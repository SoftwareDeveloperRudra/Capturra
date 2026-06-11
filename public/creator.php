<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();
$host = "localhost"; $dbname = "capturra"; $username = "root"; $password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $my_id = $_SESSION['user_id'] ?? 0;

    // 1. Get Top 3 Creators for the Banner (Most posts)
    $top_sql = "SELECT u.id, u.username, 
                (SELECT COUNT(*) FROM photos WHERE user_id = u.id) as post_count
                FROM users u ORDER BY post_count DESC LIMIT 3";
    $top_creators = $pdo->query($top_sql)->fetchAll(PDO::FETCH_ASSOC);

    // 2. Get All Creators + Follow Status
    $all_sql = "SELECT u.id, u.username, 
                (SELECT COUNT(*) FROM photos WHERE user_id = u.id) as total_posts,
                (SELECT COUNT(*) FROM follows WHERE following_id = u.id) as follower_count,
                (SELECT COUNT(*) FROM follows WHERE follower_id = $my_id AND following_id = u.id) as is_following
                FROM users u WHERE u.id != $my_id ORDER BY u.id DESC";
    $photographers = $pdo->query($all_sql)->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) { die("DB Error: " . $e->getMessage()); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Capturra | Discover</title>
    <script src="https://jquery.com"></script>
    <style>
        :root { --bg: #000000; --card: #000000; --purple: #8a2be2; --gold: #ffd700; --white: #fff; }
        body { background: var(--bg); color: var(--white); font-family: 'Segoe UI', sans-serif; padding: 40px; margin: 0; }
        
        /* Top 3 Banner */
        .top-banner {
            background: linear-gradient(135deg, #000000 0%, #000000 100%);
            padding: 40px; border-radius: 30px; margin-bottom: 50px;
            text-align: center; border: 1px solid rgba(255, 215, 0, 0.3);
        }
        .top-flex { display: flex; justify-content: center; gap: 20px; margin-top: 20px; }
        .top-card { 
            background: rgba(255,255,255,0.05); padding: 20px; border-radius: 20px; 
            width: 180px; border: 1px solid var(--gold); 
        }

        /* Grid */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
        .card { background: var(--card); padding: 30px; border-radius: 24px; text-align: center; border: 1px solid rgba(255,255,255,0.05); transition: 0.3s; }
        .card:hover { transform: translateY(-10px); border-color: var(--purple); }
        
        .avatar { width: 80px; height: 80px; background: var(--purple); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; }
        .btn-follow { 
            width: 100%; padding: 12px; border-radius: 12px; border: 2px solid var(--purple); 
            background: transparent; color: white; cursor: pointer; font-weight: bold; transition: 0.3s;
        }
        .btn-follow.active { background: var(--purple); }
        .view-btn { display: block; margin-top: 15px; color: #aaa; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>

    <!-- SECTION 1: TOP 3 BANNER -->
    <div class="top-banner">
        <h1 style="color: var(--gold); margin: 0;">🏆 Top Creators</h1>
        <p style="color: #aaa;">The most active photographers this week</p>
        <div class="top-flex">
            <?php foreach ($top_creators as $index => $top): ?>
                <div class="top-card">
                    <div style="font-size: 0.8rem; color: var(--gold);">RANK #<?= $index + 1 ?></div>
                    <h3 style="margin: 10px 0;"><?= htmlspecialchars($top['username']) ?></h3>
                    <div style="font-size: 0.9rem;"><?= $top['post_count'] ?> Posts</div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- SECTION 2: ALL PHOTOGRAPHERS -->
    <h2>Community Creators</h2>
    <div class="grid">
        <?php foreach ($photographers as $p): ?>
            <div class="card">
                <div class="avatar"><?= substr($p['username'], 0, 1) ?></div>
                <h3><?= htmlspecialchars($p['username']) ?></h3>
                <p><b id="count-<?= $p['id'] ?>"><?= $p['follower_count'] ?></b> Followers</p>
                
                <button class="btn-follow <?= $p['is_following'] ? 'active' : '' ?>" 
                        onclick="toggleFollow(this, <?= $p['id'] ?>, '<?= $_SESSION['username'] ?? 'Someone' ?>')">
                    <?= $p['is_following'] ? 'Following' : 'Follow' ?>
                </button>
                
                <a href="profile.php?id=<?= $p['id'] ?>" class="view-btn">View Portfolio</a>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
    function toggleFollow(btn, creatorId, myName) {
        $.post('follow_action.php', { creator_id: creatorId, follower_name: myName }, function(data) {
            let res = JSON.parse(data);
            if (res.status === 'followed') {
                $(btn).addClass('active').text('Following');
                updateCount(creatorId, 1);
            } else if (res.status === 'unfollowed') {
                $(btn).removeClass('active').text('Follow');
                updateCount(creatorId, -1);
            } else { alert(res.message); }
        });
    }
    function updateCount(id, change) {
        let el = $('#count-' + id);
        el.text(parseInt(el.text()) + change);
    }
    </script>
</body>
</html>
