<?php
// support.php - include in your main layout or use standalone
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capturra - Support</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        body { background: #0f0f13; color: #e2e0f0; margin: 0; padding: 0; }
 
        .sup-card {
            background: #16161f;
            border: 1px solid #2a2a3e;
            border-radius: 14px;
            padding: 22px;
            transition: all .3s;
            cursor: pointer;
        }
        .sup-card:hover { border-color: #7c3aed; box-shadow: 0 8px 24px rgba(124,58,237,0.15); transform: translateY(-3px); }
 
        .icon-box {
            width: 48px; height: 48px; border-radius: 12px;
            background: #1e1535; border: 1px solid #3a2060;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; margin-bottom: 12px;
        }
 
        .search-input {
            background: #16161f;
            border: 1px solid #3a3a5c;
            border-radius: 10px;
            color: #e2e0f0;
            padding: 12px 16px 12px 44px;
            outline: none;
            width: 100%;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: border-color .3s;
            box-sizing: border-box;
        }
        .search-input:focus { border-color: #7c3aed; }
        .search-input::placeholder { color: #6b6b8a; }
 
        .faq-item {
            background: #16161f;
            border: 1px solid #2a2a3e;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 10px;
            transition: border-color .3s;
        }
        .faq-item:hover { border-color: #3a2060; }
 
        .faq-q {
            width: 100%; text-align: left;
            padding: 16px 18px; background: transparent; border: none;
            color: #e2e0f0; font-size: 13px; font-weight: 500;
            cursor: pointer; display: flex; justify-content: space-between;
            align-items: center; font-family: 'Inter', sans-serif;
        }
        .faq-a {
            display: none; padding: 0 18px 16px;
            font-size: 12px; color: #a0a0c0;
            line-height: 1.7; border-top: 1px solid #1e1e2e;
        }
        .faq-a.open { display: block; }
        .faq-arrow { transition: transform .3s; color: #6b6b8a; font-size: 12px; }
        .faq-arrow.rotated { transform: rotate(180deg); color: #a855f7; }
 
        .s-input {
            width: 100%; background: #0f0f13;
            border: 1px solid #3a3a5c; border-radius: 10px;
            color: #e2e0f0; padding: 10px 14px;
            font-size: 13px; font-family: 'Inter', sans-serif;
            outline: none; transition: border-color .3s;
            margin-bottom: 12px; box-sizing: border-box;
        }
        .s-input:focus { border-color: #7c3aed; }
        .s-input::placeholder { color: #4a4a6a; }
        .s-label { display:block; font-size:12px; color:#a0a0c0; margin-bottom:5px; }
 
        .send-btn {
            background: linear-gradient(135deg, #7c3aed, #5b21b6);
            color: #fff; border: none; padding: 11px 28px;
            border-radius: 10px; font-size: 13px; font-weight: 600;
            cursor: pointer; font-family: 'Inter', sans-serif; transition: opacity .3s;
        }
        .send-btn:hover { opacity: 0.88; }
 
        .status-ok { font-size:11px; padding:3px 10px; border-radius:20px; background:#1a2e1a; color:#4ade80; border:1px solid #1a4a1a; }
        .tag-badge { display:inline-block; background:#1e1535; color:#a855f7; border:1px solid #3a2060; font-size:11px; padding:3px 10px; border-radius:20px; }
    </style>
</head>
<body>
 
<div style="max-width:1100px; margin:0 auto; padding:40px 24px 60px;">
 
    <!-- Hero -->
    <div style="text-align:center; margin-bottom:40px;">
        <div style="display:inline-block; margin-bottom:12px; padding:4px 14px; border-radius:20px; font-size:11px; font-weight:600; letter-spacing:1.5px; text-transform:uppercase; background:#1e1535; color:#a855f7; border:1px solid #3a2060;">
            Help Center
        </div>
        <h1 style="font-size:32px; font-weight:700; color:#fff; margin:0 0 8px;">How can we help you?</h1>
        <p style="color:#6b6b8a; font-size:14px; margin:0 0 22px;">Search our knowledge base or get in touch with our team.</p>
        <div style="position:relative; max-width:480px; margin:0 auto;">
            <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#6b6b8a;"></i>
            <input type="text" class="search-input" placeholder="Search for help articles...">
        </div>
    </div>
 
    <!-- Quick Help Cards -->
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:40px;">
        <div class="sup-card">
            <div class="icon-box">🚀</div>
            <h3 style="font-size:13px; font-weight:600; color:#fff; margin:0 0 6px;">Getting Started</h3>
            <p style="font-size:12px; color:#6b6b8a; line-height:1.6; margin:0;">Set up your profile, upload your first photo, and get discovered.</p>
        </div>
        <div class="sup-card">
            <div class="icon-box">💳</div>
            <h3 style="font-size:13px; font-weight:600; color:#fff; margin:0 0 6px;">Billing & Plans</h3>
            <p style="font-size:12px; color:#6b6b8a; line-height:1.6; margin:0;">Manage subscriptions, update payment methods, view invoices.</p>
        </div>
        <div class="sup-card">
            <div class="icon-box">🔒</div>
            <h3 style="font-size:13px; font-weight:600; color:#fff; margin:0 0 6px;">Account & Security</h3>
            <p style="font-size:12px; color:#6b6b8a; line-height:1.6; margin:0;">Reset password, enable 2FA, manage your privacy settings.</p>
        </div>
        <div class="sup-card">
            <div class="icon-box">📅</div>
            <h3 style="font-size:13px; font-weight:600; color:#fff; margin:0 0 6px;">Bookings</h3>
            <p style="font-size:12px; color:#6b6b8a; line-height:1.6; margin:0;">Handle client requests, manage your calendar, process payments.</p>
        </div>
    </div>
 
    <!-- Main Content -->
    <div style="display:grid; grid-template-columns:1fr 300px; gap:24px;">
 
        <!-- Left -->
        <div>
            <!-- FAQ -->
            <h2 style="font-size:18px; font-weight:600; color:#fff; margin:0 0 18px;">Frequently Asked Questions</h2>
 
            <div class="faq-item">
                <button class="faq-q" onclick="toggleFaq(this)">How do I upload photos to my portfolio?<i class="fa-solid fa-chevron-down faq-arrow"></i></button>
                <div class="faq-a">Go to your Profile → click "Upload Photo" → select images (JPG, PNG, up to 20MB). Add titles and tags. Photos appear on your public portfolio immediately.</div>
            </div>
            <div class="faq-item">
                <button class="faq-q" onclick="toggleFaq(this)">How do I get discovered by clients?<i class="fa-solid fa-chevron-down faq-arrow"></i></button>
                <div class="faq-a">Complete your profile with bio, location, and specialization. Upload high-quality work regularly. Pro plan members get boosted visibility in search results.</div>
            </div>
            <div class="faq-item">
                <button class="faq-q" onclick="toggleFaq(this)">What is the difference between Photographer and Client roles?<i class="fa-solid fa-chevron-down faq-arrow"></i></button>
                <div class="faq-a">Photographers upload portfolios, receive bookings, and earn through the platform. Clients browse photographers and send booking requests. You can change your role anytime in Settings → Profile.</div>
            </div>
            <div class="faq-item">
                <button class="faq-q" onclick="toggleFaq(this)">How do bookings and payments work?<i class="fa-solid fa-chevron-down faq-arrow"></i></button>
                <div class="faq-a">Clients send a booking request with date, location, and budget. Photographers accept or decline. Payment is held securely until the session is completed. Capturra charges a 10% platform fee.</div>
            </div>
            <div class="faq-item">
                <button class="faq-q" onclick="toggleFaq(this)">Can I cancel or refund a booking?<i class="fa-solid fa-chevron-down faq-arrow"></i></button>
                <div class="faq-a">Cancellations 48+ hours before session are fully refunded. Within 48 hours — 50% fee applies. No-shows are non-refundable. Contact support for disputes.</div>
            </div>
            <div class="faq-item">
                <button class="faq-q" onclick="toggleFaq(this)">How do I upgrade or cancel my plan?<i class="fa-solid fa-chevron-down faq-arrow"></i></button>
                <div class="faq-a">Go to Settings → Billing. Upgrade to Pro anytime. To cancel, click "Cancel Plan" — Pro features stay active until end of billing cycle.</div>
            </div>
            <div class="faq-item">
                <button class="faq-q" onclick="toggleFaq(this)">How do I report a user or inappropriate content?<i class="fa-solid fa-chevron-down faq-arrow"></i></button>
                <div class="faq-a">Click the three-dot menu on any profile or photo and select "Report". Our team reviews all reports within 24 hours.</div>
            </div>
 
            <!-- Contact Form -->
            <h2 style="font-size:18px; font-weight:600; color:#fff; margin:32px 0 16px;">Send Us a Message</h2>
            <div style="background:#16161f; border:1px solid #2a2a3e; border-radius:14px; padding:24px;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div><label class="s-label">Name</label><input type="text" class="s-input" placeholder="Your name"></div>
                    <div><label class="s-label">Email</label><input type="email" class="s-input" placeholder="your@email.com"></div>
                </div>
                <label class="s-label">Subject</label>
                <select class="s-input" style="-webkit-appearance:none;">
                    <option value="" disabled selected>Select a topic</option>
                    <option>Account & Login</option>
                    <option>Billing & Payments</option>
                    <option>Booking Issues</option>
                    <option>Technical Problem</option>
                    <option>Report a User</option>
                    <option>Other</option>
                </select>
                <label class="s-label">Message</label>
                <textarea class="s-input" rows="4" style="resize:none; margin-bottom:0;" placeholder="Describe your issue in detail..."></textarea>
                <div style="margin-top:14px;">
                    <button class="send-btn">Send Message</button>
                    <p style="font-size:11px; color:#6b6b8a; margin:8px 0 0;">We typically respond within 24 hours on business days.</p>
                </div>
            </div>
        </div>
 
        <!-- Sidebar -->
        <div style="display:flex; flex-direction:column; gap:14px;">
 
            <!-- Contact Options -->
            <div style="background:#16161f; border:1px solid #2a2a3e; border-radius:14px; padding:20px;">
                <h3 style="font-size:14px; font-weight:600; color:#fff; margin:0 0 16px;">Other Ways to Reach Us</h3>
                <div style="display:flex; flex-direction:column; gap:14px;">
                    <div style="display:flex; gap:12px; align-items:flex-start;">
                        <div style="width:40px;height:40px;border-radius:10px;background:#1e1535;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-envelope" style="color:#a855f7;font-size:14px;"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:500;color:#fff;">Email Support</div>
                            <div style="font-size:11px;color:#6b6b8a;margin-top:2px;">support@capturra.com</div>
                            <div style="font-size:11px;color:#6b6b8a;">Mon–Fri, 9am–6pm IST</div>
                        </div>
                    </div>
                    <div style="display:flex; gap:12px; align-items:flex-start;">
                        <div style="width:40px;height:40px;border-radius:10px;background:#1e1535;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-comments" style="color:#a855f7;font-size:14px;"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:500;color:#fff;">Live Chat</div>
                            <div style="font-size:11px;color:#6b6b8a;margin-top:2px;">Available on Pro plan</div>
                            <div style="font-size:11px;color:#4ade80;">● Online now</div>
                        </div>
                    </div>
                    <div style="display:flex; gap:12px; align-items:flex-start;">
                        <div style="width:40px;height:40px;border-radius:10px;background:#1e1535;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-brands fa-instagram" style="color:#a855f7;font-size:14px;"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:500;color:#fff;">Social Media</div>
                            <div style="font-size:11px;color:#6b6b8a;margin-top:2px;">@capturra.official</div>
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- System Status -->
            <div style="background:#16161f; border:1px solid #2a2a3e; border-radius:14px; padding:20px;">
                <h3 style="font-size:14px; font-weight:600; color:#fff; margin:0 0 14px;">System Status</h3>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                        <span style="color:#a0a0c0;">Website</span><span class="status-ok">✓ Operational</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                        <span style="color:#a0a0c0;">Uploads</span><span class="status-ok">✓ Operational</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                        <span style="color:#a0a0c0;">Payments</span><span class="status-ok">✓ Operational</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                        <span style="color:#a0a0c0;">Search</span><span class="status-ok">✓ Operational</span>
                    </div>
                </div>
            </div>
 
            <!-- Recent Tickets -->
            <div style="background:#16161f; border:1px solid #2a2a3e; border-radius:14px; padding:20px;">
                <h3 style="font-size:14px; font-weight:600; color:#fff; margin:0 0 14px;">Your Recent Tickets</h3>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div style="padding:12px;border-radius:10px;background:#0f0f13;border:1px solid #2a2a3e;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                            <span style="font-size:13px;font-weight:500;color:#fff;">Billing Issue</span>
                            <span class="tag-badge" style="color:#4ade80;background:#1a2e1a;border-color:#1a4a1a;">Resolved</span>
                        </div>
                        <div style="font-size:11px;color:#6b6b8a;">Mar 20, 2025 · #TKT-1023</div>
                    </div>
                    <div style="padding:12px;border-radius:10px;background:#0f0f13;border:1px solid #2a2a3e;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                            <span style="font-size:13px;font-weight:500;color:#fff;">Upload not working</span>
                            <span class="tag-badge">Open</span>
                        </div>
                        <div style="font-size:11px;color:#6b6b8a;">Apr 14, 2025 · #TKT-1047</div>
                    </div>
                </div>
                <button style="margin-top:10px;font-size:12px;color:#a855f7;background:none;border:none;cursor:pointer;font-family:'Inter',sans-serif;">View all tickets →</button>
            </div>
 
        </div>
    </div>
</div>
 
<script>
function toggleFaq(btn) {
    const answer = btn.nextElementSibling;
    const arrow  = btn.querySelector('.faq-arrow');
    const isOpen = answer.classList.contains('open');
    document.querySelectorAll('.faq-a').forEach(a => a.classList.remove('open'));
    document.querySelectorAll('.faq-arrow').forEach(a => a.classList.remove('rotated'));
    if (!isOpen) { answer.classList.add('open'); arrow.classList.add('rotated'); }
}
</script>
</body>
</html>