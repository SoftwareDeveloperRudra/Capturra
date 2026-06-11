<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/capturra/includes/auth.php');
requireRole("client");

$username = $_SESSION['username'];
$name     = $_SESSION['name'];
?>
<?php
include("../config/database.php");

$sql = "SELECT photos.*, users.username,
        (SELECT COUNT(*) FROM likes WHERE likes.photo_id = photos.id) AS like_count
        FROM photos
        JOIN users ON photos.user_id = users.id
        ORDER BY photos.upload_date DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capturra - Photography Community</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
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

        /* Dark mode overrides */
        .dark body {
            background-color: #0a0a0a;
            color: #f0f0f0;
        }
        .dark nav {
            background-color: #111111 !important;
            border-color: #222222 !important;
        }
        .dark nav a,
        .dark nav .text-gray-900,
        .dark nav .text-gray-700 {
            color: #f0f0f0 !important;
        }
        .dark .bg-white {
            background-color: #1a1a1a !important;
            color: #f0f0f0 !important;
            border-color: #2a2a2a !important;
        }
        .dark .text-gray-900 { color: #f0f0f0 !important; }
        .dark .text-gray-700, .dark .text-gray-500 { color: #bbbbbb !important; }
        .dark .border-gray-200 { border-color: #2a2a2a !important; }
        .dark .upload-zone { border-color: #444444 !important; background-color: rgba(255,255,255,0.03) !important; }
        #darkToggle { background: transparent; border: 1px solid transparent; padding: 6px 8px; border-radius: 8px; color: inherit; }
        #darkToggle.active { background: rgba(255,255,255,0.04); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center cursor-pointer" onclick="goHome()">
                    <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <span class="text-white font-bold text-lg">C</span>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Capturra</span>
                </div>
                
                <!-- Search Bar -->
                <div class="flex-1 max-w-lg mx-8">
                    <div class="relative">
                        <input type="text" placeholder="Search creators, photos, categories..." 
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
                    <a href="client_home.php" class="text-gray-700 hover:text-purple-600 font-medium transition-colors">Home</a>
                    <a href="explore.php" class="text-gray-700 hover:text-purple-600 font-medium transition-colors">Explore</a>
                    <a href="trending.php" class="text-gray-700 hover:text-purple-600 font-medium transition-colors">Trending</a>
                    <a href="creator.php" class="text-gray-700 hover:text-purple-600 font-medium transition-colors">Creators</a>

                    <!-- Messages -->
                    <div class="relative inline-block">
                        <div class="relative cursor-pointer group" onclick="toggleMessages(event)">
                            <span class="text-2xl group-hover:scale-110 transition-transform inline-block">💬</span>
                            <div class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-blue-500 rounded-full border-2 border-white notification-dot"></div>
                        </div>

                        <div id="msgDropdown" class="hidden absolute right-0 mt-4 w-80 bg-white dark:bg-[#1a1a1a] rounded-[2rem] shadow-2xl border border-slate-100 dark:border-[#2a2a2a] z-[999] overflow-hidden transform origin-top-right transition-all">
                            <div class="p-4 border-b border-slate-100 dark:border-[#2a2a2a] flex justify-between items-center bg-slate-50/50 dark:bg-[#111]/30">
                                <h3 class="text-[11px] font-black dark:text-white uppercase tracking-widest text-slate-500">Recent Inquiries</h3>
                                <span class="bg-purple-600 text-white text-[9px] px-2 py-0.5 rounded-full font-black animate-pulse">NEW</span>
                            </div>
                            <div class="max-h-[380px] overflow-y-auto">
                                <div class="p-4 hover:bg-slate-50 dark:hover:bg-[#222] transition-all border-b border-slate-50 dark:border-[#2a2a2a]">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-11 h-11 rounded-2xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-sm">JD</div>
                                            <div>
                                                <h4 class="text-xs font-black dark:text-white text-slate-800">John Doe</h4>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">Wedding Inquiry</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="https://wa.me/91XXXXXXXXXX" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#25D366] text-white shadow-lg shadow-green-500/20 hover:scale-110 active:scale-95 transition-all">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            </a>
                                            <a href="mailto:john@gmail.com" class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#EA4335] text-white shadow-lg shadow-red-500/20 hover:scale-110 active:scale-95 transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="messages.php" class="block p-4 text-center text-[10px] font-black text-slate-400 bg-slate-50 dark:bg-[#111]/40 hover:text-purple-600 transition-colors uppercase tracking-[0.2em]">
                                Open Message Center
                            </a>
                        </div>
                    </div>

                    <!-- Dark mode toggle -->
                    <button id="darkToggle" title="Toggle dark mode" class="flex items-center justify-center w-9 h-9 rounded-md text-gray-700 hover:bg-gray-100 transition-colors" aria-pressed="false">🌙</button>

                    <!-- Notifications -->
                    <div class="relative" id="notifWrapper">
                        <div class="relative cursor-pointer" onclick="toggleNotifications()">
                            <svg class="h-6 w-6 text-gray-700 hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full notification-dot"></div>
                        </div>

                        <!-- Notification Dropdown -->
                        <div id="notifDropdown" class="hidden absolute right-0 mt-4 w-80 bg-white dark:bg-[#1a1a1a] rounded-2xl shadow-2xl border border-gray-100 dark:border-[#2a2a2a] z-[999] overflow-hidden origin-top-right">
                            <div class="p-4 border-b border-gray-100 dark:border-[#2a2a2a] flex justify-between items-center bg-gray-50/50 dark:bg-[#111]/30">
                                <h3 class="text-[11px] font-black uppercase tracking-widest text-gray-500 dark:text-gray-400">Notifications</h3>
                                <span class="bg-red-500 text-white text-[9px] px-2 py-0.5 rounded-full font-black animate-pulse">NEW</span>
                            </div>
                            <div class="max-h-[380px] overflow-y-auto divide-y divide-gray-50 dark:divide-[#2a2a2a]">
                                <div class="p-4 hover:bg-gray-50 dark:hover:bg-[#222] transition-all flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-500 flex-shrink-0">❤️</div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Sarah Johnson liked your photo</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">2 minutes ago</p>
                                    </div>
                                </div>
                                <div class="p-4 hover:bg-gray-50 dark:hover:bg-[#222] transition-all flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-500 flex-shrink-0">💬</div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Emma Wilson commented on your photo</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">15 minutes ago</p>
                                    </div>
                                </div>
                                <div class="p-4 hover:bg-gray-50 dark:hover:bg-[#222] transition-all flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-500 flex-shrink-0">👤</div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">David Park started following you</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">1 hour ago</p>
                                    </div>
                                </div>
                            </div>
                            <a href="notification.php" class="block p-4 text-center text-[10px] font-black text-gray-400 bg-gray-50 dark:bg-[#111]/40 hover:text-purple-600 transition-colors uppercase tracking-[0.2em]">
                                View All Notifications
                            </a>
                        </div>
                    </div>
                    
                    <!-- Profile Menu -->
                    <div class="relative">
                        <button onclick="toggleProfileMenu()" class="flex items-center space-x-2 focus:outline-none">
                            <div class="w-8 h-8 rounded-full border-2 border-purple-500 flex items-center justify-center bg-gray-100">
                                <span class="text-gray-400 text-sm">👤</span>
                            </div>
                        </button>
                        <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2">
                            <a href="profile.php" onclick="closeProfileMenu()" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Profile</a>
                            <a href="settings.php" onclick="closeProfileMenu()" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Settings</a>
                            <hr class="my-1">
                            <button onclick="logout()" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-50">Logout</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- User Profile Snapshot -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-full mx-auto mb-4 border-4 border-purple-500 flex items-center justify-center bg-gray-100">
                            <span class="text-2xl text-gray-400">👤</span>
                        </div>
                        <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($name); ?></h3>
                        <p class="text-sm text-gray-500 mb-4">@<?php echo htmlspecialchars($username); ?></p>
                        <div class="flex justify-center space-x-4 text-sm">
                            <div class="text-center">
                                <div class="font-semibold text-gray-900">0</div>
                                <div class="text-gray-500">Followers</div>
                            </div>
                            <div class="text-center">
                                <div class="font-semibold text-gray-900">0</div>
                                <div class="text-gray-500">Likes</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Quick Links</h3>
                    <div class="space-y-2">
                        <a href="blogs.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="h-5 w-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <span class="text-gray-700">Blogs</span>
                        </a>
                        <a href="my_bookings.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="h-5 w-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <span class="text-gray-700">My Bookings</span>
                        </a>
                        <a href="setting.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="h-5 w-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-gray-700">Settings</span>
                        </a>
                        <a href="support.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="h-5 w-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"></path></svg>
                            <span class="text-gray-700">Support</span>
                        </a>
                    </div>
                </div>
                <div class="mt-10 px-4">
    <div class="relative overflow-hidden bg-gradient-to-br from-[#6366f1] via-[#a855f7] to-[#ec4899] rounded-2xl p-5 shadow-lg shadow-purple-500/20 group cursor-pointer transition-transform hover:scale-[1.02]">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/10 rounded-full blur-xl group-hover:bg-white/20 transition-colors"></div>
        
       
    </div>
    
</div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Hero / Welcome Section -->
                <div class="gradient-bg rounded-xl p-8 text-white">
                    <h1 class="text-3xl font-bold mb-2">Welcome back, <?php echo htmlspecialchars($name); ?> 👋</h1>
                    <p class="text-purple-100 mb-6">Explore photographers and book your next event effortlessly.</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="portfolio.php" class="bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-800 transition-colors text-center">View Portfolio</a>
                        <a href="booking_calendar.php" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition-colors text-center">Hire Photographer</a>
                    </div>
                </div>

                <!-- Main Feed -->
                <div class="space-y-8">
                    <!-- Trending Photos -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">🔥 Trending Photos</h2>
                        <div class="photo-grid">
<?php while($row = mysqli_fetch_assoc($result)) { ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden card-hover">
        <a href="photo.php?id=<?php echo $row['id']; ?>">
            <img src="/Capturra/uploads/<?php echo htmlspecialchars($row['photo_path'] ?? $row['image']); ?>" 
                 class="w-full rounded-t-xl cursor-pointer"
                 style="max-height:300px; object-fit:contain;"
                 onclick="openModal(this.src)">
        </a>
        <div class="p-4">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">👤</div>
                <div>
                    <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($row['username']); ?></p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button onclick="likePost(<?php echo $row['id']; ?>, this)" class="flex items-center space-x-1 text-gray-500">
                    ❤️ <span class="text-sm"><?php echo $row['like_count']; ?></span>
                </button>
                <button class="flex items-center space-x-1 text-gray-500 hover:text-blue-500">
                    💬 <span class="text-sm"><?php echo $row['comment_count'] ?? 0; ?></span>
                </button>
            </div>
        </div>
    </div>
<?php } ?>
                        </div>
                    </section>

                    <!-- Followed Creators' Updates -->
                </div>
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

                <!-- Suggested Creators -->
               <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-900">💡 Suggested for You</h3>
        <a href="trending.php" class="text-xs font-medium text-purple-600 hover:text-purple-800 transition-colors flex items-center gap-1">
            View More  <span>→</span>
        </a>
    </div>

    <div class="space-y-4">
        <div class="flex items-center space-x-3">
            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=32&h=32&fit=crop&crop=face" alt="Suggested creator" class="w-8 h-8 rounded-full">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">Emma Wilson</p>
                <p class="text-xs text-gray-500">Portrait specialist</p>
            </div>
            <button onclick="followCreator(this)" class="text-xs bg-purple-100 text-purple-700 px-3 py-1 rounded-full hover:bg-purple-200 transition-colors">Follow</button>
        </div>

        <div class="flex items-center space-x-3">
            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=32&h=32&fit=crop&crop=face" alt="Suggested creator" class="w-8 h-8 rounded-full">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">David Park</p>
                <p class="text-xs text-gray-500">Nature photographer</p>
            </div>
            <button onclick="followCreator(this)" class="text-xs bg-purple-100 text-purple-700 px-3 py-1 rounded-full hover:bg-purple-200 transition-colors">Follow</button>
        </div>
    </div>
</div>

                <!-- Trending Hashtags -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">🔥 Trending Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 cursor-pointer hover:bg-purple-200 transition-colors">#Nature</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 cursor-pointer hover:bg-blue-200 transition-colors">#Wedding</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 cursor-pointer hover:bg-green-200 transition-colors">#Street</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 cursor-pointer hover:bg-yellow-200 transition-colors">#Portrait</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800 cursor-pointer hover:bg-pink-200 transition-colors">#Landscape</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 cursor-pointer hover:bg-indigo-200 transition-colors">#Architecture</span>
                    </div>
                </div>

                <!-- Follower Activity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">👥 Follower Activity</h3>
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
            </div>
        </div>
    </div>

    <script>
        function goHome() {
            window.location.href = 'client_home.php';
        }

        // ── Messages dropdown ──
        function toggleMessages(event) {
            event.stopPropagation();
            const msgDD = document.getElementById('msgDropdown');
            const notifDD = document.getElementById('notifDropdown');
            if (notifDD && !notifDD.classList.contains('hidden')) notifDD.classList.add('hidden');
            msgDD.classList.toggle('hidden');
        }

        // ── Notifications dropdown ──
        function toggleNotifications() {
            const notifDD = document.getElementById('notifDropdown');
            const msgDD = document.getElementById('msgDropdown');
            if (msgDD && !msgDD.classList.contains('hidden')) msgDD.classList.add('hidden');
            notifDD.classList.toggle('hidden');
        }

        // Close both dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const notifWrapper = document.getElementById('notifWrapper');
            const notifDD = document.getElementById('notifDropdown');
            if (notifDD && !notifDD.classList.contains('hidden') && notifWrapper && !notifWrapper.contains(event.target)) {
                notifDD.classList.add('hidden');
            }

            const msgDD = document.getElementById('msgDropdown');
            if (msgDD && !msgDD.classList.contains('hidden') && !event.target.closest('.relative.inline-block')) {
                msgDD.classList.add('hidden');
            }
        });

        function toggleProfileMenu() {
            const menu = document.getElementById('profileMenu');
            menu.classList.toggle('hidden');
        }

        function closeProfileMenu() {
            const menu = document.getElementById('profileMenu');
            if (menu && !menu.classList.contains('hidden')) menu.classList.add('hidden');
        }

        function viewPortfolio() { window.location.href = 'portfolio.php'; }
        function hirePhotographer() { window.location.href = 'booking_calendar.php'; }

        function likePost(button) {
            const likeCount = button.querySelector('span:last-child');
            const currentCount = parseInt(likeCount.textContent);
            likeCount.textContent = currentCount + 1;
            button.classList.add('text-red-500');
            button.classList.remove('text-gray-500');
        }

        function commentPost() { alert('Comment dialog would open here! 💬'); }
        function sharePost() { alert('Share options would appear here! ↗️'); }

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
                if (profileMenu) profileMenu.classList.add('hidden');
            }
        });

        document.querySelector('input[type="text"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') alert(`Searching for: "${this.value}"`);
        });
    </script>

    <script>
    function logout() {
        fetch("http://localhost:8888/Capturra/api/auth/logout.php")
            .then(res => res.json())
            .then(data => {
                if (data.status) {
                    window.location.href = "/Capturra/public/login.html";
                } else {
                    alert("Logout failed");
                }
            })
            .catch(err => { console.error(err); alert("Server error"); });
    }
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
            if(d) btn.classList.add('active'); else btn.classList.remove('active');
        }
        const saved = localStorage.getItem(KEY);
        const prefers = saved !== null ? saved === '1' : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
        applyDark(prefers);
        btn.addEventListener('click', function(){
            const isDark = !document.documentElement.classList.contains('dark');
            applyDark(isDark);
            localStorage.setItem(KEY, isDark ? '1' : '0');
        });
    })();

    function likePost(photoId, btn) {
        fetch("like.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "photo_id=" + photoId
        })
        .then(res => res.json())
        .then(data => {
            let countSpan = btn.querySelector("span");
            let count = parseInt(countSpan.textContent);
            if (data.status === "liked") {
                countSpan.textContent = count + 1;
                btn.classList.add("text-red-500");
            } else {
                countSpan.textContent = count - 1;
                btn.classList.remove("text-red-500");
            }
        });
    }
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