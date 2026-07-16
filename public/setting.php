<?php
include("../config/database.php");
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();

// ✅ Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ✅ Prepare query
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

// ✅ If user not found
if (!$user) {
    die("User not found");
}
?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capturra - Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        body { background: #0f0f13; color: #e2e0f0; margin: 0; padding: 0; }
 
        .s-card {
            background: #16161f;
            border: 1px solid #2a2a3e;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 18px;
        }
        .s-card h3 { font-size:15px; font-weight:600; color:#e2e0f0; margin:0 0 4px; }
        .s-desc    { font-size:12px; color:#6b6b8a; margin:0 0 20px; }
 
        .s-input {
            width: 100%;
            background: #0f0f13;
            border: 1px solid #3a3a5c;
            border-radius: 10px;
            color: #e2e0f0;
            padding: 10px 14px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color .3s;
            margin-bottom: 14px;
            box-sizing: border-box;
        }
        .s-input:focus { border-color: #7c3aed; }
        .s-input::placeholder { color: #4a4a6a; }
 
        .s-label { display:block; font-size:12px; color:#a0a0c0; margin-bottom:5px; }
 
        .save-btn {
            background: linear-gradient(135deg, #7c3aed, #5b21b6);
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: opacity .3s;
        }
        .save-btn:hover { opacity: 0.88; }
 
        .danger-btn {
            background: transparent;
            border: 1px solid #7f1d1d;
            color: #f87171;
            padding: 9px 20px;
            border-radius: 10px;
            font-size: 13px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: all .3s;
        }
        .danger-btn:hover { background: #2a1010; border-color: #ef4444; }
 
        .toggle { position:relative; width:42px; height:22px; flex-shrink:0; }
        .toggle input { opacity:0; width:0; height:0; }
        .t-slider {
            position:absolute; inset:0;
            background:#2a2a3e; border-radius:22px; cursor:pointer; transition:.3s;
        }
        .t-slider::before {
            content:''; position:absolute;
            width:16px; height:16px; background:#6b6b8a;
            border-radius:50%; left:3px; top:3px; transition:.3s;
        }
        .toggle input:checked + .t-slider { background:#1e1535; }
        .toggle input:checked + .t-slider::before { transform:translateX(20px); background:#a855f7; }
 
        .t-row {
            display:flex; justify-content:space-between; align-items:center;
            padding:13px 0; border-bottom:1px solid #1e1e2e;
        }
        .t-row:last-child { border-bottom:none; padding-bottom:0; }
        .t-title { font-size:13px; color:#e2e0f0; }
        .t-sub   { font-size:11px; color:#6b6b8a; margin-top:2px; }
 
        .sidebar-nav {
            background: #16161f;
            border: 1px solid #2a2a3e;
            border-radius: 14px;
            overflow: hidden;
        }
        .s-nav-item {
            display:flex; align-items:center; gap:10px;
            padding:12px 18px; font-size:13px; color:#a0a0c0;
            cursor:pointer; border-left:3px solid transparent; transition:all .2s;
            border:none; background:none; width:100%; text-align:left;
            font-family:'Inter',sans-serif;
        }
        .s-nav-item:hover { background:#1e1535; color:#e2e0f0; }
        .s-nav-item.active { background:#1e1535; color:#a855f7; border-left:3px solid #7c3aed; }
        .s-nav-item i { width:16px; text-align:center; font-size:13px; }
 
        .tab-content { display:none; }
        .tab-content.active { display:block; }
 
        .avatar-circle {
            width:72px; height:72px; border-radius:50%;
            background:linear-gradient(135deg,#7c3aed,#a855f7);
            display:flex; align-items:center; justify-content:center;
            font-size:24px; font-weight:700; color:#fff; flex-shrink:0;
        }
        .plan-badge {
            background:#1e1535; color:#a855f7;
            border:1px solid #3a2060; font-size:11px;
            padding:3px 10px; border-radius:20px; font-weight:600;
        }
        .edit-btn {
    position: absolute;
    right: 10px;
    top: 32px;
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 14px;
    color: #a855f7;
}

.toast {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: #1e1535;
    color: #fff;
    padding: 14px 18px;
    border-radius: 10px;
    font-size: 13px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    border: 1px solid #3a2060;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
    z-index: 9999;
}

.toast.show {
    opacity: 1;
    transform: translateY(0);
}

.toast.success {
    border-color: #22c55e;
}

.toast.error {
    border-color: #ef4444;
}
    </style>
</head>
<body>
 
<div style="max-width:1100px; margin:0 auto; padding:40px 24px 60px;">

<!-- ✅ HEADER (PERFECT FLEX STRUCTURE) -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:12px;">

    <!-- LEFT SIDE -->
    <div>
        <h1 style="font-size:28px; font-weight:700; color:#fff; margin:0;">Settings</h1>
        <p style="font-size:13px; color:#6b6b8a; margin:2px 0 0;">
            Manage your account preferences and privacy
        </p>
    </div>

    <!-- RIGHT SIDE BUTTON -->
    <?php
        $dashboard = ($user['role'] === 'Photographer') 
            ? 'photographer_home.php' 
            : 'client_home.php';
    ?>

    <a href="<?php echo $dashboard; ?>" 
       style="display:inline-flex; align-items:center; gap:8px; 
              background:#1e1535; color:#a855f7; padding:10px 16px; 
              border-radius:10px; font-size:13px; text-decoration:none;
              transition:0.2s ease;"
       onmouseover="this.style.background='#2a1d4d'"
       onmouseout="this.style.background='#1e1535'">

        <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
    </a>

</div>

<!-- ✅ MAIN GRID -->
<div style="display:grid; grid-template-columns:220px 1fr; gap:24px;">

    <!-- SIDEBAR -->
    <div>

        <!-- PROFILE CARD -->
        <div class="s-card" style="display:flex; align-items:center; gap:14px; margin-bottom:14px;">
            
            <div class="avatar-circle" style="width:48px;height:48px;font-size:16px; overflow:hidden;">
                <?php if(!empty($user['profile_image'])): ?>
                    <img src="../uploads/profiles/<?php echo $user['profile_image']; ?>" 
                         style="width:100%;height:100%;object-fit:cover;">
                <?php else: ?>
                    <?php 
                    echo strtoupper(
                        substr($user['first_name'] ?? 'U',0,1) . 
                        substr($user['last_name'] ?? '',0,1)
                    ); 
                    ?>
                <?php endif; ?>
            </div>

            <div>
                <div style="font-size:13px; font-weight:600; color:#fff;">
                    <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                </div>
                <div style="font-size:11px; color:#6b6b8a; margin-top:2px;">
                    <?php echo $user['email']; ?>
                </div>
                <div style="margin-top:4px;">
                    <span class="plan-badge">Pro</span>
                </div>
            </div>
        </div>

        <!-- SIDEBAR NAV -->
        <div class="sidebar-nav">
            <button class="s-nav-item active" onclick="switchTab('profile',this)">
                <i class="fa-solid fa-user"></i> Profile
            </button>

            <button class="s-nav-item" onclick="switchTab('account',this)">
                <i class="fa-solid fa-shield"></i> Account & Security
            </button>

            <button class="s-nav-item" onclick="switchTab('notifications',this)">
                <i class="fa-solid fa-bell"></i> Notifications
            </button>

            <button class="s-nav-item" onclick="switchTab('privacy',this)">
                <i class="fa-solid fa-lock"></i> Privacy
            </button>

            <button class="s-nav-item" onclick="switchTab('billing',this)">
                <i class="fa-solid fa-credit-card"></i> Billing
            </button>

            <button class="s-nav-item" onclick="switchTab('appearance',this)">
                <i class="fa-solid fa-palette"></i> Appearance
            </button>
        </div>

    </div>

    <!-- CONTENT AREA -->
    <div>

            <!-- Profile -->
            <div id="tab-profile" class="tab-content active">
                <div class="s-card">

                    <h3>Profile Information</h3>
                    <p class="s-desc">Update your public profile details</p>

                    <!-- ✅ IMAGE UPLOAD FORM -->
                    <form action="upload_profile.php" method="POST" enctype="multipart/form-data">

                        <div style="display:flex; align-items:center; gap:16px; margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #1e1e2e;">
                            
                            <!-- Avatar -->
                            <div class="avatar-circle" style="overflow:hidden;">
                                <?php if(!empty($user['profile_image'])): ?>
                                    <img src="../uploads/profiles/<?php echo $user['profile_image']; ?>" 
                                         style="width:100%;height:100%;object-fit:cover;">
                                <?php else: ?>
                                    <?php 
                                    echo strtoupper(
                                        substr($user['first_name'] ?? 'U',0,1) . 
                                        substr($user['last_name'] ?? '',0,1)
                                    ); 
                                    ?>
                                <?php endif; ?>
                            </div>

                            <!-- Hidden File -->
                            <input type="file" name="profile_image" id="fileInput" hidden accept="image/*">

                            <div style="display:flex; gap:10px;">
                                <button type="button" class="save-btn"
                                        onclick="document.getElementById('fileInput').click()">
                                    Change Photo
                                </button>

                                <a href="remove_profile.php">
                                    <button type="button" class="danger-btn">
                                        Remove
                                    </button>
                                </a>
                            </div>

                        </div>
                    </form>

        <!-- FORM START -->
        <form action="save_profile.php" method="POST">

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">

            <!-- FIRST NAME -->
            <div style="position:relative;">
                <label class="s-label">First Name</label>
                <input type="text" name="first_name" class="s-input"
                       value="<?php echo $user['first_name'] ?? ''; ?>" disabled>
                <button type="button" class="edit-btn" onclick="enableEdit(this)">✏️</button>
            </div>

            <!-- LAST NAME -->
            <div style="position:relative;">
                <label class="s-label">Last Name</label>
                <input type="text" name="last_name" class="s-input"
                       value="<?php echo $user['last_name'] ?? ''; ?>" disabled>
                <button type="button" class="edit-btn" onclick="enableEdit(this)">✏️</button>
            </div>

            <!-- USERNAME -->
            <div style="position:relative;">
                <label class="s-label">Username</label>
                <input type="text" name="username" class="s-input"
                       value="<?php echo $user['username'] ?? ''; ?>" disabled>
                <button type="button" class="edit-btn" onclick="enableEdit(this)">✏️</button>
            </div>

            <!-- ROLE (keep normal dropdown) -->
            <div>
                <label class="s-label">Role</label>
                <select name="role" class="s-input" style="-webkit-appearance:none;">
                    <option value="Photographer" <?php if(($user['role'] ?? '')=='Photographer') echo 'selected'; ?>>Photographer</option>
                    <option value="Client" <?php if(($user['role'] ?? '')=='Client') echo 'selected'; ?>>Client</option>
                </select>
            </div>

            <!-- BIO -->
            <div style="grid-column:span 2; position:relative;">
                <label class="s-label">Bio</label>
                <textarea name="bio" class="s-input" rows="3" style="resize:none;" disabled><?php echo $user['bio'] ?? ''; ?></textarea>
                <button type="button" class="edit-btn" onclick="enableEdit(this)">✏️</button>
            </div>

            <!-- LOCATION -->
            <div style="position:relative;">
                <label class="s-label">Location</label>
                <input type="text" name="location" class="s-input"
                       value="<?php echo $user['location'] ?? ''; ?>" disabled>
                <button type="button" class="edit-btn" onclick="enableEdit(this)">✏️</button>
            </div>

            <!-- WEBSITE -->
            <div style="position:relative;">
                <label class="s-label">Website</label>
                <input type="url" name="website" class="s-input"
                       value="<?php echo $user['website'] ?? ''; ?>">
                <button type="button" class="edit-btn" onclick="enableEdit(this)">✏️</button>
            </div>

        </div>

        <button class="save-btn">Save Changes</button>
        </form>
        <!-- FORM END -->

    </div>
</div>
</div>
 
            <!-- Account -->
            <div id="tab-account" class="tab-content">
                <div class="s-card">
                    <h3>Email Address</h3><p class="s-desc">Update your login email</p>
                    <label class="s-label">Current Email</label><input type="email" class="s-input" value="alex@gmail.com">
                    <label class="s-label">New Email</label><input type="email" class="s-input" placeholder="Enter new email">
                    <button class="save-btn">Update Email</button>
                </div>
                <div class="s-card">
                    <h3>Change Password</h3><p class="s-desc">Use a strong password to stay secure</p>
                    <label class="s-label">Current Password</label><input type="password" class="s-input" placeholder="••••••••">
                    <label class="s-label">New Password</label><input type="password" class="s-input" placeholder="••••••••">
                    <label class="s-label">Confirm New Password</label><input type="password" class="s-input" placeholder="••••••••">
                    <button class="save-btn">Update Password</button>
                </div>
                <div class="s-card" style="border-color:#3a1010;">
                    <h3 style="color:#f87171;">Danger Zone</h3>
                    <p class="s-desc">These actions are permanent and cannot be undone.</p>
                    <div style="display:flex; gap:10px;">
                        <button class="danger-btn">Deactivate Account</button>
                        <button class="danger-btn" style="border-color:#ef4444;color:#ef4444;">Delete Account</button>
                    </div>
                </div>
            </div>
 
            <!-- Notifications -->
            <div id="tab-notifications" class="tab-content">
                <div class="s-card">
                    <h3>Email Notifications</h3><p class="s-desc">Choose what emails you want to receive</p>
                    <div class="t-row"><div><div class="t-title">New Followers</div><div class="t-sub">When someone follows your profile</div></div><label class="toggle"><input type="checkbox" checked><span class="t-slider"></span></label></div>
                    <div class="t-row"><div><div class="t-title">Photo Likes</div><div class="t-sub">When someone likes your photo</div></div><label class="toggle"><input type="checkbox" checked><span class="t-slider"></span></label></div>
                    <div class="t-row"><div><div class="t-title">Comments</div><div class="t-sub">When someone comments on your work</div></div><label class="toggle"><input type="checkbox"><span class="t-slider"></span></label></div>
                    <div class="t-row"><div><div class="t-title">Booking Requests</div><div class="t-sub">When a client requests a booking</div></div><label class="toggle"><input type="checkbox" checked><span class="t-slider"></span></label></div>
                    <div class="t-row"><div><div class="t-title">Newsletter</div><div class="t-sub">Weekly articles and tips</div></div><label class="toggle"><input type="checkbox"><span class="t-slider"></span></label></div>
                </div>
                <div class="s-card">
                    <h3>Push Notifications</h3><p class="s-desc">Manage in-app and browser notifications</p>
                    <div class="t-row"><div><div class="t-title">Enable Push Notifications</div><div class="t-sub">Real-time alerts in browser</div></div><label class="toggle"><input type="checkbox" checked><span class="t-slider"></span></label></div>
                    <div class="t-row"><div><div class="t-title">Direct Messages</div><div class="t-sub">New messages from clients</div></div><label class="toggle"><input type="checkbox" checked><span class="t-slider"></span></label></div>
                </div>
                <button class="save-btn">Save Preferences</button>
            </div>
 
            <!-- Privacy -->
            <div id="tab-privacy" class="tab-content">
                <div class="s-card">
                    <h3>Privacy Settings</h3><p class="s-desc">Control who can see your content</p>
                    <div class="t-row"><div><div class="t-title">Public Profile</div><div class="t-sub">Anyone can view your profile</div></div><label class="toggle"><input type="checkbox" checked><span class="t-slider"></span></label></div>
                    <div class="t-row"><div><div class="t-title">Show in Search Results</div><div class="t-sub">Appear when people search photographers</div></div><label class="toggle"><input type="checkbox" checked><span class="t-slider"></span></label></div>
                    <div class="t-row"><div><div class="t-title">Show Follower Count</div><div class="t-sub">Display followers on your profile</div></div><label class="toggle"><input type="checkbox" checked><span class="t-slider"></span></label></div>
                    <div class="t-row"><div><div class="t-title">Allow Direct Messages</div><div class="t-sub">Let clients message you</div></div><label class="toggle"><input type="checkbox" checked><span class="t-slider"></span></label></div>
                    <div class="t-row"><div><div class="t-title">Show Activity Status</div><div class="t-sub">Show when you were last active</div></div><label class="toggle"><input type="checkbox"><span class="t-slider"></span></label></div>
                </div>
                <button class="save-btn">Save Privacy Settings</button>
            </div>
 
            <!-- Billing -->
            <div id="tab-billing" class="tab-content">
                <div class="s-card">
                    <h3>Current Plan</h3><p class="s-desc">Your active subscription</p>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px;border-radius:10px;background:#1a0a2e;border:1px solid #3a2060;margin-bottom:16px;">
                        <div>
                            <div style="font-size:14px;font-weight:600;color:#fff;">Pro Plan</div>
                            <div style="font-size:12px;color:#a0a0c0;margin-top:3px;">₹999/month · Renews May 15, 2025</div>
                        </div>
                        <span class="plan-badge">Active</span>
                    </div>
                    <div style="display:flex;gap:10px;">
                        <button class="save-btn">Upgrade Plan</button>
                        <button class="danger-btn">Cancel Plan</button>
                    </div>
                </div>
                <div class="s-card">
                    <h3>Payment Method</h3><p class="s-desc">Your saved payment methods</p>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px;border-radius:10px;background:#0f0f13;border:1px solid #2a2a3e;margin-bottom:14px;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <i class="fa-brands fa-cc-visa" style="font-size:22px;color:#a855f7;"></i>
                            <div>
                                <div style="font-size:13px;font-weight:500;color:#fff;">Visa ending in 4242</div>
                                <div style="font-size:11px;color:#6b6b8a;margin-top:2px;">Expires 12/26</div>
                            </div>
                        </div>
                        <button class="danger-btn" style="font-size:12px;padding:6px 12px;">Remove</button>
                    </div>
                    <button class="save-btn" style="font-size:12px;padding:9px 18px;">+ Add Payment Method</button>
                </div>
                <div class="s-card">
                    <h3>Billing History</h3><p class="s-desc">Your recent transactions</p>
                    <table style="width:100%;font-size:13px;border-collapse:collapse;">
                        <thead><tr style="color:#6b6b8a;border-bottom:1px solid #2a2a3e;">
                            <th style="text-align:left;padding-bottom:10px;font-weight:500;">Date</th>
                            <th style="text-align:left;padding-bottom:10px;font-weight:500;">Description</th>
                            <th style="text-align:left;padding-bottom:10px;font-weight:500;">Amount</th>
                            <th style="text-align:left;padding-bottom:10px;font-weight:500;">Status</th>
                        </tr></thead>
                        <tbody style="color:#a0a0c0;">
                            <tr style="border-bottom:1px solid #1e1e2e;"><td style="padding:10px 0;">Apr 15, 2025</td><td>Pro Plan</td><td>₹999</td><td style="color:#4ade80;font-size:12px;">✓ Paid</td></tr>
                            <tr style="border-bottom:1px solid #1e1e2e;"><td style="padding:10px 0;">Mar 15, 2025</td><td>Pro Plan</td><td>₹999</td><td style="color:#4ade80;font-size:12px;">✓ Paid</td></tr>
                            <tr><td style="padding:10px 0;">Feb 15, 2025</td><td>Pro Plan</td><td>₹999</td><td style="color:#4ade80;font-size:12px;">✓ Paid</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
 
            <!-- Appearance -->
            <div id="tab-appearance" class="tab-content">
                <div class="s-card">
                    <h3>Theme</h3><p class="s-desc">Choose your preferred interface theme</p>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
                        <div style="padding:16px;border-radius:10px;text-align:center;cursor:pointer;background:#0f0f13;border:2px solid #7c3aed;">
                            <div style="font-size:22px;margin-bottom:6px;">🌙</div>
                            <div style="font-size:13px;font-weight:500;color:#fff;">Dark</div>
                            <div style="font-size:11px;margin-top:2px;color:#a855f7;">Active</div>
                        </div>
                        <div style="padding:16px;border-radius:10px;text-align:center;cursor:pointer;background:#0f0f13;border:1px solid #2a2a3e;">
                            <div style="font-size:22px;margin-bottom:6px;">☀️</div>
                            <div style="font-size:13px;font-weight:500;color:#fff;">Light</div>
                            <div style="font-size:11px;margin-top:2px;color:#6b6b8a;">Coming soon</div>
                        </div>
                        <div style="padding:16px;border-radius:10px;text-align:center;cursor:pointer;background:#0f0f13;border:1px solid #2a2a3e;">
                            <div style="font-size:22px;margin-bottom:6px;">💻</div>
                            <div style="font-size:13px;font-weight:500;color:#fff;">System</div>
                            <div style="font-size:11px;margin-top:2px;color:#6b6b8a;">Auto</div>
                        </div>
                    </div>
                </div>
                <div class="s-card">
                    <h3>Language</h3><p class="s-desc">Select your display language</p>
                    <select class="s-input" style="-webkit-appearance:none;width:200px;">
                        <option selected>English</option>
                        <option>Hindi</option>
                        <option>Spanish</option>
                        <option>French</option>
                    </select>
                    <button class="save-btn">Save</button>
                </div>
            </div>
 
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>

<script>
document.getElementById("fileInput").addEventListener("change", function() {
    if (this.files.length > 0) {
        this.form.submit(); // auto upload on select
    }
});
</script>

<script>
document.getElementById("fileInput").addEventListener("change", function() {
    this.form.submit();
});
</script>

<script>
// ✅ TAB SWITCHING (FIXED)
function switchTab(name, el) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.s-nav-item').forEach(s => s.classList.remove('active'));

    const target = document.getElementById('tab-' + name);

    if (!target) {
        console.error("Tab not found: tab-" + name);
        return;
    }

    target.classList.add('active');

    if (el) el.classList.add('active');
}

// ✅ ENABLE EDIT BUTTON
function enableEdit(btn) {
    const input = btn.previousElementSibling;

    if (!input) return;

    input.removeAttribute("disabled");
    input.focus();
}

// ✅ TOAST SYSTEM
function showToast(message, type = "success") {
    const toast = document.getElementById("toast");

    if (!toast) return;

    toast.innerText = message;
    toast.className = "toast show " + type;

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}

// ✅ PAGE LOAD FIX
document.addEventListener("DOMContentLoaded", () => {

    // FIX: Always show profile tab by default
    const defaultTab = document.getElementById("tab-profile");
    if (defaultTab) defaultTab.classList.add("active");

    // Toast from URL params
    const params = new URLSearchParams(window.location.search);

    if (params.get("success")) {
        showToast("Profile updated successfully ✅", "success");
    }

    if (params.get("error")) {
        showToast("Something went wrong ❌", "error");
    }

});
</script>
</body>
</html>