<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/auth.php";
// Sirf logged in users hi dekh sakein
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include("../config/database.php");

$user_id = $_SESSION['user_id'];

// Database se notifications fetch karein (Agar table banayi hai toh)
// Agar abhi table nahi hai, toh hum 'Sample' notifications dikhayenge niche.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Notifications | Capturra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #0f0f13; color: #e2e0f0; font-family: 'Inter', sans-serif; }
        .notif-card { 
            background: #16161f; 
            border: 1px solid #2a2a3e; 
            transition: all 0.3s ease;
        }
        .notif-card:hover { border-color: #7c3aed; background: #1c1c27; }
        .unread-dot { width: 8px; height: 8px; background: #7c3aed; border-radius: 50%; }
    </style>
</head>
<body class="p-6 md:p-12">

    <div class="max-w-3xl mx-auto">
        <a href="javascript:history.back()" class="text-sm text-purple-400 hover:text-purple-300 mb-6 inline-block">← Back to Dashboard</a>

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-white">Notifications</h1>
            <button class="text-xs bg-[#1e1e2e] hover:bg-[#2a2a3e] px-4 py-2 rounded-lg text-slate-400">Mark all as read</button>
        </div>

        <div class="space-y-4">
            
            <div class="notif-card p-5 rounded-xl flex items-center justify-between">
                <div class="flex gap-4 items-center">
                    <div class="w-12 h-12 rounded-full bg-purple-500/10 flex items-center justify-center text-xl">❤️</div>
                    <div>
                        <p class="text-white"><strong>Rahul Sharma</strong> liked your photo "Sunset over Himalayas"</p>
                        <p class="text-xs text-slate-500 mt-1">10 minutes ago</p>
                    </div>
                </div>
                <div class="unread-dot"></div>
            </div>

            <div class="notif-card p-5 rounded-xl flex items-center justify-between">
                <div class="flex gap-4 items-center">
                    <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center text-xl">💬</div>
                    <div>
                        <p class="text-white"><strong>Sneha Kapoor</strong> commented: "Amazing shot! What camera?"</p>
                        <p class="text-xs text-slate-500 mt-1">2 hours ago</p>
                    </div>
                </div>
            </div>

            <div class="notif-card p-5 rounded-xl flex items-center justify-between opacity-60">
                <div class="flex gap-4 items-center">
                    <div class="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center text-xl">📸</div>
                    <div>
                        <p class="text-white">Your photo was featured in <strong>Trending</strong></p>
                        <p class="text-xs text-slate-500 mt-1">Yesterday</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="hidden text-center py-20">
            <div class="text-6xl mb-4">🔔</div>
            <p class="text-slate-500">All caught up! No new notifications.</p>
        </div>
    </div>

</body>
</html>