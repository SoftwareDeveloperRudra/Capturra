<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/config/database.php";

/* 🔥 TIME FILTER */
$filter = $_GET['filter'] ?? 'today';

switch($filter){
    case 'today':
        $time_condition = "photos.upload_date >= CURDATE()";
        break;
    case 'week':
        $time_condition = "photos.upload_date >= NOW() - INTERVAL 7 DAY";
        break;
    case 'month':
        $time_condition = "photos.upload_date >= NOW() - INTERVAL 30 DAY";
        break;
    default:
        $time_condition = "1";
}

/* 🔥 SAFE CHECK FOR COMMENTS TABLE */
$checkComments = mysqli_query($conn, "SHOW TABLES LIKE 'comments'");
$comments_join = (mysqli_num_rows($checkComments) > 0)
    ? "LEFT JOIN comments ON comments.photo_id = photos.id"
    : "";

/* 🔥 TRENDING PHOTOS QUERY */
$sql = "
SELECT photos.*, users.username,
COUNT(DISTINCT likes.id) AS like_count,
" . ($comments_join ? "COUNT(DISTINCT comments.id)" : "0") . " AS comment_count

FROM photos
JOIN users ON photos.user_id = users.id
LEFT JOIN likes ON likes.photo_id = photos.id
$comments_join

WHERE $time_condition
GROUP BY photos.id
ORDER BY like_count DESC
LIMIT 12
";

$result = mysqli_query($conn, $sql);

if(!$result){
    die("Query Failed: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Trending</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
body{
    background: radial-gradient(circle at top,#1a0a2e,#0f0f13);
    color:white;
    font-family:'Inter',sans-serif;
}

/* CARD */
.card{
    background:#16161f;
    border:1px solid #2a2a3e;
    border-radius:16px;
    overflow:hidden;
    transition:.3s;
}
.card:hover{
    transform:translateY(-6px);
    border-color:#7c3aed;
    box-shadow:0 20px 50px rgba(124,58,237,0.3);
}

/* BADGE */
.badge{
    position:absolute;
    top:10px;
    left:10px;
    background:#7c3aed;
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
}

/* FILTER BUTTON */
.filter-btn{
    padding:8px 16px;
    border-radius:20px;
    border:1px solid #2a2a3e;
    cursor:pointer;
}
.filter-btn.active{
    background:linear-gradient(135deg,#7c3aed,#5b21b6);
}
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capturra - Trending</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        body { background: #0f0f13; color: #e2e0f0; }
        
        .photo-card {
            background: #16161f; border: 1px solid #2a2a3e; border-radius: 16px; overflow: hidden;
            transition: all 0.3s ease;
        }
        .photo-card:hover { border-color: #7c3aed; transform: translateY(-4px); box-shadow: 0 16px 40px rgba(124,58,237,0.2); }

        .rank-badge {
            display: inline-flex; align-items: center; justify-content: center;
            width: 28px; height: 28px; border-radius: 50%;
            font-size: 12px; font-weight: 700;
        }
        .rank-1 { background: #2a1a0a; color: #fbbf24; border: 1px solid #78350f; }
        .rank-2 { background: #1a1a2e; color: #94a3b8; border: 1px solid #334155; }
        .rank-3 { background: #1a1010; color: #cd7c3a; border: 1px solid #7c2d12; }

        .tab-btn { padding: 10px 28px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all .2s; border: 1px solid #2a2a3e; background: #16161f; color: #a0a0c0; }
        .tab-btn.active { background: linear-gradient(135deg,#7c3aed,#5b21b6); color: #fff; border-color: #7c3aed; }

        .fire-badge { background: linear-gradient(135deg,#7c1d1d,#dc2626); color:#fff; font-size:10px; padding:3px 10px; border-radius:20px; font-weight:700; }
        
        .filter-btn { padding:8px 16px; border-radius:20px; border:1px solid #2a2a3e; background:#16161f; color:#a0a0c0; cursor:pointer; transition:0.3s; }
        .filter-btn.active { background:linear-gradient(135deg,#7c3aed,#5b21b6); color:white; border-color:#7c3aed; }
        .filter-btn.active {
    background: linear-gradient(135deg, #7c3aed, #5b21b6) !important;
    color: white !important;
    border-color: #7c3aed !important;
}
    </style>
</head>
<body>

</div>
    <section style="background: radial-gradient(ellipse at 50% 0%, #1a0a2e 0%, #0f0f13 60%); padding:40px 24px 30px; text-align:center;">
        <h1 class="text-3xl font-bold text-white mb-2">🔥 Trending on Capturra</h1>
        <p class="text-gray-400 text-sm mb-8">Most liked photos and top creators</p>

        <div class="flex justify-center flex-wrap gap-3 mb-6">
            <button onclick="loadTrending('today', this)" class="filter-btn">🔥 Today</button>
            <button onclick="loadTrending('week', this)" class="filter-btn active">📅 This Week</button>
            <button onclick="loadTrending('month', this)" class="filter-btn">🗓️ This Month</button>
            <button onclick="loadTrending('all', this)" class="filter-btn">⏳ All Time</button>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-6 py-10">
        <div class="flex gap-3 mb-8">
            <button class="tab-btn active" onclick="showTab('photos', this)">📸 Trending Photos</button>
            <button class="tab-btn" onclick="showTab('photographers', this)">👤 Trending Photographers</button>
        </div>

        <div id="tab-photos">
            <div id="trending-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <p class="col-span-full text-center text-gray-500">Initializing...</p>
            </div>
        </div>

        <div id="tab-photographers" style="display:none;">
            <p class="text-center text-gray-400">Photographer leaderboard coming soon...</p>
        </div>
    </div>

    <footer class="border-t border-gray-800 py-8 text-center mt-20">
        <p class="text-gray-500 text-sm">© 2026 Capturra. Where Creators Shine ✨</p>
    </footer>

<script>
   function loadTrending(filter, btn) {
    // 1. UI Update: Handle the highlighting for whichever button was clicked
    if (btn) {
        // Find the parent container of the clicked button and remove 'active' from all its buttons
        const parent = btn.parentElement;
        parent.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        
        // Add 'active' to the clicked button
        btn.classList.add('active');
    }

    const container = document.getElementById('trending-container');
    container.innerHTML = `<div class="col-span-full text-center py-20"><div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-purple-500"></div></div>`;

    // 2. Data Fetching (Keep your working absolute path)
    fetch('/Capturra/api/trending.php?filter=' + filter)
        .then(res => res.json())
        .then(response => {
            let html = "";
            if (!response.status || !response.data || response.data.length === 0) {
                container.innerHTML = `<p class="col-span-full text-center text-gray-400 py-10">No trending photos found for this period.</p>`;
                return;
            }

            response.data.forEach((photo, index) => {
                const img = photo.image.split(/[\\/]/).pop();
                const rank = index + 1;
                html += `
                <div class="photo-card">
                    <div class="relative">
                        <img src="../uploads/${img}" class="w-full h-56 object-cover block">
                        <div class="absolute top-2 left-2">
                            <span class="rank-badge rank-${rank <= 3 ? rank : 'default'} bg-black/60 backdrop-blur-md text-white">#${rank}</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xl font-bold text-white">❤️ ${photo.like_count}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium">@${photo.username}</span>
                            <span class="text-xs text-gray-500">💬 ${photo.comment_count}</span>
                        </div>
                    </div>
                </div>`;
            });
            container.innerHTML = html;
        })
        .catch(err => {
            container.innerHTML = `<p class="col-span-full text-center text-red-400 py-10">Error: API unreachable or broken.</p>`;
        });
}
    
    function showTab(tab, btn) {
        document.getElementById('tab-photos').style.display = tab === 'photos' ? 'block' : 'none';
        document.getElementById('tab-photographers').style.display = tab === 'photographers' ? 'block' : 'none';
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    // Initial Load: Set to 'today' or 'week' based on your preferred default
    // Initial Load: Set the default view to 'today'
window.onload = () => {
    // Find the 'Today' button specifically to ensure it gets the 'active' class
    const todayBtn = document.querySelector('button[onclick*="today"]');
    
    // Trigger the load function for today's data
    loadTrending('today', todayBtn);
};
</script>
</body>
</html>
