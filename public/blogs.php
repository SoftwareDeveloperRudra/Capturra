<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/auth.php";
include("../config/database.php");
$role = $_SESSION['role'] ?? 'client';
$name = $_SESSION['name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capturra - Blogs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        body { background: #0f0f13; color: #e2e0f0; margin: 0; }
        .gradient-bg { background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); }
        nav { background: #0d0d11; border-bottom: 1px solid #2a2a3e; }
        .nav-link { color: #a0a0c0; font-size: 13px; text-decoration: none; transition: color .2s; font-weight: 500; }
        .nav-link:hover { color: #a855f7; }

        /* ── Category Icon Tabs ── */
        .cat-tabs { display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; padding: 24px 24px 0; max-width: 1100px; margin: 0 auto; }

        .cat-tab {
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            padding: 16px 20px; border-radius: 16px; cursor: pointer;
            border: 1px solid #2a2a3e; background: #16161f;
            transition: all .25s; min-width: 110px;
        }
        .cat-tab:hover { border-color: #7c3aed; background: #1e1535; }
        .cat-tab.active { border-color: #7c3aed; background: #1e1535; box-shadow: 0 0 20px rgba(124,58,237,0.2); }
        .cat-tab .icon { font-size: 28px; }
        .cat-tab .label { font-size: 12px; font-weight: 600; color: #a0a0c0; text-align: center; }
        .cat-tab.active .label { color: #a855f7; }
        .cat-tab .count { font-size: 10px; color: #6b6b8a; }

        /* ── Tab Content ── */
        .tab-section { display: none; }
        .tab-section.active { display: block; }

        /* ── Article Cards ── */
        .article-card {
            background: #16161f; border: 1px solid #2a2a3e; border-radius: 14px;
            overflow: hidden; transition: all .3s;
        }
        .article-card:hover { border-color: #7c3aed; transform: translateY(-3px); box-shadow: 0 12px 30px rgba(124,58,237,0.15); }

        .tag { display: inline-block; background: #1e1535; color: #a855f7; border: 1px solid #3a2060; font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 500; }

        .thumb { display: flex; align-items: center; justify-content: center; font-size: 52px; }
        .featured-thumb { height: 220px; }
        .small-thumb { height: 150px; }

        .read-btn { background: transparent; border: 1px solid #7c3aed; color: #a855f7; padding: 7px 16px; border-radius: 8px; font-size: 12px; cursor: pointer; transition: all .3s; font-family: 'Inter', sans-serif; }
        .read-btn:hover { background: #1e1535; }

        .sidebar-card { background: #16161f; border: 1px solid #2a2a3e; border-radius: 12px; padding: 18px; }
        .tip-card { background: #1a0a2e; border: 1px solid #3a2060; border-radius: 10px; padding: 12px; margin-bottom: 10px; font-size: 12px; color: #a0a0c0; }
        .tip-card:last-child { margin-bottom: 0; }

        /* ── Gear Rating ── */
        .rating-bar-bg { background: #1e1e2e; border-radius: 20px; height: 5px; overflow: hidden; margin-top: 4px; }
        .rating-bar { background: linear-gradient(90deg, #7c3aed, #a855f7); height: 100%; border-radius: 20px; }
        .star { color: #fbbf24; font-size: 11px; }

        /* ── Story card ── */
        .story-card { background: #16161f; border: 1px solid #2a2a3e; border-radius: 14px; padding: 20px; transition: all .3s; }
        .story-card:hover { border-color: #7c3aed; transform: translateY(-3px); box-shadow: 0 12px 30px rgba(124,58,237,0.15); }
        .quote-block { border-left: 3px solid #7c3aed; padding: 10px 14px; background: #1a0a2e; border-radius: 0 8px 8px 0; margin: 10px 0; font-size: 12px; color: #c084fc; font-style: italic; }
        .avatar-initials { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 700; color: #fff; flex-shrink: 0; }

        /* ── Checklist ── */
        .check-item { display: flex; gap: 10px; align-items: flex-start; padding: 9px 0; border-bottom: 1px solid #1e1e2e; font-size: 12px; color: #a0a0c0; }
        .check-item:last-child { border-bottom: none; }

        /* ── Tool pill ── */
        .tool-pill { display: inline-flex; align-items: center; gap: 5px; background: #1e1535; color: #a855f7; border: 1px solid #3a2060; padding: 5px 11px; border-radius: 20px; font-size: 11px; margin: 3px; cursor: pointer; }

        /* ── Mood pill ── */
        .mood-pill { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; cursor: pointer; margin: 3px; }

        /* ── Step ── */
        .step-num { width: 22px; height: 22px; border-radius: 50%; background: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; color: #fff; flex-shrink: 0; margin-top: 1px; }
        .step-row { display: flex; gap: 10px; align-items: flex-start; padding: 9px 0; border-bottom: 1px solid #1e1e2e; }
        .step-row:last-child { border-bottom: none; }

        /* ── Search ── */
        .search-input { background: #16161f; border: 1px solid #3a3a5c; border-radius: 10px; color: #e2e0f0; padding: 10px 16px 10px 40px; outline: none; font-size: 13px; transition: border-color .3s; }
        .search-input:focus { border-color: #7c3aed; }
        .search-input::placeholder { color: #6b6b8a; }

        /* newsletter input */
        .nl-input { width: 100%; background: #0f0f13; border: 1px solid #3a3a5c; border-radius: 8px; color: #e2e0f0; padding: 8px 12px; font-size: 12px; outline: none; margin-bottom: 8px; box-sizing: border-box; }
        .nl-btn { width: 100%; padding: 8px; border-radius: 8px; font-size: 12px; font-weight: 600; color: #fff; background: linear-gradient(135deg,#7c3aed,#5b21b6); border: none; cursor: pointer; }

        .notification-dot { animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1}50%{opacity:.5} }
        #profileMenu { background: #16161f; border-color: #2a2a3e; }
    </style>
</head>
<body class="min-h-screen">



<!-- Hero -->
<section style="background:radial-gradient(ellipse at 50% 0%,#1a0a2e 0%,#0f0f13 60%);padding:36px 24px 20px;text-align:center;">
    <div style="display:inline-block;margin-bottom:10px;padding:4px 14px;border-radius:20px;font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;background:#1e1535;color:#a855f7;border:1px solid #3a2060;">Capturra Blog</div>
    <h1 style="font-size:28px;font-weight:700;color:#fff;margin:0 0 6px;">Stories, Tips & Inspiration</h1>
    <p style="color:#6b6b8a;font-size:13px;margin:0 0 20px;">Photography guides, creator stories, and industry insights</p>
    <div style="position:relative;max-width:420px;margin:0 auto;">
        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#6b6b8a;font-size:14px;">🔍</span>
        <input type="text" class="search-input" placeholder="Search articles..." style="width:100%;box-sizing:border-box;">
    </div>
</section>

<!-- ── Category Tabs ── -->
<div class="cat-tabs">
    <div class="cat-tab active" onclick="switchTab('tips', this)">
        <span class="icon">📷</span>
        <span class="label">Photography<br>Tips</span>
    </div>
    <div class="cat-tab" onclick="switchTab('gear', this)">
        <span class="icon">📸</span>
        <span class="label">Gear<br>Reviews</span>
    </div>
    <div class="cat-tab" onclick="switchTab('stories', this)">
        <span class="icon">🌟</span>
        <span class="label">Creator<br>Stories</span>
    </div>
    <div class="cat-tab" onclick="switchTab('business', this)">
        <span class="icon">💼</span>
        <span class="label">Business</span>
    </div>
    <div class="cat-tab" onclick="switchTab('editing', this)">
        <span class="icon">🎨</span>
        <span class="label">Editing</span>
    </div>
    <div class="cat-tab" onclick="switchTab('inspiration', this)">
        <span class="icon">✨</span>
        <span class="label">Inspiration</span>
    </div>
</div>

<!-- ══════════════════════════════════════════
     MAIN CONTENT WRAPPER
══════════════════════════════════════════ -->
<div style="max-width:1100px;margin:0 auto;padding:24px 24px 60px;">

<!-- ══════════════ PHOTOGRAPHY TIPS ══════════════ -->
<div id="tab-tips" class="tab-section active">
<div style="display:grid;grid-template-columns:1fr 270px;gap:24px;">
    <div>
        <!-- Featured -->
        <div class="article-card" style="margin-bottom:18px;">
            <div class="thumb featured-thumb" style="background:linear-gradient(135deg,#1a0a2e,#3b1f7a);">🌄</div>
            <div style="padding:20px;">
                <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;"><span class="tag">⭐ Featured</span><span style="color:#6b6b8a;font-size:11px;">Apr 15, 2025 · 8 min</span></div>
                <h2 style="font-size:17px;font-weight:700;color:#fff;margin:0 0 8px;">Mastering Golden Hour: Complete Guide for Photographers</h2>
                <p style="color:#a0a0c0;font-size:12px;line-height:1.7;margin:0 0 14px;">The golden hour — just after sunrise and before sunset — offers the most flattering natural light. Plan your shoots, choose the right settings, and capture breathtaking images during this magical window.</p>
                <ul style="color:#a0a0c0;font-size:12px;line-height:1.9;padding-left:16px;margin:0 0 14px;">
                    <li>Shoot within 30 minutes of sunrise/sunset</li>
                    <li>Use low ISO (100–400) to minimize noise</li>
                    <li>Shoot towards the light for silhouettes</li>
                    <li>Plan location using PhotoPills or Sun Surveyor</li>
                </ul>
                <button class="read-btn">Read Full Article →</button>
            </div>
        </div>
        <!-- Grid -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <?php
            $tip_articles = [
                ['📐','Composition','Rule of Thirds & When to Break It','The rule of thirds is the foundation of strong composition — but knowing when to ignore it separates good photographers from great ones.','Apr 10'],
                ['💡','Lighting','5 Natural Lighting Setups Every Photographer Needs','You don\'t need expensive studio lights. Master these 5 natural light setups and shoot stunning portraits anywhere.','Apr 8'],
                ['🎯','Focus','Autofocus Modes Explained: AF-S vs AF-C vs MF','Choosing the wrong focus mode can ruin a perfect shot. This guide breaks down when to use each mode.','Apr 5'],
                ['⚡','Exposure','The Exposure Triangle: ISO, Aperture, Shutter Speed','Understanding the exposure triangle is the single most important skill for any photographer.','Apr 2'],
                ['🌙','Night','Night Photography: Capturing Stars & City Lights','Long exposures, light painting, and astrophotography — your complete guide to shooting after dark.','Mar 28'],
                ['🌿','Nature','Wildlife Photography: Patience is Your Best Lens','Wildlife photography demands patience and preparation. Here\'s how the pros do it.','Mar 24'],
            ];
            foreach($tip_articles as $a): ?>
            <div class="article-card">
                <div class="thumb small-thumb" style="background:linear-gradient(135deg,#1a1a2e,#2d1f50);"><?php echo $a[0];?></div>
                <div style="padding:14px;">
                    <div style="display:flex;gap:6px;margin-bottom:6px;"><span class="tag"><?php echo $a[1];?></span><span style="color:#6b6b8a;font-size:10px;"><?php echo $a[4];?></span></div>
                    <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 6px;"><?php echo $a[2];?></h3>
                    <p style="color:#a0a0c0;font-size:11px;line-height:1.6;margin:0 0 10px;"><?php echo $a[3];?></p>
                    <button class="read-btn" style="width:100%;font-size:11px;">Read More →</button>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <!-- Sidebar -->
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div class="sidebar-card">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 12px;padding-bottom:10px;border-bottom:1px solid #2a2a3e;">⚡ Quick Tips</h3>
            <div class="tip-card">📷 <b style="color:#a855f7;">Tip #1</b><br>Shoot in RAW format for maximum editing control.</div>
            <div class="tip-card">📷 <b style="color:#a855f7;">Tip #2</b><br>Always check your histogram, not just the LCD screen.</div>
            <div class="tip-card">📷 <b style="color:#a855f7;">Tip #3</b><br>Use a tripod for anything slower than 1/60s.</div>
            <div class="tip-card">📷 <b style="color:#a855f7;">Tip #4</b><br>The best camera is the one you have with you.</div>
        </div>
        <div class="sidebar-card" style="background:linear-gradient(135deg,#1a0a2e,#16161f);">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 6px;">📬 Newsletter</h3>
            <p style="font-size:11px;color:#a0a0c0;margin:0 0 10px;">Weekly photography tips in your inbox.</p>
            <input type="email" placeholder="Your email" class="nl-input">
            <button class="nl-btn">Subscribe</button>
        </div>
        <div class="sidebar-card">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 12px;padding-bottom:10px;border-bottom:1px solid #2a2a3e;">🏷️ Tags</h3>
            <div style="display:flex;flex-wrap:wrap;gap:6px;">
                <span class="tag" style="cursor:pointer;">#portrait</span>
                <span class="tag" style="cursor:pointer;">#landscape</span>
                <span class="tag" style="cursor:pointer;">#lightroom</span>
                <span class="tag" style="cursor:pointer;">#gear</span>
                <span class="tag" style="cursor:pointer;">#tips</span>
                <span class="tag" style="cursor:pointer;">#editing</span>
                <span class="tag" style="cursor:pointer;">#street</span>
                <span class="tag" style="cursor:pointer;">#business</span>
            </div>
        </div>
    </div>
</div>
</div>

<!-- ══════════════ GEAR REVIEWS ══════════════ -->
<div id="tab-gear" class="tab-section">
<div style="display:grid;grid-template-columns:1fr 270px;gap:24px;">
    <div>
        <!-- Featured Review -->
        <div class="article-card" style="margin-bottom:18px;">
            <div class="thumb featured-thumb" style="background:linear-gradient(135deg,#1a0a2e,#3b1f7a);">📷</div>
            <div style="padding:20px;">
                <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;"><span class="tag">⭐ Editor's Choice</span><span style="color:#6b6b8a;font-size:11px;">Apr 12, 2025</span></div>
                <h2 style="font-size:17px;font-weight:700;color:#fff;margin:0 0 6px;">Sony A7 IV Review: Best All-Around Mirrorless in 2025</h2>
                <div style="display:flex;gap:2px;margin-bottom:10px;"><span class="star">★★★★★</span><span style="color:#6b6b8a;font-size:12px;margin-left:6px;">9.4 / 10</span></div>
                <p style="color:#a0a0c0;font-size:12px;line-height:1.7;margin:0 0 12px;">After 6 weeks of shooting weddings, portraits, and street photography with the Sony A7 IV — here's our definitive verdict.</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
                    <div style="background:#0f2a1a;border:1px solid #1a4a2a;border-radius:8px;padding:10px;">
                        <div style="font-size:11px;font-weight:600;color:#4ade80;margin-bottom:6px;">✅ Pros</div>
                        <ul style="color:#a0a0c0;font-size:11px;line-height:1.8;padding-left:12px;margin:0;"><li>Outstanding 33MP sensor</li><li>Excellent AF tracking</li><li>Great battery life</li></ul>
                    </div>
                    <div style="background:#2a1010;border:1px solid #4a1a1a;border-radius:8px;padding:10px;">
                        <div style="font-size:11px;font-weight:600;color:#f87171;margin-bottom:6px;">❌ Cons</div>
                        <ul style="color:#a0a0c0;font-size:11px;line-height:1.8;padding-left:12px;margin:0;"><li>Expensive body price</li><li>Slow buffer clearing</li><li>Heavy for travel</li></ul>
                    </div>
                </div>
                <button class="read-btn">Read Full Review →</button>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <?php
            $gear_articles = [
                ['🔭','Lens','Sigma 35mm f/1.4 Art: Worth It in 2025?','★★★★','8.6/10','Apr 8','Sharp, fast, and affordable — still king for street photography?'],
                ['🎒','Accessories','Best Camera Bags for Photographers 2025','★★★★★','9.1/10','Apr 5','We tested 12 bags — here are the 5 that survive real shooting conditions.'],
                ['💡','Lighting','Godox AD200 Pro: Best Portable Flash?','★★★★★','9.3/10','Apr 1','Powerful, compact, and reasonably priced — a game-changer for on-location shoots.'],
                ['🖥️','Editing','Best Monitors for Photo Editing 2025','★★★★','8.8/10','Mar 28','Color accuracy matters. These are the monitors professional retouchers actually use.'],
            ];
            foreach($gear_articles as $g): ?>
            <div class="article-card">
                <div class="thumb small-thumb" style="background:linear-gradient(135deg,#1a1a2e,#2d1f50);"><?php echo $g[0];?></div>
                <div style="padding:14px;">
                    <div style="display:flex;gap:6px;margin-bottom:4px;"><span class="tag"><?php echo $g[1];?></span><span style="color:#6b6b8a;font-size:10px;"><?php echo $g[5];?></span></div>
                    <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 4px;"><?php echo $g[2];?></h3>
                    <div style="display:flex;gap:2px;margin-bottom:6px;"><span class="star"><?php echo $g[3];?></span><span style="color:#6b6b8a;font-size:10px;margin-left:4px;"><?php echo $g[4];?></span></div>
                    <p style="color:#a0a0c0;font-size:11px;line-height:1.5;margin:0 0 10px;"><?php echo $g[6];?></p>
                    <button class="read-btn" style="width:100%;font-size:11px;">Read Review →</button>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <!-- Sidebar -->
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div class="sidebar-card">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 14px;padding-bottom:10px;border-bottom:1px solid #2a2a3e;">🏆 Top Rated Gear</h3>
            <?php
            $rated = [
                ['Sony A7 IV','94'],
                ['Godox AD200 Pro','93'],
                ['Camera Bags Top 5','91'],
                ['Sigma 35mm Art','86'],
            ];
            foreach($rated as $r): ?>
            <div style="margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:3px;"><span style="font-size:12px;color:#e2e0f0;"><?php echo $r[0];?></span><span style="font-size:11px;color:#fbbf24;"><?php echo $r[1];?>★</span></div>
                <div class="rating-bar-bg"><div class="rating-bar" style="width:<?php echo $r[1];?>%;"></div></div>
            </div>
            <?php endforeach;?>
        </div>
        <div class="sidebar-card" style="background:linear-gradient(135deg,#1a0a2e,#16161f);">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 6px;">📬 Newsletter</h3>
            <p style="font-size:11px;color:#a0a0c0;margin:0 0 10px;">Weekly gear reviews in your inbox.</p>
            <input type="email" placeholder="Your email" class="nl-input">
            <button class="nl-btn">Subscribe</button>
        </div>
    </div>
</div>
</div>

<!-- ══════════════ CREATOR STORIES ══════════════ -->
<div id="tab-stories" class="tab-section">
<div style="display:grid;grid-template-columns:1fr 270px;gap:24px;">
    <div>
        <!-- Featured Story -->
        <div class="story-card" style="margin-bottom:18px;border-color:#3a2060;background:linear-gradient(135deg,#1a0a2e,#16161f);">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">
                <div class="avatar-initials" style="background:linear-gradient(135deg,#7c3aed,#a855f7);">SJ</div>
                <div>
                    <h3 style="font-size:15px;font-weight:700;color:#fff;margin:0 0 2px;">Sarah Johnson</h3>
                    <p style="font-size:11px;color:#6b6b8a;margin:0;">Wedding Photographer · Los Angeles</p>
                    <div style="margin-top:4px;"><span class="tag">⭐ Featured Story</span></div>
                </div>
            </div>
            <h2 style="font-size:16px;font-weight:700;color:#fff;margin:0 0 10px;">From 9-to-5 to Full-Time Photographer: My 3-Year Journey</h2>
            <div class="quote-block">"I quit my corporate job with ₹0 savings and a secondhand Canon. Three years later, I'm fully booked 6 months in advance."</div>
            <p style="color:#a0a0c0;font-size:12px;line-height:1.7;margin:0 0 14px;">Sarah started shooting friends' weddings for free, built a portfolio over 8 months, and landed her first paid client at ₹15,000. Today, her packages start at ₹80,000.</p>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:14px;">
                <div style="background:#16161f;border:1px solid #2a2a3e;border-radius:8px;padding:10px;text-align:center;"><div style="font-size:16px;font-weight:700;color:#a855f7;">3 yrs</div><div style="font-size:10px;color:#6b6b8a;">Journey</div></div>
                <div style="background:#16161f;border:1px solid #2a2a3e;border-radius:8px;padding:10px;text-align:center;"><div style="font-size:16px;font-weight:700;color:#a855f7;">120+</div><div style="font-size:10px;color:#6b6b8a;">Weddings</div></div>
                <div style="background:#16161f;border:1px solid #2a2a3e;border-radius:8px;padding:10px;text-align:center;"><div style="font-size:16px;font-weight:700;color:#a855f7;">₹80K</div><div style="font-size:10px;color:#6b6b8a;">Package</div></div>
            </div>
            <button class="read-btn">Read Full Story →</button>
        </div>
        <!-- Story Grid -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <?php
            $stories = [
                ['AC','Alex Chen','Portrait · NY','#1a3a6a','How I Got My First 1,000 Clients Through Social Media',"Instagram wasn't just my portfolio — it was my entire sales funnel.",'Alex cracked the algorithm before it was fashionable. His content strategy brought 1K paying clients in under 2 years.'],
                ['MR','Mike Rodriguez','Nature · CO','#0f3a1a','Selling Prints Online: ₹5 Lakh in My First Year',"Nature photography doesn't have to be a passion project — it can be a real business.",'Mike\'s landscape prints now sell globally. He shares exactly how he set up his print-on-demand store.'],
                ['EW','Emma Wilson','Fashion · Mumbai','#3a0a2a','Shooting for Vogue India: My Unexpected Break','The Vogue editor found me through a random Capturra post. Opportunity finds prepared people.','Emma went from fashion blogger to Vogue India contributor in 18 months.'],
                ['DK','David Kim','Street · Chicago','#2a1a0a','Teaching Photography: ₹2L/Month Workshop Business',"I stopped selling photos and started selling skills. That changed everything.",'David now runs sold-out photography workshops every month.'],
            ];
            foreach($stories as $s): ?>
            <div class="story-card">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                    <div class="avatar-initials" style="width:38px;height:38px;font-size:13px;background:<?php echo $s[3];?>;"><?php echo $s[0];?></div>
                    <div><div style="font-size:12px;font-weight:700;color:#fff;"><?php echo $s[1];?></div><div style="font-size:10px;color:#6b6b8a;"><?php echo $s[2];?></div></div>
                </div>
                <h3 style="font-size:12px;font-weight:600;color:#fff;margin:0 0 6px;"><?php echo $s[4];?></h3>
                <div class="quote-block" style="font-size:11px;"><?php echo $s[5];?></div>
                <p style="color:#a0a0c0;font-size:11px;line-height:1.5;margin:0 0 10px;"><?php echo $s[6];?></p>
                <button class="read-btn" style="width:100%;font-size:11px;">Read Story →</button>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <!-- Sidebar -->
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div class="sidebar-card" style="background:linear-gradient(135deg,#1a0a2e,#16161f);">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 6px;">🎤 Share Your Story</h3>
            <p style="font-size:11px;color:#a0a0c0;margin:0 0 10px;">Are you a creator with an inspiring journey? We'd love to feature you!</p>
            <button class="nl-btn">Submit Your Story</button>
        </div>
        <div class="sidebar-card">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 12px;padding-bottom:10px;border-bottom:1px solid #2a2a3e;">🌟 Featured Creators</h3>
            <?php foreach($stories as $s): ?>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                <div class="avatar-initials" style="width:30px;height:30px;font-size:11px;background:<?php echo $s[3];?>;"><?php echo $s[0];?></div>
                <div><div style="font-size:11px;font-weight:600;color:#fff;"><?php echo $s[1];?></div><div style="font-size:10px;color:#6b6b8a;"><?php echo $s[2];?></div></div>
            </div>
            <?php endforeach;?>
        </div>
        <div class="sidebar-card" style="background:linear-gradient(135deg,#1a0a2e,#16161f);">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 6px;">📬 Newsletter</h3>
            <input type="email" placeholder="Your email" class="nl-input">
            <button class="nl-btn">Subscribe</button>
        </div>
    </div>
</div>
</div>

<!-- ══════════════ BUSINESS ══════════════ -->
<div id="tab-business" class="tab-section">
<div style="display:grid;grid-template-columns:1fr 270px;gap:24px;">
    <div>
        <!-- Featured -->
        <div class="article-card" style="margin-bottom:18px;">
            <div class="thumb featured-thumb" style="background:linear-gradient(135deg,#1a0a2e,#3b1f7a);">💰</div>
            <div style="padding:20px;">
                <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;"><span class="tag">⭐ Featured</span><span style="color:#6b6b8a;font-size:11px;">Apr 14, 2025 · 10 min</span></div>
                <h2 style="font-size:17px;font-weight:700;color:#fff;margin:0 0 8px;">Complete Pricing Guide for Indian Photographers in 2025</h2>
                <p style="color:#a0a0c0;font-size:12px;line-height:1.7;margin:0 0 12px;">Stop undercharging. A data-backed framework used by top photographers across India to set rates that cover costs, pay a salary, and leave room for profit.</p>
                <div style="background:#1a0a2e;border:1px solid #3a2060;border-radius:8px;padding:14px;margin-bottom:14px;">
                    <div style="font-size:12px;font-weight:700;color:#a855f7;margin-bottom:8px;">📊 Average 2025 Rates (India)</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                        <?php $rates = [['Wedding (full day)','₹40K–₹1.5L'],['Portrait session','₹5K–₹25K'],['Product shoot','₹10K–₹50K'],['Corporate event','₹20K–₹80K']]; foreach($rates as $r): ?>
                        <div style="font-size:11px;color:#a0a0c0;"><?php echo $r[0];?></div><div style="font-size:11px;color:#fff;font-weight:600;"><?php echo $r[1];?></div>
                        <?php endforeach;?>
                    </div>
                </div>
                <button class="read-btn">Read Full Guide →</button>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <?php
            $biz_articles = [
                ['📝','Contracts','Photography Contract Template: What to Include','A solid contract protects you and your clients. Here are the 10 clauses every photography contract must have.','Apr 9'],
                ['🤝','Clients','How to Handle Difficult Clients Professionally','Late payments, scope creep, unrealistic expectations — handle the toughest client situations with grace.','Apr 6'],
                ['📱','Marketing','Instagram Marketing for Photographers: 2025 Strategy','The algorithm has changed. Here\'s what actually works for photographers on Instagram in 2025.','Apr 3'],
                ['💳','Finance','Tax Tips for Freelance Photographers in India','GST, income tax, deductions — a plain-English guide to finances for Indian photographers.','Mar 30'],
            ];
            foreach($biz_articles as $b): ?>
            <div class="article-card">
                <div class="thumb small-thumb" style="background:linear-gradient(135deg,#1a1a2e,#2d1f50);"><?php echo $b[0];?></div>
                <div style="padding:14px;">
                    <div style="display:flex;gap:6px;margin-bottom:6px;"><span class="tag"><?php echo $b[1];?></span><span style="color:#6b6b8a;font-size:10px;"><?php echo $b[4];?></span></div>
                    <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 6px;"><?php echo $b[2];?></h3>
                    <p style="color:#a0a0c0;font-size:11px;line-height:1.5;margin:0 0 10px;"><?php echo $b[3];?></p>
                    <button class="read-btn" style="width:100%;font-size:11px;">Read More →</button>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <!-- Sidebar -->
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div class="sidebar-card">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 12px;padding-bottom:10px;border-bottom:1px solid #2a2a3e;">✅ Business Checklist</h3>
            <div class="check-item"><span style="color:#a855f7;">☑</span><span>Set up a professional portfolio website</span></div>
            <div class="check-item"><span style="color:#a855f7;">☑</span><span>Create a photography contract template</span></div>
            <div class="check-item"><span style="color:#6b6b8a;">☐</span><span>Register for GST if income &gt; ₹20L</span></div>
            <div class="check-item"><span style="color:#6b6b8a;">☐</span><span>Open a dedicated business bank account</span></div>
            <div class="check-item"><span style="color:#6b6b8a;">☐</span><span>Get equipment insurance</span></div>
            <div class="check-item"><span style="color:#6b6b8a;">☐</span><span>Build a referral program</span></div>
        </div>
        <div class="sidebar-card" style="background:linear-gradient(135deg,#1a0a2e,#16161f);">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 6px;">📬 Newsletter</h3>
            <p style="font-size:11px;color:#a0a0c0;margin:0 0 10px;">Weekly business tips for photographers.</p>
            <input type="email" placeholder="Your email" class="nl-input">
            <button class="nl-btn">Subscribe</button>
        </div>
    </div>
</div>
</div>

<!-- ══════════════ EDITING ══════════════ -->
<div id="tab-editing" class="tab-section">
<div style="display:grid;grid-template-columns:1fr 270px;gap:24px;">
    <div>
        <div class="article-card" style="margin-bottom:18px;">
            <div class="thumb featured-thumb" style="background:linear-gradient(135deg,#1a0a2e,#3b1f7a);">🎨</div>
            <div style="padding:20px;">
                <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;"><span class="tag">⭐ Featured</span><span style="color:#6b6b8a;font-size:11px;">Apr 13, 2025 · 12 min</span></div>
                <h2 style="font-size:17px;font-weight:700;color:#fff;margin:0 0 8px;">Lightroom vs Photoshop: The Definitive Guide for 2025</h2>
                <p style="color:#a0a0c0;font-size:12px;line-height:1.7;margin:0 0 12px;">Two of the most powerful tools in a photographer's arsenal — but they serve very different purposes. Here's exactly when to use each.</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
                    <div style="background:#0f1e35;border:1px solid #1e3a5e;border-radius:8px;padding:12px;">
                        <div style="font-size:12px;font-weight:700;color:#60a5fa;margin-bottom:6px;">🔵 Lightroom</div>
                        <ul style="color:#a0a0c0;font-size:11px;line-height:1.7;padding-left:12px;margin:0;"><li>Batch editing photos</li><li>Applying presets</li><li>Organizing catalog</li></ul>
                    </div>
                    <div style="background:#1a1535;border:1px solid #3a2060;border-radius:8px;padding:12px;">
                        <div style="font-size:12px;font-weight:700;color:#a855f7;margin-bottom:6px;">🔮 Photoshop</div>
                        <ul style="color:#a0a0c0;font-size:11px;line-height:1.7;padding-left:12px;margin:0;"><li>Complex retouching</li><li>Removing objects</li><li>Advanced masking</li></ul>
                    </div>
                </div>
                <button class="read-btn">Read Full Guide →</button>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <?php
            $edit_articles = [
                ['🌈','Color Grading','Color Grading: Create Your Own Signature Look','Develop a consistent, recognizable editing style that makes your portfolio instantly identifiable.','Apr 10'],
                ['✨','Retouching','Skin Retouching: Frequency Separation Explained','The gold standard for professional portrait retouching — step-by-step in Photoshop.','Apr 7'],
                ['⚡','Presets','How to Create & Sell Your Own Lightroom Presets','Turn your editing style into passive income. Build, package, and sell presets online.','Apr 4'],
                ['🤖','AI Tools','AI Editing Tools in 2025: What\'s Worth Using','From Adobe Firefly to Luminar AI — which tools save time vs which are just hype.','Mar 31'],
            ];
            foreach($edit_articles as $e): ?>
            <div class="article-card">
                <div class="thumb small-thumb" style="background:linear-gradient(135deg,#1a1a2e,#2d1f50);"><?php echo $e[0];?></div>
                <div style="padding:14px;">
                    <div style="display:flex;gap:6px;margin-bottom:6px;"><span class="tag"><?php echo $e[1];?></span><span style="color:#6b6b8a;font-size:10px;"><?php echo $e[4];?></span></div>
                    <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 6px;"><?php echo $e[2];?></h3>
                    <p style="color:#a0a0c0;font-size:11px;line-height:1.5;margin:0 0 10px;"><?php echo $e[3];?></p>
                    <button class="read-btn" style="width:100%;font-size:11px;">Read More →</button>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <!-- Sidebar -->
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div class="sidebar-card">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 12px;padding-bottom:10px;border-bottom:1px solid #2a2a3e;">🛠️ Popular Tools</h3>
            <div style="display:flex;flex-wrap:wrap;">
                <?php foreach(['🔵 Lightroom','🔮 Photoshop','⚡ Luminar AI','🌟 Capture One','🎨 VSCO','📱 Snapseed','🤖 Firefly'] as $t): ?>
                <span class="tool-pill"><?php echo $t;?></span>
                <?php endforeach;?>
            </div>
        </div>
        <div class="sidebar-card">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 12px;padding-bottom:10px;border-bottom:1px solid #2a2a3e;">📋 Basic Workflow</h3>
            <?php
            $steps = [['Import & Cull','Select the best shots first'],['Exposure & WB','Fix the technical fundamentals'],['Color Grade','Apply your signature style'],['Retouch','Spot healing, skin smoothing'],['Export','Right format for right platform']];
            foreach($steps as $i => $s): ?>
            <div class="step-row">
                <div class="step-num"><?php echo $i+1;?></div>
                <div><div style="font-size:12px;color:#fff;margin-bottom:1px;"><?php echo $s[0];?></div><div style="font-size:10px;color:#6b6b8a;"><?php echo $s[1];?></div></div>
            </div>
            <?php endforeach;?>
        </div>
        <div class="sidebar-card" style="background:linear-gradient(135deg,#1a0a2e,#16161f);">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 6px;">📬 Newsletter</h3>
            <input type="email" placeholder="Your email" class="nl-input">
            <button class="nl-btn">Subscribe</button>
        </div>
    </div>
</div>
</div>

<!-- ══════════════ INSPIRATION ══════════════ -->
<div id="tab-inspiration" class="tab-section">
<div style="display:grid;grid-template-columns:1fr 270px;gap:24px;">
    <div>
        <!-- Quotes -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:18px;">
            <div style="background:linear-gradient(135deg,#1a0a2e,#1e1535);border:1px solid #3a2060;border-radius:12px;padding:16px;">
                <div style="font-size:22px;margin-bottom:8px;">🌄</div>
                <p style="font-size:12px;color:#e2e0f0;font-style:italic;line-height:1.6;margin:0 0 8px;">"The best photographs are the ones that tell a story without words."</p>
                <span style="font-size:11px;color:#a855f7;">— Ansel Adams</span>
            </div>
            <div style="background:linear-gradient(135deg,#1a0a2e,#1e1535);border:1px solid #3a2060;border-radius:12px;padding:16px;">
                <div style="font-size:22px;margin-bottom:8px;">📸</div>
                <p style="font-size:12px;color:#e2e0f0;font-style:italic;line-height:1.6;margin:0 0 8px;">"To take photographs means to recognize — simultaneously and within a fraction of a second."</p>
                <span style="font-size:11px;color:#a855f7;">— Henri Cartier-Bresson</span>
            </div>
        </div>
        <!-- Featured -->
        <div class="article-card" style="margin-bottom:18px;">
            <div class="thumb featured-thumb" style="background:linear-gradient(135deg,#1a0a2e,#3b1f7a);">🌟</div>
            <div style="padding:20px;">
                <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;"><span class="tag">⭐ Featured</span><span style="color:#6b6b8a;font-size:11px;">Apr 16, 2025 · 6 min</span></div>
                <h2 style="font-size:17px;font-weight:700;color:#fff;margin:0 0 8px;">30-Day Photography Challenge: One Theme, One Shot, Every Day</h2>
                <p style="color:#a0a0c0;font-size:12px;line-height:1.7;margin:0 0 12px;">Stuck in a creative rut? This 30-day challenge will push your creativity and build a consistent habit. Each day has a theme, a prompt, and a technique to practice.</p>
                <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px;">
                    <?php
                    $days = [['Day 1: Shadows','#1e1535','#a855f7','#3a2060'],['Day 2: Reflection','#0f1e35','#60a5fa','#1e3a5e'],['Day 3: Texture','#0f2a1a','#4ade80','#1a4a2a'],['Day 4: Silhouette','#2a1a0a','#fbbf24','#4a3010'],['Day 5: Motion Blur','#2a1010','#f87171','#4a1a1a']];
                    foreach($days as $d): ?>
                    <span style="font-size:11px;padding:5px 11px;border-radius:20px;background:<?php echo $d[0];?>;color:<?php echo $d[1];?>;border:1px solid <?php echo $d[2];?>;"><?php echo $d[0];?></span>
                    <?php endforeach;?>
                    <span style="font-size:11px;color:#6b6b8a;padding:5px 11px;">+25 more...</span>
                </div>
                <button class="read-btn">Get the Full Challenge →</button>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <?php
            $insp_articles = [
                ['🏙️','Street','10 Street Photography Scenes to Capture This Weekend','Ready-to-shoot ideas that work in any city, from crowded markets to quiet alleys.','Apr 11'],
                ['🌌','Astro','Shooting the Milky Way: A Beginner\'s Bucket List Guide','Astrophotography is easier than you think. Here\'s your first Milky Way shot guide.','Apr 8'],
                ['🌿','Macro','Macro Photography: The World in a Dewdrop','Discover the hidden universe that exists in your own backyard with macro photography.','Apr 5'],
                ['🌅','Landscape','India\'s 15 Most Photographed Locations in 2025','From Varanasi to Rann of Kutch — bucket list shots for Indian photographers.','Apr 2'],
            ];
            foreach($insp_articles as $ins): ?>
            <div class="article-card">
                <div class="thumb small-thumb" style="background:linear-gradient(135deg,#1a1a2e,#2d1f50);"><?php echo $ins[0];?></div>
                <div style="padding:14px;">
                    <div style="display:flex;gap:6px;margin-bottom:6px;"><span class="tag"><?php echo $ins[1];?></span><span style="color:#6b6b8a;font-size:10px;"><?php echo $ins[4];?></span></div>
                    <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 6px;"><?php echo $ins[2];?></h3>
                    <p style="color:#a0a0c0;font-size:11px;line-height:1.5;margin:0 0 10px;"><?php echo $ins[3];?></p>
                    <button class="read-btn" style="width:100%;font-size:11px;">Explore →</button>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <!-- Sidebar -->
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div class="sidebar-card">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 12px;padding-bottom:10px;border-bottom:1px solid #2a2a3e;">🎯 This Week's Challenge</h3>
            <div style="background:#1a0a2e;border:1px solid #3a2060;border-radius:10px;padding:14px;text-align:center;margin-bottom:12px;">
                <div style="font-size:28px;margin-bottom:6px;">🌙</div>
                <div style="font-size:14px;font-weight:700;color:#fff;margin-bottom:4px;">Blue Hour</div>
                <div style="font-size:11px;color:#a0a0c0;line-height:1.5;">Capture the magical twilight between sunset and darkness. Focus on city lights and deep blue skies.</div>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:11px;color:#6b6b8a;margin-bottom:10px;"><span>📷 342 entries</span><span>⏰ 4 days left</span></div>
            <button class="nl-btn">Join Challenge</button>
        </div>
        <div class="sidebar-card">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 12px;padding-bottom:10px;border-bottom:1px solid #2a2a3e;">🎨 Mood Themes</h3>
            <div style="display:flex;flex-wrap:wrap;">
                <span class="mood-pill" style="background:#1e1535;color:#a855f7;">Moody Dark</span>
                <span class="mood-pill" style="background:#0f1e35;color:#60a5fa;">Film Noir</span>
                <span class="mood-pill" style="background:#0f2a1a;color:#4ade80;">Nature Tones</span>
                <span class="mood-pill" style="background:#2a1a0a;color:#fbbf24;">Golden Hour</span>
                <span class="mood-pill" style="background:#2a1010;color:#f87171;">Vibrant Street</span>
                <span class="mood-pill" style="background:#1a1a2e;color:#818cf8;">Minimalist</span>
            </div>
        </div>
        <div class="sidebar-card" style="background:linear-gradient(135deg,#1a0a2e,#16161f);">
            <h3 style="font-size:13px;font-weight:600;color:#fff;margin:0 0 6px;">📬 Newsletter</h3>
            <p style="font-size:11px;color:#a0a0c0;margin:0 0 10px;">Weekly inspiration in your inbox.</p>
            <input type="email" placeholder="Your email" class="nl-input">
            <button class="nl-btn">Subscribe</button>
        </div>
    </div>
</div>
</div>

</div><!-- /main content wrapper -->

<footer style="border-top:1px solid #1e1a2e;padding:20px;text-align:center;"><p style="color:#6b6b8a;font-size:12px;">© 2025 Capturra. All rights reserved.</p></footer>

<script>
function switchTab(name, el) {
    // Hide all sections
    document.querySelectorAll('.tab-section').forEach(s => s.classList.remove('active'));
    // Deactivate all tabs
    document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
    // Activate clicked
    document.getElementById('tab-' + name).classList.add('active');
    el.classList.add('active');
}

document.addEventListener('click', function(e) {
    const pm = document.getElementById('profileMenu');
    if(pm && !pm.classList.contains('hidden') && !pm.contains(e.target) && !e.target.closest('[onclick*="profileMenu"]'))
        pm.classList.add('hidden');
});
</script>
</body>
</html>