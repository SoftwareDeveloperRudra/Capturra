<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Bookings</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>
body{
    background: radial-gradient(circle at top,#1a0a2e,#0f0f13);
    color:white;
    font-family:'Inter',sans-serif;
}

/* HEADER GLOW */
.glow{
    background: linear-gradient(90deg,#7c3aed,#c084fc);
    -webkit-background-clip:text;
    color:transparent;
}

/* GLASS */
.glass{
    background: rgba(22,22,31,0.6);
    backdrop-filter: blur(18px);
    border:1px solid rgba(124,58,237,0.2);
    border-radius:16px;
}

/* STATS */
.stat{
    padding:20px;
    transition:.3s;
}
.stat:hover{
    transform:translateY(-5px);
    box-shadow:0 10px 30px rgba(124,58,237,0.3);
}

/* CARD */
.card{
    background: rgba(22,22,31,0.7);
    backdrop-filter: blur(16px);
    border:1px solid rgba(124,58,237,0.2);
    border-radius:18px;
    transition:.35s;
    transform-style: preserve-3d;
}
.card:hover{
    transform: rotateX(3deg) rotateY(-3deg) scale(1.02);
    box-shadow:0 20px 50px rgba(124,58,237,0.3);
}

/* IMG */
.img{
    width:120px;
    height:90px;
    border-radius:12px;
    object-fit:cover;
}

/* BADGE */
.badge{
    padding:5px 12px;
    border-radius:20px;
    font-size:12px;
}
.confirmed{background:#0f2e1c;color:#4ade80;}
.pending{background:#2a2a3e;color:#facc15;}
.cancelled{background:#3a1e1e;color:#f87171;}

/* BUTTON */
.btn{
    background:linear-gradient(135deg,#7c3aed,#5b21b6);
    padding:6px 14px;
    border-radius:8px;
    font-size:12px;
    transition:.3s;
}
.btn:hover{
    transform:scale(1.05);
}

/* TAB */
.tab{
    padding:8px 18px;
    border-radius:30px;
    border:1px solid #2a2a3e;
    cursor:pointer;
}
.tab.active{
    background:linear-gradient(135deg,#7c3aed,#5b21b6);
}

/* SEARCH */
.search{
    background:#16161f;
    border:1px solid #2a2a3e;
    border-radius:10px;
    padding:10px 14px;
    width:100%;
    outline:none;
}

/* NOTIFICATION */
.bell{
    position:relative;
    cursor:pointer;
}
.dot{
    position:absolute;
    top:0;
    right:0;
    width:8px;
    height:8px;
    background:#f87171;
    border-radius:50%;
}
</style>
</head>

<body>

<div class="max-w-6xl mx-auto p-6">

<!-- TOP BAR -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-4xl font-bold glow">My Bookings</h1>
        <p class="text-gray-400">All your shoots in one place</p>
    </div>

    <div class="flex items-center gap-4">
        <div class="bell text-xl">🔔<span class="dot"></span></div>
        <img src="https://i.pravatar.cc/40" class="rounded-full">
    </div>
</div>

<!-- STATS -->
<div class="grid md:grid-cols-3 gap-4 mb-8">

<div class="glass stat">
<h2 class="text-2xl font-bold">12</h2>
<p class="text-gray-400">Total Bookings</p>
</div>

<div class="glass stat">
<h2 class="text-2xl font-bold text-green-400">7</h2>
<p class="text-gray-400">Completed</p>
</div>

<div class="glass stat">
<h2 class="text-2xl font-bold text-yellow-400">3</h2>
<p class="text-gray-400">Pending</p>
</div>

</div>

<!-- SEARCH -->
<input type="text" id="search" placeholder="Search bookings..." class="search mb-6">

<!-- TABS -->
<div class="flex gap-3 mb-8">
<button class="tab active" onclick="filterStatus('all')">All</button>
<button class="tab" onclick="filterStatus('confirmed')">Confirmed</button>
<button class="tab" onclick="filterStatus('pending')">Pending</button>
<button class="tab" onclick="filterStatus('cancelled')">Cancelled</button>
</div>

<!-- BOOKINGS GRID -->
<div id="bookings" class="grid md:grid-cols-2 gap-6">

<!-- CARD -->
<div class="card booking confirmed p-4 flex justify-between items-center">
<div class="flex gap-4 items-center">
<img src="https://source.unsplash.com/300x200/?wedding" class="img">
<div>
<h2 class="font-semibold">Wedding Shoot</h2>
<p class="text-sm text-gray-400">25 May • 4PM</p>
</div>
</div>
<div class="text-right">
<span class="badge confirmed">Confirmed</span>
<button class="block text-red-400 text-xs mt-2" onclick="cancelBooking(this)">Cancel</button>
</div>
</div>

<!-- CARD -->
<div class="card booking pending p-4 flex justify-between items-center">
<div class="flex gap-4 items-center">
<img src="https://source.unsplash.com/300x200/?baby" class="img">
<div>
<h2 class="font-semibold">Baby Shoot</h2>
<p class="text-sm text-gray-400">10 June</p>
</div>
</div>
<div>
<span class="badge pending">Pending</span>
</div>
</div>

</div>

</div>

<script>

// SEARCH
document.getElementById("search").addEventListener("keyup", function(){
let value = this.value.toLowerCase();
document.querySelectorAll(".booking").forEach(card=>{
card.style.display = card.innerText.toLowerCase().includes(value) ? "flex":"none";
});
});

// FILTER
function filterStatus(status){
document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
event.target.classList.add('active');

document.querySelectorAll('.booking').forEach(card=>{
if(status==='all'){
card.style.display='flex';
}else{
card.style.display = card.classList.contains(status)?'flex':'none';
}
});
}

// CANCEL
function cancelBooking(btn){
let card = btn.closest('.booking');
card.classList.remove('confirmed');
card.classList.add('cancelled');

let badge = card.querySelector('.badge');
badge.innerText="Cancelled";
badge.className="badge cancelled";

btn.remove();
}

</script>

</body>
</html>