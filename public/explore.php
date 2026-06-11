<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/auth.php";
include("../config/database.php");

$username = $_SESSION['username'] ?? '';
$name     = $_SESSION['name'] ?? '';
$user_id  = $_SESSION['user_id'] ?? 0;
$role     = $_SESSION['role'] ?? 'client';

// Search / filter
$search   = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$category = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : '';

$where = "WHERE 1=1";
if ($search)   $where .= " AND (users.username LIKE '%$search%' OR CONCAT(users.first_name, ' ', users.last_name) LIKE '%$search%' OR photos.description LIKE '%$search%')";

$sql = "SELECT photos.*, users.username, users.id AS photographer_id, CONCAT(users.first_name, ' ', users.last_name) AS photographer_name,
        (SELECT COUNT(*) FROM likes WHERE likes.photo_id = photos.id) AS like_count,
        (SELECT COUNT(*) FROM comments WHERE comments.photo_id = photos.id) AS comment_count
        FROM photos
        JOIN users ON photos.user_id = users.id
        $where
        ORDER BY photos.upload_date DESC";
$result = mysqli_query($conn, $sql);

// Categories from DB
$cat_result = $cat_result ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capturra - Explore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        body { background: #0f0f13; color: #e2e0f0; }
        .gradient-bg { background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); }
        nav { background: #0d0d11; border-bottom: 1px solid #2a2a3e; }

        .photo-card {
            background: #16161f; border: 1px solid #2a2a3e; border-radius: 14px;
            overflow: hidden; transition: all 0.3s ease; break-inside: avoid; margin-bottom: 1.5rem;
        }
        .photo-card:hover { border-color: #7c3aed; transform: translateY(-3px); box-shadow: 0 12px 30px rgba(124,58,237,0.15); }

        .masonry { columns: 3; column-gap: 1.5rem; }
        @media(max-width:768px) { .masonry { columns: 2; } }
        @media(max-width:480px) { .masonry { columns: 1; } }

        .photo-card {
    position: relative;
}

.heart-anim {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    font-size: 50px;
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s ease;
}

.heart-anim.show {
    transform: translate(-50%, -50%) scale(1.2);
    opacity: 1;
}

        .cap-card { background: #16161f; border: 1px solid #2a2a3e; border-radius: 14px; }
        .search-input { background: #16161f; border: 1px solid #3a3a5c; border-radius: 10px; color: #e2e0f0; padding: 10px 16px 10px 42px; outline: none; width: 100%; font-size: 14px; transition: border-color .3s; }
        .search-input:focus { border-color: #7c3aed; }
        .search-input::placeholder { color: #6b6b8a; }

        .cat-btn { background: #16161f; border: 1px solid #2a2a3e; color: #a0a0c0; padding: 6px 16px; border-radius: 20px; font-size: 12px; cursor: pointer; transition: all .2s; text-decoration: none; display: inline-block; }
        .cat-btn:hover, .cat-btn.active { background: #1e1535; border-color: #7c3aed; color: #a855f7; }

        .follow-btn { background: #1e1535; color: #a855f7; border: 1px solid #3a2060; padding: 5px 14px; border-radius: 20px; font-size: 12px; cursor: pointer; transition: all .2s; }
        .follow-btn:hover { background: #2d1f50; }

        #profileMenu { background: #16161f; border-color: #2a2a3e; }
        .notification-dot { animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }

        footer { background: #080810; border-color: #1e1a2e; }
    </style>
</head>
<body class="min-h-screen">



<!-- Hero Search -->
<section style="background: radial-gradient(ellipse at 50% 0%, #1a0a2e 0%, #0f0f13 60%); padding: 40px 24px 30px; text-align:center;">
    <div style="display:inline-block; margin-bottom:12px; padding:4px 14px; border-radius:20px; font-size:11px; font-weight:600; letter-spacing:1.5px; text-transform:uppercase; background:#1e1535; color:#a855f7; border:1px solid #3a2060;">Explore</div>
    <h1 style="font-size:32px; font-weight:700; color:#fff; margin:0 0 8px;">Discover Amazing Photography</h1>
    <p style="color:#6b6b8a; font-size:14px; margin:0 0 24px;">Browse thousands of stunning photos from talented creators</p>
    <form method="GET" action="explore.php" style="max-width:520px; margin:0 auto; position:relative;">
        <i style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#6b6b8a;">🔍</i>
        <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" class="search-input" placeholder="Search photographers or styles...">
    </form>
</section>

<!-- Category Pills -->
<div style="max-width:1280px; margin:0 auto; padding:20px 24px; display:flex; flex-wrap:wrap; gap:10px;">
    <a href="explore.php" class="cat-btn <?php echo !$category?'active':''; ?>">All</a>
    <?php if($cat_result && mysqli_num_rows($cat_result) > 0):
        while($cat = mysqli_fetch_assoc($cat_result)): ?>
    <a href="explore.php?cat=<?php echo urlencode($cat['category']); ?>" class="cat-btn <?php echo $category===$cat['category']?'active':''; ?>">
        <?php echo htmlspecialchars($cat['category']); ?>
    </a>
    <?php endwhile; else: ?>
    <a href="explore.php?cat=Wedding"    class="cat-btn">Wedding</a>
    <a href="explore.php?cat=Portrait"   class="cat-btn">Portrait</a>
    <a href="explore.php?cat=Nature"     class="cat-btn">Nature</a>
    <a href="explore.php?cat=Street"     class="cat-btn">Street</a>
    <a href="explore.php?cat=Fashion"    class="cat-btn">Fashion</a>
    <a href="explore.php?cat=Landscape"  class="cat-btn">Landscape</a>
    <?php endif; ?>
</div>

<!-- Main -->
<div style="max-width:1280px; margin:0 auto; padding:0 24px 60px; display:grid; grid-template-columns:1fr 280px; gap:24px;">

    <!-- Masonry Grid -->
    <div>
        <?php if($result && mysqli_num_rows($result) > 0): ?>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
            <p style="color:#6b6b8a; font-size:13px;"><?php echo mysqli_num_rows($result); ?> photos found</p>
        </div>
        <div class="masonry">
        <?php while($row = mysqli_fetch_assoc($result)):
            $filename = basename(str_replace('\\','/',$row['image']));
            $img_src  = "/Capturra/uploads/" . $filename;
        ?>
        <div class="photo-card">
            <a href="photo.php?id=<?php echo $row['id']; ?>">
                <img src="<?php echo htmlspecialchars($img_src); ?>"
     ondblclick="handleDoubleClick(event, this, <?php echo $row['id']; ?>)"
     class="w-full cursor-pointer">
     <div class="heart-anim">❤️</div>
                <div style="display:none; height:160px; background:#1a1a2e; align-items:center; justify-content:center; color:#6b6b8a; font-size:13px;">📷 Not found</div>
            </a>
            <div style="padding:14px;">
                <!-- Photographer info -->
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                    <a href="photographer_home.php?id=<?php echo $row['photographer_id']; ?>" style="display:flex; align-items:center; gap:8px; text-decoration:none;">
                        <div style="width:30px;height:30px;border-radius:50%;background:#1e1535;display:flex;align-items:center;justify-content:center;color:#a855f7;font-size:12px;flex-shrink:0;">👤</div>
                        <span style="font-size:13px; font-weight:600; color:#fff; transition:color .2s;" onmouseover="this.style.color='#a855f7'" onmouseout="this.style.color='#fff'">
                            <?php echo htmlspecialchars($row['photographer_name'] ?: $row['username']); ?>
                        </span>
                    </a>
                    <button class="follow-btn" onclick="followPhotographer(this, <?php echo $row['photographer_id']; ?>)">Follow</button>
                </div>
                <!-- Description -->
                <?php if(!empty($row['description'])): ?>
                <p style="font-size:12px; color:#a0a0c0; margin-bottom:10px; line-height:1.5;"><?php echo htmlspecialchars(mb_strimwidth($row['description'],0,80,'...')); ?></p>
                <?php endif; ?>
                <!-- Actions -->
                <div style="display:flex; align-items:center; gap:14px;">
                    <button onclick="likePost(<?php echo $row['id']; ?>, this)" style="display:flex; align-items:center; gap:4px; font-size:12px; color:#6b6b8a; background:none; border:none; cursor:pointer;">
                        ❤️ <span><?php echo $row['like_count']; ?></span>
                    </button>
                    <a href="photo.php?id=<?php echo $row['id']; ?>" style="display:flex; align-items:center; gap:4px; font-size:12px; color:#6b6b8a; text-decoration:none;">
                        💬 <span><?php echo $row['comment_count']; ?></span>
                    </a>
                    <span style="font-size:11px; color:#6b6b8a; margin-left:auto;"><?php echo date('d M', strtotime($row['upload_date'])); ?></span>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div style="text-align:center; padding:60px 20px;">
            <div style="font-size:48px; margin-bottom:16px;">📷</div>
            <h3 style="color:#fff; font-size:18px; margin-bottom:8px;">No photos found</h3>
            <p style="color:#6b6b8a;">Try a different search or category</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div style="display:flex; flex-direction:column; gap:16px;">

        <!-- Top Photographers -->
        <div class="cap-card" style="padding:20px;">
            <h3 style="font-size:14px; font-weight:600; color:#fff; margin:0 0 16px; padding-bottom:12px; border-bottom:1px solid #2a2a3e;">📸 Top Photographers</h3>
            <?php
            $top_sql = "SELECT users.id, CONCAT(users.first_name, ' ', users.last_name) AS name, users.username,
                        COUNT(DISTINCT photos.id) AS photo_count,
                        COUNT(DISTINCT likes.id) AS total_likes
                        FROM users
                        LEFT JOIN photos ON users.id = photos.user_id
                        LEFT JOIN likes  ON photos.id = likes.photo_id
                        WHERE users.role = 'photographer'
                        GROUP BY users.id ORDER BY total_likes DESC LIMIT 5";
            $top_r = mysqli_query($conn, $top_sql);
            if($top_r && mysqli_num_rows($top_r) > 0):
                while($tp = mysqli_fetch_assoc($top_r)): ?>
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                <div style="width:36px;height:36px;border-radius:50%;background:#1e1535;display:flex;align-items:center;justify-content:center;color:#a855f7;font-size:14px;flex-shrink:0;">👤</div>
                <div style="flex:1; min-width:0;">
                    <a href="photographer_home.php?id=<?php echo $tp['id']; ?>" style="font-size:13px; font-weight:600; color:#fff; text-decoration:none;" onmouseover="this.style.color='#a855f7'" onmouseout="this.style.color='#fff'">
                        <?php echo htmlspecialchars($tp['name'] ?: $tp['username']); ?>
                    </a>
                    <div style="font-size:11px; color:#6b6b8a;"><?php echo $tp['photo_count']; ?> photos · <?php echo $tp['total_likes']; ?> likes</div>
                </div>
                <button class="follow-btn" onclick="followPhotographer(this, <?php echo $tp['id']; ?>)">Follow</button>
            </div>
            <?php endwhile; else: ?>
            <p style="color:#6b6b8a; font-size:13px;">No photographers yet</p>
            <?php endif; ?>
        </div>

        <!-- Browse by Category -->
        <div class="cap-card" style="padding:20px;">
            <h3 style="font-size:14px; font-weight:600; color:#fff; margin:0 0 14px; padding-bottom:12px; border-bottom:1px solid #2a2a3e;">🗂️ Browse by Category</h3>
            <div style="display:flex; flex-direction:column; gap:8px;">
                <?php
                $cats = ['Wedding'=>'💒','Portrait'=>'👤','Nature'=>'🌿','Street'=>'🏙️','Fashion'=>'👗','Landscape'=>'🏔️','Events'=>'🎭'];
                foreach($cats as $cat => $icon): ?>
                <a href="explore.php?cat=<?php echo urlencode($cat); ?>" style="display:flex; align-items:center; gap:10px; padding:8px 10px; border-radius:8px; text-decoration:none; color:#a0a0c0; font-size:13px; transition:all .2s;" onmouseover="this.style.background='#1e1535'; this.style.color='#a855f7'" onmouseout="this.style.background='transparent'; this.style.color='#a0a0c0'">
                    <span><?php echo $icon; ?></span> <?php echo $cat; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>

<!-- Footer -->
<footer style="border-top:1px solid #1e1a2e; padding:32px 24px; text-align:center;">
    <p style="color:#6b6b8a; font-size:13px;">© 2025 Capturra. All rights reserved. Where Creators Shine ✨</p>
</footer>

<script>
function toggleProfileMenu() { document.getElementById('profileMenu').classList.toggle('hidden'); }
function toggleNotifications() { alert('Notifications 🔔'); }

document.addEventListener('click', function(e) {
    const pm = document.getElementById('profileMenu');
    if (pm && !pm.classList.contains('hidden') && !pm.contains(e.target) && !e.target.closest('[onclick*="toggleProfileMenu"]'))
        pm.classList.add('hidden');
});

function followPhotographer(btn, id) {
    if (btn.textContent.trim() === 'Follow') {
        btn.textContent = 'Following';
        btn.style.background = '#1a2e1a';
        btn.style.color = '#4ade80';
    } else {
        btn.textContent = 'Follow';
        btn.style.background = '#1e1535';
        btn.style.color = '#a855f7';
    }
}

function likePost(photoId, btn) {
    fetch("like.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "photo_id=" + photoId
    })
    .then(res => res.json())
    .then(data => {
        let span = btn.querySelector("span");
        let count = parseInt(span.textContent);
        if (data.status === "liked") { span.textContent = count + 1; btn.style.color = '#ef4444'; }
        else { span.textContent = count - 1; btn.style.color = '#6b6b8a'; }
    });
}

function logout() {
    fetch("/Capturra/api/auth/logout.php")
        .then(res => res.json())
        .then(data => { if(data.status) window.location.href="/Capturra/public/login.html"; });
}

function handleDoubleClick(e, imgEl, photoId) {
    e.preventDefault();

    fetch("like.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "photo_id=" + photoId
    })
    .then(res => res.json())
    .then(data => {

        if (data.status === "error") {
            alert("Login required");
            return;
        }

        let card = imgEl.closest('.photo-card');
        let likeBtn = card.querySelector("button span");

        if (likeBtn) {
            let count = parseInt(likeBtn.textContent);
            likeBtn.textContent = data.status === "liked" ? count + 1 : count - 1;
        }
    });
}

</script>
</body>
</html>