<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Capturra Analytics</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    * { font-family: 'Inter', sans-serif; }
    body { background: #0a0a0f; }

    .card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(168,85,247,0.15);
        backdrop-filter: blur(12px);
        transition: all 0.3s ease;
    }
    .card:hover { border-color: rgba(168,85,247,0.35); box-shadow: 0 0 28px rgba(168,85,247,0.08); transform: translateY(-2px); }

    .stat-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(168,85,247,0.15);
        backdrop-filter: blur(12px);
        transition: all 0.3s ease;
        position: relative; overflow: hidden;
    }
    .stat-card::before { content:''; position:absolute; top:0;left:0;right:0; height:2px; }
    .stat-card.purple::before { background: linear-gradient(90deg,#7c3aed,#a855f7); }
    .stat-card.pink::before   { background: linear-gradient(90deg,#ec4899,#f472b6); }
    .stat-card.green::before  { background: linear-gradient(90deg,#10b981,#34d399); }
    .stat-card.blue::before   { background: linear-gradient(90deg,#3b82f6,#60a5fa); }
    .stat-card:hover { border-color:rgba(168,85,247,0.4); box-shadow:0 8px 32px rgba(168,85,247,0.12); transform:translateY(-4px); }

    .neon { text-shadow: 0 0 20px rgba(168,85,247,0.6); }

    .badge { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600; }
    .badge.up   { background:rgba(16,185,129,0.15); color:#34d399; }
    .badge.down { background:rgba(239,68,68,0.15);  color:#f87171; }

    .tab-btn { padding:7px 18px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;transition:all .2s;color:#6b6b8a;background:transparent;border:none; }
    .tab-btn.active { background:rgba(124,58,237,0.2);color:#a855f7;border:1px solid rgba(124,58,237,0.3); }
    .tab-btn:hover:not(.active) { color:#e2e0f0; }

    .progress-bg   { background:rgba(255,255,255,0.06);border-radius:20px;height:6px;overflow:hidden; }
    .progress-fill { height:100%;border-radius:20px;transition:width 1s ease; }

    .spark-bar { border-radius:2px;background:rgba(168,85,247,0.35);transition:background .2s;flex-shrink:0; }
    .spark-bar:hover { background:#a855f7; }
</style>
</head>
<body class="text-white min-h-screen">

<div class="max-w-6xl mx-auto px-4 py-8">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white neon">📊 Analytics Dashboard</h1>
            <p class="text-sm mt-1" style="color:#6b6b8a;">Track your growth across photos, likes, followers & bookings</p>
        </div>
        <div class="flex items-center gap-2">
            <button class="tab-btn" onclick="setRange('7d',this)">7D</button>
            <button class="tab-btn" onclick="setRange('30d',this)">30D</button>
            <button class="tab-btn active" onclick="setRange('6m',this)">6M</button>
            <button class="tab-btn" onclick="setRange('1y',this)">1Y</button>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        <div class="stat-card purple rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(124,58,237,0.2);display:flex;align-items:center;justify-content:center;font-size:16px;">📸</div>
                <span class="badge up">↑ 12%</span>
            </div>
            <div class="text-2xl font-bold text-white">48</div>
            <div class="text-xs mt-1" style="color:#6b6b8a;">Total Photos</div>
            <div class="flex items-end gap-0.5 mt-3" style="height:28px;">
                <?php foreach([3,5,4,7,6,8,9] as $h): $pct=round($h/9*100); ?>
                <div class="spark-bar flex-1" style="height:<?php echo $pct;?>%;"></div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="stat-card pink rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(236,72,153,0.15);display:flex;align-items:center;justify-content:center;font-size:16px;">❤️</div>
                <span class="badge up">↑ 24%</span>
            </div>
            <div class="text-2xl font-bold text-white">2.3K</div>
            <div class="text-xs mt-1" style="color:#6b6b8a;">Total Likes</div>
            <div class="flex items-end gap-0.5 mt-3" style="height:28px;">
                <?php foreach([4,6,5,8,7,9,10] as $h): $pct=round($h/10*100); ?>
                <div class="spark-bar flex-1" style="height:<?php echo $pct;?>%;background:rgba(236,72,153,0.4);"></div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="stat-card blue rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(59,130,246,0.15);display:flex;align-items:center;justify-content:center;font-size:16px;">👥</div>
                <span class="badge up">↑ 8%</span>
            </div>
            <div class="text-2xl font-bold text-white">1.2K</div>
            <div class="text-xs mt-1" style="color:#6b6b8a;">Followers</div>
            <div class="flex items-end gap-0.5 mt-3" style="height:28px;">
                <?php foreach([5,4,6,5,7,8,9] as $h): $pct=round($h/9*100); ?>
                <div class="spark-bar flex-1" style="height:<?php echo $pct;?>%;background:rgba(59,130,246,0.4);"></div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="stat-card green rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(16,185,129,0.15);display:flex;align-items:center;justify-content:center;font-size:16px;">💰</div>
                <span class="badge up">↑ 32%</span>
            </div>
            <div class="text-2xl font-bold text-white">₹50K</div>
            <div class="text-xs mt-1" style="color:#6b6b8a;">Earnings</div>
            <div class="flex items-end gap-0.5 mt-3" style="height:28px;">
                <?php foreach([4,5,7,6,8,9,10] as $h): $pct=round($h/10*100); ?>
                <div class="spark-bar flex-1" style="height:<?php echo $pct;?>%;background:rgba(16,185,129,0.4);"></div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <!-- Row 1: Bookings + Earnings -->
    <div class="grid md:grid-cols-2 gap-5 mb-5">
        <div class="card rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold" style="color:#a855f7;">📈 Bookings Growth</h3>
                <span class="badge up">↑ 15%</span>
            </div>
            <canvas id="bookingChart" height="160"></canvas>
        </div>
        <div class="card rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold" style="color:#10b981;">💰 Earnings Trend</h3>
                <span class="badge up">↑ 32%</span>
            </div>
            <canvas id="earningChart" height="160"></canvas>
        </div>
    </div>

    <!-- Row 2: Likes + Followers -->
    <div class="grid md:grid-cols-2 gap-5 mb-5">
        <div class="card rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold" style="color:#ec4899;">❤️ Likes Over Time</h3>
                <span class="badge up">↑ 24%</span>
            </div>
            <canvas id="likesChart" height="160"></canvas>
        </div>
        <div class="card rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold" style="color:#3b82f6;">👥 Followers Growth</h3>
                <span class="badge up">↑ 8%</span>
            </div>
            <canvas id="followersChart" height="160"></canvas>
        </div>
    </div>

    <!-- Row 3: Top Photos + Engagement -->
    <div class="grid md:grid-cols-2 gap-5 mb-5">

        <div class="card rounded-xl p-5">
            <h3 class="font-semibold mb-4" style="color:#a855f7;">🏆 Top Performing Photos</h3>
            <div style="display:flex;flex-direction:column;gap:14px;">
                <?php
                $top = [
                    ['Golden Hour Portrait', 284, 93],
                    ['Wedding Ceremony',     251, 82],
                    ['Nature Landscape',     198, 65],
                    ['Fashion Editorial',    176, 58],
                    ['Street Candid',        163, 53],
                ];
                foreach($top as $i => $p): ?>
                <div style="display:flex;align-items:center;gap:12px;">
                    <span style="font-size:12px;color:#6b6b8a;width:16px;text-align:right;"><?php echo $i+1; ?></span>
                    <div style="width:44px;height:44px;border-radius:8px;background:#1a1a2e;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">📷</div>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">
                            <span style="font-size:12px;font-weight:500;color:#e2e0f0;"><?php echo $p[0]; ?></span>
                            <span style="font-size:12px;color:#ec4899;font-weight:600;">❤️ <?php echo $p[1]; ?></span>
                        </div>
                        <div class="progress-bg">
                            <div class="progress-fill" style="width:<?php echo $p[2]; ?>%;background:linear-gradient(90deg,#7c3aed,#ec4899);"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card rounded-xl p-5">
            <h3 class="font-semibold mb-4" style="color:#a855f7;">🎯 Engagement Breakdown</h3>
            <div style="display:flex;align-items:center;gap:24px;">
                <div style="width:150px;flex-shrink:0;">
                    <canvas id="engagementChart"></canvas>
                </div>
                <div style="flex:1;display:flex;flex-direction:column;gap:12px;">
                    <?php
                    $eng = [
                        ['Likes',    '2,340','#ec4899',58],
                        ['Comments',   '420','#a855f7',18],
                        ['Follows',    '380','#3b82f6',14],
                        ['Shares',     '260','#10b981',10],
                    ];
                    foreach($eng as $e): ?>
                    <div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                            <span style="display:flex;align-items:center;gap:6px;font-size:12px;color:#a0a0c0;">
                                <span style="width:8px;height:8px;border-radius:50%;background:<?php echo $e[2]; ?>;display:inline-block;"></span>
                                <?php echo $e[0]; ?>
                            </span>
                            <span style="font-size:12px;color:#e2e0f0;font-weight:600;"><?php echo $e[1]; ?></span>
                        </div>
                        <div class="progress-bg">
                            <div class="progress-fill" style="width:<?php echo $e[3]; ?>%;background:<?php echo $e[2]; ?>;"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>

    <!-- Row 4: Heatmap + Tips -->
    <div class="grid md:grid-cols-3 gap-5">

        <div class="card rounded-xl p-5 md:col-span-2">
            <h3 class="font-semibold mb-4" style="color:#a855f7;">🔥 Weekly Activity</h3>
            <?php
            $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
            $hrs  = ['6am','9am','12pm','3pm','6pm','9pm'];
            $heat = [[1,2,3,2,4,3],[2,4,5,3,4,2],[1,3,4,5,3,4],[2,3,4,3,5,4],[3,5,4,5,4,3],[4,5,5,4,5,5],[2,3,4,3,4,3]];
            ?>
            <div style="display:flex;flex-direction:column;gap:5px;">
                <div style="display:flex;gap:5px;margin-bottom:2px;padding-left:34px;">
                    <?php foreach($hrs as $h): ?><div style="flex:1;text-align:center;font-size:10px;color:#6b6b8a;"><?php echo $h; ?></div><?php endforeach; ?>
                </div>
                <?php foreach($days as $di => $day): ?>
                <div style="display:flex;align-items:center;gap:5px;">
                    <div style="width:28px;font-size:11px;color:#6b6b8a;flex-shrink:0;"><?php echo $day; ?></div>
                    <?php foreach($heat[$di] as $val):
                        $op = round($val/5,2); ?>
                    <div style="flex:1;height:24px;border-radius:5px;background:rgba(168,85,247,<?php echo $op; ?>);border:1px solid rgba(168,85,247,0.08);cursor:pointer;transition:transform .15s;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'"></div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
                <div style="display:flex;align-items:center;gap:5px;margin-top:6px;padding-left:34px;">
                    <span style="font-size:10px;color:#6b6b8a;">Less</span>
                    <?php foreach([0.1,0.3,0.5,0.7,1.0] as $o): ?>
                    <div style="width:14px;height:14px;border-radius:3px;background:rgba(168,85,247,<?php echo $o; ?>);"></div>
                    <?php endforeach; ?>
                    <span style="font-size:10px;color:#6b6b8a;">More</span>
                </div>
            </div>
        </div>

        <div class="card rounded-xl p-5">
            <h3 class="font-semibold mb-4" style="color:#a855f7;">💡 Growth Tips</h3>
            <div style="display:flex;flex-direction:column;gap:10px;">
                <?php
                $tips = [
                    ['📅','Post Consistently','Upload 3+ photos/week','rgba(124,58,237,0.1)','rgba(124,58,237,0.3)'],
                    ['❤️','Boost Engagement','Reply to comments fast','rgba(236,72,153,0.1)','rgba(236,72,153,0.3)'],
                    ['🔖','Use Descriptions','2x more engagement','rgba(59,130,246,0.1)','rgba(59,130,246,0.3)'],
                    ['🏷️','Add Categories','3x more discovery','rgba(16,185,129,0.1)','rgba(16,185,129,0.3)'],
                ];
                foreach($tips as $t): ?>
                <div style="background:<?php echo $t[3]; ?>;border:1px solid <?php echo $t[4]; ?>;border-radius:10px;padding:10px 12px;display:flex;gap:10px;align-items:flex-start;">
                    <span style="font-size:16px;flex-shrink:0;"><?php echo $t[0]; ?></span>
                    <div>
                        <div style="font-size:12px;font-weight:600;color:#e2e0f0;margin-bottom:2px;"><?php echo $t[1]; ?></div>
                        <div style="font-size:11px;color:#6b6b8a;"><?php echo $t[2]; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</div>

<script>
Chart.defaults.color = '#6b6b8a';
Chart.defaults.borderColor = 'rgba(255,255,255,0.04)';

const allLabels = {
    '7d':  ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
    '30d': ['W1','W2','W3','W4'],
    '6m':  ['Jan','Feb','Mar','Apr','May','Jun'],
    '1y':  ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
};
const allData = {
    bookings:  { '7d':[5,8,6,10,9,14,12],'30d':[18,25,30,47],'6m':[10,25,18,35,28,45],'1y':[8,12,15,20,18,25,22,30,28,35,40,45] },
    earnings:  { '7d':[2000,3500,2800,4200,3900,5500,4800],'30d':[8000,12000,15000,18000],'6m':[5000,9000,7000,12000,10000,15000],'1y':[4000,6000,8000,10000,9000,12000,11000,14000,13000,16000,18000,20000] },
    likes:     { '7d':[45,80,60,120,90,180,150],'30d':[180,280,340,520],'6m':[120,280,200,420,380,650],'1y':[80,150,200,320,280,420,390,520,480,600,720,840] },
    followers: { '7d':[10,18,14,25,20,35,30],'30d':[50,80,120,180],'6m':[40,90,70,150,120,200],'1y':[30,60,90,130,110,160,150,200,180,250,300,380] },
};

function grad(ctx, c1, c2) {
    const g = ctx.createLinearGradient(0,0,0,200);
    g.addColorStop(0, c1); g.addColorStop(1, c2); return g;
}

const lineOpts = (color, gradColor) => ({
    type:'line',
    options:{
        responsive:true,
        plugins:{ legend:{display:false} },
        scales:{
            x:{ grid:{color:'rgba(255,255,255,0.03)'}, ticks:{color:'#6b6b8a'} },
            y:{ grid:{color:'rgba(255,255,255,0.03)'}, ticks:{color:'#6b6b8a'}, beginAtZero:true }
        }
    }
});

// Booking
const bCtx = document.getElementById('bookingChart').getContext('2d');
const bookingChart = new Chart(bCtx, { type:'line', data:{
    labels: allLabels['6m'],
    datasets:[{ label:'Bookings', data:allData.bookings['6m'], borderColor:'#a855f7', backgroundColor:grad(bCtx,'rgba(168,85,247,0.3)','rgba(168,85,247,0)'), tension:0.4, fill:true, pointBackgroundColor:'#a855f7', pointRadius:4, pointHoverRadius:7, borderWidth:2 }]
}, options:{ responsive:true, plugins:{legend:{display:false}}, scales:{ x:{grid:{color:'rgba(255,255,255,0.03)'},ticks:{color:'#6b6b8a'}}, y:{grid:{color:'rgba(255,255,255,0.03)'},ticks:{color:'#6b6b8a'},beginAtZero:true} } }});

// Earnings
const eCtx = document.getElementById('earningChart').getContext('2d');
const earningChart = new Chart(eCtx, { type:'bar', data:{
    labels: allLabels['6m'],
    datasets:[{ label:'Earnings', data:allData.earnings['6m'], backgroundColor: allData.earnings['6m'].map(()=>{ const g=eCtx.createLinearGradient(0,0,0,200); g.addColorStop(0,'rgba(16,185,129,0.8)'); g.addColorStop(1,'rgba(16,185,129,0.1)'); return g; }), borderRadius:6, borderSkipped:false }]
}, options:{ responsive:true, plugins:{legend:{display:false}}, scales:{ x:{grid:{display:false},ticks:{color:'#6b6b8a'}}, y:{grid:{color:'rgba(255,255,255,0.03)'},ticks:{color:'#6b6b8a',callback:v=>'₹'+v/1000+'K'},beginAtZero:true} } }});

// Likes
const lCtx = document.getElementById('likesChart').getContext('2d');
const likesChart = new Chart(lCtx, { type:'line', data:{
    labels: allLabels['6m'],
    datasets:[{ label:'Likes', data:allData.likes['6m'], borderColor:'#ec4899', backgroundColor:grad(lCtx,'rgba(236,72,153,0.3)','rgba(236,72,153,0)'), tension:0.4, fill:true, pointBackgroundColor:'#ec4899', pointRadius:4, pointHoverRadius:7, borderWidth:2 }]
}, options:{ responsive:true, plugins:{legend:{display:false}}, scales:{ x:{grid:{color:'rgba(255,255,255,0.03)'},ticks:{color:'#6b6b8a'}}, y:{grid:{color:'rgba(255,255,255,0.03)'},ticks:{color:'#6b6b8a'},beginAtZero:true} } }});

// Followers
const fCtx = document.getElementById('followersChart').getContext('2d');
const followersChart = new Chart(fCtx, { type:'line', data:{
    labels: allLabels['6m'],
    datasets:[{ label:'Followers', data:allData.followers['6m'], borderColor:'#3b82f6', backgroundColor:grad(fCtx,'rgba(59,130,246,0.3)','rgba(59,130,246,0)'), tension:0.4, fill:true, pointBackgroundColor:'#3b82f6', pointRadius:4, pointHoverRadius:7, borderWidth:2 }]
}, options:{ responsive:true, plugins:{legend:{display:false}}, scales:{ x:{grid:{color:'rgba(255,255,255,0.03)'},ticks:{color:'#6b6b8a'}}, y:{grid:{color:'rgba(255,255,255,0.03)'},ticks:{color:'#6b6b8a'},beginAtZero:true} } }});

// Engagement Doughnut
new Chart(document.getElementById('engagementChart'), { type:'doughnut', data:{
    labels:['Likes','Comments','Follows','Shares'],
    datasets:[{ data:[58,18,14,10], backgroundColor:['#ec4899','#a855f7','#3b82f6','#10b981'], borderColor:'#0a0a0f', borderWidth:3, hoverOffset:8 }]
}, options:{ cutout:'72%', plugins:{ legend:{display:false} } }});

// Range switcher
function setRange(range, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const lbl = allLabels[range];
    [[bookingChart, allData.bookings[range]], [earningChart, allData.earnings[range]], [likesChart, allData.likes[range]], [followersChart, allData.followers[range]]].forEach(([chart, newData]) => {
        chart.data.labels = lbl;
        chart.data.datasets[0].data = newData;
        chart.update('active');
    });
}
</script>
</body>
</html>