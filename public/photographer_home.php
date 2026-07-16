<?php

$hidden_comments = $hidden_comments ?? [];
$total_comments = $total_comments ?? 0;

require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/auth.php";
requireRole("photographer");

include("../config/database.php");

$username = $_SESSION['username'];
$name     = $_SESSION['name'];
$user_id  = $_SESSION['user_id'];

date_default_timezone_set('Asia/Kolkata');

function timeAgo($datetime) {
    if (empty($datetime)) return "Unknown time";

    $time = strtotime($datetime);
    if (!$time) return "Unknown time";

    $diff = time() - $time;

    if ($diff < 60) return "Just now";

    if ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . " min" . ($mins > 1 ? "s" : "") . " ago";
    }

    if ($diff < 86400) {
        $hrs = floor($diff / 3600);
        return $hrs . " hr" . ($hrs > 1 ? "s" : "") . " ago";
    }

    if ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . " day" . ($days > 1 ? "s" : "") . " ago";
    }

    return date("d M Y, H:i", $time);
}

// Fetch photos + total likes
$sql = "
SELECT photos.*, 
       COUNT(likes.id) AS total_likes
FROM photos
LEFT JOIN likes ON photos.id = likes.photo_id
WHERE photos.user_id = '$user_id'
GROUP BY photos.id
ORDER BY photos.id DESC
";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capturra - Where Creators Shine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .photographer-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        
        .notification-dot {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .upload-zone {
            border: 2px dashed #d1d5db;
            transition: all 0.3s ease;
        }
        
        .upload-zone:hover {
            border-color: #667eea;
            background-color: #f8fafc;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .ranking-badge {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }
        /* Post image hover effect */
        .post-img { transition: opacity .2s ease-in-out; }
        .post-img:hover { opacity: .5; }
        /* Dark mode overrides (photographer) */
       /* Dark mode overrides (photographer) */
.dark body { background-color: #0a0a0a; color: #f0f0f0; }
.dark nav { background-color: #111111 !important; border-color: #222222 !important; }
.dark nav a, .dark nav .text-gray-900, .dark nav .text-gray-700 { color: #f0f0f0 !important; }
.dark .bg-white { background-color: #1a1a1a !important; color: #f0f0f0 !important; border-color: #2a2a2a !important; }
.dark .text-gray-900 { color: #f0f0f0 !important; }
.dark .text-gray-700, .dark .text-gray-500 { color: #bbbbbb !important; }
.dark .border-gray-200 { border-color: #2a2a2a !important; }
.dark .upload-zone { border-color: #444444 !important; background-color: rgba(255,255,255,0.03) !important; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php if(isset($_SESSION['success'])): ?>
    <div id="popup" style="
        position: fixed;
        top: 20px;
        right: 20px;
        background: #22c55e;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: bold;
        z-index: 9999;
    ">
        <?php 
            echo $_SESSION['success']; 
            unset($_SESSION['success']);
        ?>
    </div>

    <script>
        setTimeout(function() {
            document.getElementById("popup").style.display = "none";
        }, 3000);
    </script>
<?php endif; ?>

    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo + Tagline -->
                <div class="flex items-center cursor-pointer" onclick="goHome()">
                    <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <span class="text-white font-bold text-lg">📸</span>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-gray-900">Capturra</span>
                        <p class="text-xs text-gray-500">Where Creators Shine</p>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <div class="flex-1 max-w-lg mx-8">
                    <div class="relative">
                        <input type="text" placeholder="Search photos, clients, or creators..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <div class="flex items-center space-x-6">
                    <a href="photographer_home.php" class="text-gray-700 hover:text-purple-600 font-medium transition-colors">Home</a>
                    <a href="explore.php" class="text-gray-700 hover:text-purple-600 font-medium transition-colors">Explore</a>
                    <a href="trending.php" class="text-gray-700 hover:text-purple-600 font-medium transition-colors">Trending</a>
                    
                    

                   <div class="relative inline-block">
    <div class="relative cursor-pointer group" onclick="toggleMessages(event)">
        <span class="text-2xl group-hover:scale-110 transition-transform inline-block">💬</span>
        <div class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-blue-500 rounded-full border-2 border-white dark:border-slate-900 notification-dot"></div>
    </div>

    <div id="msgDropdown" class="hidden absolute right-0 mt-4 w-80 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-slate-100 dark:border-slate-800 z-[999] overflow-hidden transform origin-top-right transition-all">
        
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/30">
            <h3 class="text-[11px] font-black dark:text-white uppercase tracking-widest text-slate-500">Recent Inquiries</h3>
            <span class="bg-purple-600 text-white text-[9px] px-2 py-0.5 rounded-full font-black animate-pulse">NEW</span>
        </div>

        <div class="max-h-[380px] overflow-y-auto">
            <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/80 transition-all border-b border-slate-50 dark:border-slate-800/50">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-sm">
                            JD
                        </div>
                        <div>
                            <h4 class="text-xs font-black dark:text-white text-slate-800">John Doe</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">Wedding Inquiry</p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="https://wa.me/91XXXXXXXXXX" target="_blank" 
                           class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#25D366] text-white shadow-lg shadow-green-500/20 hover:scale-110 active:scale-95 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </a>
                        <a href="mailto:john@gmail.com" 
                           class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#EA4335] text-white shadow-lg shadow-red-500/20 hover:scale-110 active:scale-95 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            </div>

        <a href="messages.php" class="block p-4 text-center text-[10px] font-black text-slate-400 bg-slate-50 dark:bg-slate-800/40 hover:text-purple-600 transition-colors uppercase tracking-[0.2em]">
            Open Message Center
        </a>
    </div>
</div>

<script>
function toggleMessages(event) {
    event.stopPropagation(); // Icon click par hi dropdown khulega
    const dropdown = document.getElementById('msgDropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking anywhere outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('msgDropdown');
    const trigger = event.target.closest('.cursor-pointer');
    
    if (!dropdown.classList.contains('hidden') && !dropdown.contains(event.target) && !trigger) {
        dropdown.classList.add('hidden');
    }
});
</script>

                    <!-- Notifications -->
                  <div class="relative">
    <div class="relative cursor-pointer flex items-center" onclick="toggleNotifications(event)">
        <span class="text-2xl hover:scale-110 transition-transform">🔔</span>
        <div id="notifDot" class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-[#0d0d11]"></div>
    </div>

    <div id="notificationMenu" class="hidden absolute right-0 mt-3 w-80 bg-[#16161f] border border-[#2a2a3e] rounded-xl shadow-2xl z-[100] overflow-hidden">
        <div class="p-4 border-b border-[#2a2a3e] flex justify-between items-center">
            <h3 class="text-white font-bold text-sm">Notifications</h3>
            <button onclick="clearNotifDot()" class="text-[10px] text-purple-400 hover:underline">Mark all as read</button>
        </div>
        
        <div class="max-h-64 overflow-y-auto">
            <div class="p-4 border-b border-[#1e1e2e] hover:bg-[#1e1e2e] transition-colors cursor-pointer">
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-purple-500/20 flex items-center justify-center text-xs">❤️</div>
                    <div>
                        <p class="text-sm text-white"><strong>Rahul</strong> liked your photo.</p>
                        <p class="text-[10px] text-slate-500">2 minutes ago</p>
                    </div>
                </div>
            </div>

            <div class="p-4 border-b border-[#1e1e2e] hover:bg-[#1e1e2e] transition-colors cursor-pointer">
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-xs">💬</div>
                    <div>
                        <p class="text-sm text-white"><strong>Sneha</strong> commented on your post.</p>
                        <p class="text-[10px] text-slate-500">1 hour ago</p>
                    </div>
                </div>
            </div>

            <div id="emptyNotif" class="hidden p-8 text-center">
                <p class="text-xs text-slate-500">No new notifications</p>
            </div>
        </div>

        <a href="notification.php" class="block p-3 text-center text-xs text-purple-400 hover:bg-[#1e1e2e] font-medium">View all notifications</a>
    </div>
</div>
                    
                    <!-- Dark mode toggle -->
                    <button id="darkToggle" title="Toggle dark mode" class="flex items-center justify-center w-9 h-9 rounded-md text-gray-700 hover:bg-gray-100 transition-colors" aria-pressed="false">🌙</button>

                    <!-- Profile Menu -->
                    <div class="relative">
                        <button onclick="toggleProfileMenu()" class="flex items-center space-x-2 focus:outline-none">
                            <div class="w-8 h-8 rounded-full border-2 border-purple-500 flex items-center justify-center bg-gray-100">
    <span class="text-gray-400 text-sm">👤</span>
</div>
                        </button>
                        <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2">
                            <a href="PROFILE_PAGE.PHP" onclick="closeProfileMenu()" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Profile</a>
                            <a href="setting.php" onclick="closeProfileMenu()" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Settings</a>
                            <a href="photographer_analytics.php" onclick="closeProfileMenu()" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Analytics</a>
                            <hr class="my-1">
                            <a href="/Capturra/api/logout.php" 
   class="block px-4 py-2 text-red-600 hover:bg-gray-50">
   Logout
</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar - Profile Snapshot -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="text-center">
       <div class="w-16 h-16 rounded-full mx-auto mb-4 border-4 border-purple-500 flex items-center justify-center bg-gray-100">
    <span class="text-2xl text-gray-400">👤</span>
</div>
                        <h3 class="font-semibold text-gray-900"><?php echo $name; ?></h3>
                        <p class="text-sm text-gray-500 mb-4">@<?php echo $username; ?></p>
                        <p class="text-sm text-gray-500 mb-4">Wedding & Lifestyle Photographer</p>
                        
                        <!-- Ranking Badge -->
                        <div class="ranking-badge inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-orange-800 mb-4">
                            🏆 Rank #47
                        </div>
                        
                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                            <div class="text-center">
                                <div class="font-bold text-gray-900">2.8K</div>
                                <div class="text-gray-500">Followers</div>
                            </div>
                            <div class="text-center">
                                <div class="font-bold text-gray-900">342</div>
                                <div class="text-gray-500">Following</div>
                            </div>
                            <div class="text-center">
                                <div class="font-bold text-gray-900">15.2K</div>
                                <div class="text-gray-500">Total Likes</div>
                            </div>
                            <div class="text-center">
                                <div class="font-bold text-gray-900">1.2K</div>
                                <div class="text-gray-500">Views</div>
                            </div>
                        </div>
                        
                        <!-- CTA Buttons -->
                        <div class="space-y-2">
                            <button onclick="editProfile()" class="w-full bg-purple-500 text-white py-2 px-4 rounded-lg hover:bg-purple-600 transition-colors font-medium">
                                Edit Profile
                            </button>
                            <button onclick="addServices()" class="w-full bg-gray-100 text-black py-2 px-4 rounded-lg hover:bg-black-200 transition-colors font-medium ">
                                Add Services
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Upload Shortcut -->
             <div class="bg-white p-6 rounded-xl shadow-md">
    <h3 class="font-semibold mb-4">Quick Upload</h3>

    <form action="../upload/upload.php" method="POST" enctype="multipart/form-data">

    <input type="file" name="photo" required
           class="block w-full text-sm mb-4">

    <button type="submit"
        class="w-full bg-purple-600 text-white py-2 rounded-lg 
               hover:bg-purple-700 transition duration-300">
        Upload Photo
    </button>

</form>
</div>


                <!-- Tips & Resources -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">💡 Growth Tips</h3>
                    <div class="space-y-3">
                        <div class="space-y-3">
    <div class="p-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg cursor-pointer hover:from-blue-100 hover:to-purple-100 transition-colors border border-blue-100/50">
    <p class="text-sm font-bold text-black mb-0.5">5 Tips to Improve Lighting</p>
    
    <p class="text-xs font-medium text-black">Master natural light techniques</p>
</div>

    <div class="p-3 bg-gradient-to-r from-green-50 to-teal-50 rounded-lg cursor-pointer hover:from-green-100 hover:to-teal-100 transition-colors border border-green-100/50">
        <p class="text-sm font-bold text-black">Boost Your Portfolio Views</p>
        <p class="text-xs font-medium text-black">SEO tips for photographers</p>
    </div>

    <div class="p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg cursor-pointer hover:from-yellow-100 hover:to-orange-100 transition-colors border border-yellow-100/50">
        <p class="text-sm font-bold text-black">Client Communication</p>
        <p class="text-xs font-medium text-black">Build lasting relationships</p>
    </div>
</div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Quick Links</h3>
                    <div class="space-y-2">
                        <a href="blogs.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="h-5 w-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="text-gray-700">Blogs</span>
                        </a>
                        <a href="my_bookings.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="h-5 w-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span class="text-gray-700">My Bookings</span>
                        </a>


                        <a href="setting.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="h-5 w-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-700">Settings</span>
                        </a>
                        <a href="support.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="h-5 w-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"></path>
                            </svg>
                            <span class="text-gray-700"  >Support</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Welcome / Hero Section -->
                <div class="photographer-gradient rounded-xl p-8 text-white">
                                        <h1 class="text-3xl font-bold mb-2">
  Welcome back, <?php echo htmlspecialchars($name); ?> 👋
</h1>
                    <p class="text-pink-100 mb-6">Ready to showcase your amazing work to the world?</p>
                    <div class="flex flex-wrap gap-4">
                        <form id="centerUploadForm" action="../uploads/upload.php" method="POST" enctype="multipart/form-data">

    <input type="file" name="photo" id="centerPhotoInput" 
           style="display: none;" 
           onchange="document.getElementById('centerUploadForm').submit();">

    <button type="button"
        onclick="document.getElementById('centerPhotoInput').click();"
        class="bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors flex items-center space-x-2">
        📷 Upload Photo
    </button>

</form>

                        </button>
                        <button onclick="managePortfolio()" class="bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-800 transition-colors">
                            Manage Portfolio
                        </button>
                        <button onclick="viewAnalytics()" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition-colors">
                            View Analytics
                        </button>
                    </div>
                </div>
<?php if(mysqli_num_rows($result) > 0) { ?>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<div class="bg-white rounded-xl shadow-md overflow-hidden relative">

   <!-- Image -->
   <!-- Image -->
   <img src="/Capturra/uploads/<?php echo htmlspecialchars($row['image']); ?>"
     class="w-full rounded-t-xl cursor-pointer"
     style="max-height:300px; object-fit:contain;"
     onclick="openModal(this.src)">
    <!-- Like Button -->
    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 z-20">
        <form action="../actions/like.php" method="POST">
            <input type="hidden" name="photo_id" value="<?php echo $row['id']; ?>">
            <button type="submit" class="bg-white px-3 py-2 rounded-full shadow flex items-center space-x-2">
                ❤️ <span class="text-sm"><?php echo $row['total_likes']; ?></span>
            </button>
        </form>
    </div>

    <!-- Comment Button -->
    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 z-20">
        <button onclick="toggleComment(this)" class="bg-white px-3 py-2 rounded-full shadow flex items-center space-x-2">
            💬 <span class="text-sm"><?php echo $total_comments ?? 0; ?></span>
        </button>
    </div>

    <!-- Comments -->
    <div class="px-4 mt-2 text-sm text-gray-800">

        <?php if(!empty($hidden_comments)): ?>
            <div onclick="showAllComments(this)" class="text-gray-500 cursor-pointer text-sm mb-1">
                View all <?php echo $total_comments; ?> comments
            </div>

            <div class="hidden older-comments">
                <?php foreach($hidden_comments as $comment): ?>
                    <div>
                        <b><?php echo htmlspecialchars($comment['username']); ?>:</b>
                        <?php echo htmlspecialchars($comment['comment']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php foreach(($initial_comments ?? []) as $comment): ?>
            <div>
                <b><?php echo htmlspecialchars($comment['username']); ?>:</b>
                <?php echo htmlspecialchars($comment['comment']); ?>
            </div>
        <?php endforeach; ?>

    </div>

    <!-- Comment Box -->
    <div class="comment-box hidden px-4 pb-4">
        <form action="../actions/comment.php" method="POST">
            <input type="hidden" name="photo_id" value="<?php echo $row['id']; ?>">
            <input type="text" name="comment" required placeholder="Write a comment..." class="border p-2 w-full">
        </form>
    </div>

    <!-- Upload Time -->
    <div class="px-4 py-2 text-xs text-gray-500">
        <?php echo date('d M Y', strtotime($row['upload_date'])); ?>
    </div>

</div>

<?php } ?>  <!-- while close -->

</div>

<?php } else { ?>

<p class="text-gray-500 px-4">No photos uploaded yet.</p>

<?php } ?>

                <!-- Trending Photos -->
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">🔥 Trending in Community</h2>
                    <div class="photo-grid">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden card-hover">
                            <img src="https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?w=400&h=300&fit=crop" alt="Street photography" class="w-full post-img" style="height:auto; object-fit:contain;">
                            <div class="p-4">
                                <div class="flex items-center space-x-3 mb-3">
                                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=32&h=32&fit=crop&crop=face" alt="Creator" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="font-semibold text-gray-900">Mike Rodriguez</p>
                                        <p class="text-sm text-gray-500">@mikestreet</p>
                                    </div>
                                </div>
                                <p class="text-gray-700 mb-3">Urban life captured perfectly</p>
                                <div class="flex items-center space-x-4">
                                    <button onclick="likePost(this)" class="flex items-center space-x-1 text-gray-500 hover:text-red-500 transition-colors">
                                        <span>❤️</span>
                                        <span class="text-sm">892</span>
                                    </button>
                                    <button onclick="commentPost()" class="flex items-center space-x-1 text-gray-500 hover:text-blue-500 transition-colors">
                                        <span>💬</span>
                                        <span class="text-sm">156</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                
            </div>

            <!-- Right Sidebar -->
            <div class="lg:col-span-1 space-y-6">

                <!-- Top Creators Leaderboard -->
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-900">🏆 Top Creators</h3>
        <a href="creator.php" class="text-xs font-medium text-purple-600 hover:text-purple-800 transition-colors flex items-center gap-1">
            View More <span>→</span>
        </a>
    </div>

    <div class="space-y-4">
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center justify-center w-6 h-6 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">1</span>
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=32&h=32&fit=crop&crop=face" alt="Top creator" class="w-8 h-8 rounded-full">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">Alex Chen</p>
                <p class="text-xs text-gray-500">15.2K followers</p>
            </div>
            <button onclick="followCreator(this)" class="text-xs bg-purple-100 text-purple-700 px-3 py-1 rounded-full hover:bg-purple-200 transition-colors">Follow</button>
        </div>

        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">2</span>
            <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=32&h=32&fit=crop&crop=face" alt="Top creator" class="w-8 h-8 rounded-full">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">Sarah Johnson</p>
                <p class="text-xs text-gray-500">12.8K followers</p>
            </div>
            <button onclick="followCreator(this)" class="text-xs bg-purple-100 text-purple-700 px-3 py-1 rounded-full hover:bg-purple-200 transition-colors">Follow</button>
        </div>

        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center justify-center w-6 h-6 bg-orange-100 text-orange-800 text-xs font-medium rounded-full">3</span>
            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=32&h=32&fit=crop&crop=face" alt="Top creator" class="w-8 h-8 rounded-full">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">Mike Rodriguez</p>
                <p class="text-xs text-gray-500">9.5K followers</p>
            </div>
            <button onclick="followCreator(this)" class="text-xs bg-purple-100 text-purple-700 px-3 py-1 rounded-full hover:bg-purple-200 transition-colors">Follow</button>
        </div>
    </div>
</div>

                <!-- Recommended Creators -->
               <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-900">💡 Recommended Creators</h3>
        <a href="search.php" class="text-xs font-medium text-purple-600 hover:text-purple-800 transition-colors flex items-center gap-1">
            View More <span>→</span>
        </a>
    </div>

    <div class="space-y-4">
        <div class="flex items-center space-x-3">
            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=32&h=32&fit=crop&crop=face" alt="Recommended creator" class="w-8 h-8 rounded-full">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">Emma Wilson</p>
                <p class="text-xs text-gray-500">Portrait specialist</p>
            </div>
            <button onclick="followCreator(this)" class="text-xs bg-purple-100 text-purple-700 px-3 py-1 rounded-full hover:bg-purple-200 transition-colors">Follow</button>
        </div>
        
        <div class="flex items-center space-x-3">
            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=32&h=32&fit=crop&crop=face" alt="Recommended creator" class="w-8 h-8 rounded-full">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">David Park</p>
                <p class="text-xs text-gray-500">Nature photographer</p>
            </div>
            <button onclick="followCreator(this)" class="text-xs bg-purple-100 text-purple-700 px-3 py-1 rounded-full hover:bg-purple-200 transition-colors">Follow</button>
        </div>
    </div>
</div>

                <!-- Suggested Hashtags -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">🔥 Suggested Hashtags</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 cursor-pointer hover:bg-purple-200 transition-colors">#Wedding</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 cursor-pointer hover:bg-blue-200 transition-colors">#Portrait</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 cursor-pointer hover:bg-green-200 transition-colors">#Nature</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 cursor-pointer hover:bg-blue-200 transition-colors">#Lifestyle</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 cursor-pointer hover:bg-yellow-200 transition-colors">#Photography</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 cursor-pointer hover:bg-indigo-200 transition-colors">#Creative</span>
                    </div>
                </div>

                <!-- Opportunities Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">💼 Opportunities</h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg cursor-pointer hover:from-green-100 hover:to-emerald-100 transition-colors">
                            <p class="text-sm font-medium text-black">Wedding Photography</p>
                            <p class="text-xs text-gray-600">Delhi • ₹25,000 - ₹40,000</p>
                            <p class="text-xs text-green-600 font-medium">2 days ago</p>
                        </div>
                        <div class="p-3 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg cursor-pointer hover:from-blue-100 hover:to-cyan-100 transition-colors">
                            <p class="text-sm font-medium text-black">Product Shoot</p>
                            <p class="text-xs text-gray-600">Mumbai • ₹15,000 - ₹25,000</p>
                            <p class="text-xs text-blue-600 font-medium">5 days ago</p>
                        </div>
                    </div>
                </div>
                

                    <!-- Follower Activity-->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">👥 Follower Activity</h2>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-4">
                                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=40&h=40&fit=crop&crop=face" alt="Follower" class="w-10 h-10 rounded-full">
                                    <div class="flex-1">
                                        <p class="text-sm"><span class="font-semibold">Sarah Johnson</span> liked your photo "Golden Hour Portrait"</p>
                                        <p class="text-xs text-gray-500">2 minutes ago</p>
                                    </div>
                                    <span class="text-red-500">❤️</span>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=40&h=40&fit=crop&crop=face" alt="Follower" class="w-10 h-10 rounded-full">
                                    <div class="flex-1">
                                        <p class="text-sm"><span class="font-semibold">Emma Wilson</span> commented on "Beautiful Wedding Moment"</p>
                                        <p class="text-xs text-gray-500">15 minutes ago</p>
                                    </div>
                                    <span class="text-blue-500">💬</span>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" alt="Follower" class="w-10 h-10 rounded-full">
                                    <div class="flex-1">
                                        <p class="text-sm"><span class="font-semibold">David Park</span> started following you</p>
                                        <p class="text-xs text-gray-500">1 hour ago</p>
                                    </div>
                                    <span class="text-green-500">👤</span>
                                </div>
                            </div>
                        </div>
                </section>
            </div>
            </div>
        </div>
    </div>

  

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-80 hidden items-center justify-center z-50">
    
    <!-- Close Button -->
    <span onclick="closeModal()" class="absolute top-5 right-8 text-white text-3xl cursor-pointer">&times;</span>
    
    <!-- Image -->
    <img id="modalImage" class="max-h-[90%] max-w-[90%] rounded-lg shadow-lg">

</div>
  


    <script>
        // Navigation functions
        function goHome() {
            alert('Navigating to photographer homepage!');
        }

        function openMessages() {
            alert('Opening messages - you have 3 new client inquiries! 💬');
        }

        function toggleNotifications() {
            alert('Notifications: New follower, 5 likes on your recent photo! 🔔');
        }

        function toggleProfileMenu() {
            const menu = document.getElementById('profileMenu');
            menu.classList.toggle('hidden');
        }

        // Profile functions
        function editProfile() {
            window.location.href = 'photographer_profile.php';
        }

        function addServices() {
            alert('Add your photography services - Wedding, Portrait, Commercial, etc.');
        }

        // Hero section functions
        function uploadPhoto() {
            alert('Photo upload dialog opening! 📷 Drag & drop your best shots here!');
        }

        function managePortfolio() {
            alert('Portfolio manager opening - organize your best work!');
        }

        function managePortfolio() {
    window.location.href = "http://localhost:8888/Capturra/public/portfolio.php?id=<?php echo $_SESSION['user_id']; ?>";
}

        function viewAnalytics() {
            window.location.href = 'photographer_analytics.php';
        }

        // Post interaction functions
        function likePost(button) {
            const likeCount = button.querySelector('span:last-child');
            const currentCount = parseInt(likeCount.textContent);
            likeCount.textContent = currentCount + 1;
            button.classList.add('text-red-500');
            button.classList.remove('text-gray-500');
        }

        function commentPost() {
            alert('Comment dialog opening! Share your thoughts 💬');
        }

        // Creator interaction functions
        function followCreator(button) {
            if (button.textContent === 'Follow') {
                button.textContent = 'Following';
                button.classList.remove('bg-purple-100', 'text-purple-700', 'hover:bg-purple-200');
                button.classList.add('bg-green-100', 'text-green-700', 'hover:bg-green-200');
            } else {
                button.textContent = 'Follow';
                button.classList.remove('bg-green-100', 'text-green-700', 'hover:bg-green-200');
                button.classList.add('bg-purple-100', 'text-purple-700', 'hover:bg-purple-200');
            }
        }

        // Close profile menu when clicking outside
        document.addEventListener('click', function(event) {
            const profileMenu = document.getElementById('profileMenu');
            const profileButton = event.target.closest('button');
            
            if (!profileButton || !profileButton.onclick || profileButton.onclick.toString().indexOf('toggleProfileMenu') === -1) {
                profileMenu.classList.add('hidden');
            }
        });

        function closeProfileMenu() {
            const menu = document.getElementById('profileMenu');
            if (menu && !menu.classList.contains('hidden')) menu.classList.add('hidden');
        }

        // Search functionality
        document.querySelector('input[type="text"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                alert(`Searching for: "${this.value}"`);
            }
        });

        // Hashtag click functionality
        document.querySelectorAll('[class*="rounded-full"][class*="cursor-pointer"]').forEach(tag => {
            tag.addEventListener('click', function() {
                const tagName = this.textContent;
                alert(`Exploring ${tagName} photos! This will help boost your reach!`);
            });
        });

        // Tips click functionality
        document.querySelectorAll('[class*="from-blue-50"], [class*="from-green-50"], [class*="from-yellow-50"]').forEach(tip => {
            tip.addEventListener('click', function() {
                const title = this.querySelector('p').textContent;
                alert(`Opening: ${title}\nThis will help you grow your photography business!`);
            });
        });

        // Opportunities click functionality
        document.querySelectorAll('[class*="from-green-50"][class*="to-emerald-50"], [class*="from-blue-50"][class*="to-cyan-50"]').forEach(opportunity => {
            opportunity.addEventListener('click', function() {
                const job = this.querySelector('p').textContent;
                alert(`Interested in: ${job}?\nClick to apply and showcase your portfolio!`);
            });
        });
    </script>
    <script>
(function(){
    const btn = document.getElementById('darkToggle')
    if(!btn) return;
    const KEY = 'capturra-dark';
    function applyDark(d){
        document.documentElement.classList.toggle('dark', d);
        document.body.classList.toggle('dark', d);
        btn.setAttribute('aria-pressed', d);
        btn.textContent = d ? '☀️' : '🌙';
        if(d) btn.classList.add('active'); else btn.classList.remove('active');
    }
    const saved = localStorage.getItem(KEY);
    const prefers = saved !== null ? saved === '1' : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
    applyDark(prefers);
    btn.addEventListener('click', function(){
        const isDark = !document.documentElement.classList.contains('dark');
        applyDark(isDark);
        localStorage.setItem(KEY, isDark ? '1' : '0');
    });}
)
</script>

<script>
(function(){ 
    const btn = document.getElementById('darkToggle');
    if(!btn) return;

    const KEY = 'capturra-dark';

    function applyDark(d){
        document.documentElement.classList.toggle('dark', d);
        document.body.classList.toggle('dark', d);
        btn.setAttribute('aria-pressed', d);
        btn.textContent = d ? '☀️' : '🌙';
    }

    const saved = localStorage.getItem(KEY);
    const prefers = saved !== null ? saved === '1' : false;

    applyDark(prefers);

    btn.addEventListener('click', function(){
        const isDark = !document.documentElement.classList.contains('dark');
        applyDark(isDark);
        localStorage.setItem(KEY, isDark ? '1' : '0');
    });

})();
</script>
<script>
function openModal(src) {
    document.getElementById("imageModal").classList.remove("hidden");
    document.getElementById("imageModal").classList.add("flex");
    document.getElementById("modalImage").src = src;
}

function closeModal() {
    document.getElementById("imageModal").classList.add("hidden");
    document.getElementById("imageModal").classList.remove("flex");
}
function toggleNotifications(event) {
    event.stopPropagation(); // Dropdown click par menu band na ho jaye
    const menu = document.getElementById('notificationMenu');
    menu.classList.toggle('hidden');
    
    // Notification dot hide karne ke liye jab user menu dekh le
    // document.getElementById('notifDot').classList.add('hidden');
}

function clearNotifDot() {
    document.getElementById('notifDot').classList.add('hidden');
}

// Bahar click karne par menu close karne ke liye
document.addEventListener('click', function(e) {
    const menu = document.getElementById('notificationMenu');
    if (!menu.contains(e.target)) {
        menu.classList.add('hidden');
    }
});
// Search Bar logic
const searchInput = document.querySelector('input[type="text"]'); // Apna search input select karein
const resultsDiv = document.createElement('div');
resultsDiv.className = "absolute top-full left-0 w-full bg-[#16161f] border border-[#2a2a3e] rounded-xl mt-2 hidden z-50 overflow-hidden shadow-2xl";
searchInput.parentElement.appendChild(resultsDiv);

searchInput.addEventListener('input', async (e) => {
    const query = e.target.value.trim();
    if (query.length < 1) {
        resultsDiv.classList.add('hidden');
        return;
    }

    try {
        const response = await fetch(`search.php?q=${query}`);
        const data = await response.json();

        let html = '';

        // Users Section
        if (data.users.length > 0) {
            html += '<div class="p-2 text-[10px] text-slate-500 uppercase tracking-widest font-bold">Creators</div>';
            data.users.forEach(user => {
                html += `
                    <a href="profile.php?username=${user.username}" class="flex items-center gap-3 p-3 hover:bg-[#1e1e2e] transition-colors border-b border-[#1e1e2e]">
                        <div class="w-8 h-8 rounded-full bg-purple-500/20 flex items-center justify-center text-xs">👤</div>
                        <div>
                            <p class="text-sm text-white font-medium">${user.name}</p>
                            <p class="text-[10px] text-slate-500">@${user.username}</p>
                        </div>
                    </a>`;
            });
        }

        // Photos Section
        if (data.photos.length > 0) {
            html += '<div class="p-2 text-[10px] text-slate-500 uppercase tracking-widest font-bold mt-2">Photos</div>';
            data.photos.forEach(photo => {
                html += `
                    <a href="photo_detail.php?id=${photo.id}" class="flex items-center gap-3 p-3 hover:bg-[#1e1e2e] transition-colors">
                        <img src="${photo.image_path}" class="w-10 h-10 rounded-lg object-cover" onerror="this.src='/Capturra/assets/placeholder.jpg'">
                        <div>
                            <p class="text-sm text-white font-medium line-clamp-1">${photo.title}</p>
                            <p class="text-[10px] text-slate-500">by @${photo.username}</p>
                        </div>
                    </a>`;
            });
        }

        if (data.users.length === 0 && data.photos.length === 0) {
            html = '<div class="p-4 text-center text-xs text-slate-500">No results found for "' + query + '"</div>';
        }

        resultsDiv.innerHTML = html;
        resultsDiv.classList.remove('hidden');

    } catch (error) {
        console.error("Search error:", error);
    }
});

// Bahar click karne par hide karein
document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
        resultsDiv.classList.add('hidden');
    }
});
</script>

</body>
</html>
