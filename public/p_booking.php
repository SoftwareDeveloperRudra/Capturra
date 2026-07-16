<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart(); ?>

<!DOCTYPE html>
<html>
<head>
<title>Bookings (Photographer)</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
body{
background: linear-gradient(135deg,#0f0f13,#1a0a2e);
color:white;
font-family:'Inter';
}

.card{
background:#16161f;
border:1px solid #2a2a3e;
border-radius:16px;
padding:18px;
transition:.3s;
}
.card:hover{
border-color:#7c3aed;
}

.badge{
padding:4px 10px;
border-radius:20px;
font-size:12px;
}
.confirmed{background:#1e3a2a;color:#4ade80;}
.pending{background:#2a2a3e;color:#facc15;}

.img{
width:120px;
height:90px;
object-fit:cover;
border-radius:10px;
}
</style>
</head>

<body>

<div class="max-w-5xl mx-auto p-6">

<h1 class="text-3xl mb-6">📸 Client Bookings</h1>

<div class="space-y-4">

<!-- CARD -->
<div class="card booking flex justify-between items-center">

<div class="flex gap-4">
<img src="https://source.unsplash.com/200x150/?couple" class="img">

<div>
<h2 class="font-semibold">Couple Shoot</h2>
<p class="text-sm text-gray-400">22 May • 3PM</p>
</div>
</div>

<div class="text-right">
<p class="text-sm">Client: Ananya</p>
<span class="badge pending">Pending</span>

<div class="mt-2 space-x-2">
<button onclick="accept(this)" class="text-green-400 text-xs">Accept</button>
<button onclick="reject(this)" class="text-red-400 text-xs">Reject</button>
</div>

</div>

</div>

</div>

</div>

<script>

function accept(btn){
let card = btn.closest('.booking');
let badge = card.querySelector('.badge');
badge.innerText="Confirmed";
badge.className="badge confirmed";
btn.parentElement.remove();
}

function reject(btn){
let card = btn.closest('.booking');
card.remove();
}

</script>

</body>
</html>