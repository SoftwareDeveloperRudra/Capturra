<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/auth.php";
requireRole("photographer");
$name = $_SESSION['name'];
?>
<!doctype html>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Capturra - Photographer Settings</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
body { font-family: 'Inter', sans-serif; }
</style>
</head>

<body class="bg-gray-100 min-h-screen">

<!-- Top Navbar -->
<nav class="bg-white shadow-sm border-b">
<div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
<div class="flex items-center space-x-3">
<div class="w-9 h-9 bg-gradient-to-r from-purple-500 to-blue-500 rounded-lg flex items-center justify-center text-white font-bold">📸</div>
<div>
<h1 class="text-xl font-bold text-gray-800">Capturra</h1>
<p class="text-xs text-gray-500">Photographer Settings</p>
</div>
</div>
<a href="photographer_home.php" class="text-purple-600 font-medium hover:underline">
← Back to Dashboard
</a>
</div>
</nav>

<div class="max-w-5xl mx-auto py-10 px-6 space-y-10">

<!-- Profile Settings -->
<div class="bg-white rounded-xl shadow-sm p-8">
<h2 class="text-xl font-semibold mb-6">👤 Profile Information</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<label class="block text-sm font-medium mb-1">Full Name</label>
<input type="text" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
</div>

<div>
<label class="block text-sm font-medium mb-1">Username</label>
<input type="text" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
</div>

<div>
<label class="block text-sm font-medium mb-1">Email</label>
<input type="email" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
</div>

<div>
<label class="block text-sm font-medium mb-1">Phone</label>
<input type="text" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
</div>
</div>

<div class="mt-6">
<label class="block text-sm font-medium mb-1">Bio</label>
<textarea rows="4" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500"></textarea>
</div>

<button class="mt-6 bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
Save Changes
</button>
</div>

<!-- Password Settings -->
<div class="bg-white rounded-xl shadow-sm p-8">
<h2 class="text-xl font-semibold mb-6">🔐 Change Password</h2>

<div class="space-y-4">
<input type="password" placeholder="Current Password"
class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">

<input type="password" placeholder="New Password"
class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">

<input type="password" placeholder="Confirm New Password"
class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
</div>

<button class="mt-6 bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
Update Password
</button>
</div>

<!-- Notification Preferences -->
<div class="bg-white rounded-xl shadow-sm p-8">
<h2 class="text-xl font-semibold mb-6">🔔 Notification Preferences</h2>

<div class="space-y-4">

<label class="flex items-center justify-between">
<span>Email Notifications</span>
<input type="checkbox" class="w-5 h-5 text-purple-600">
</label>

<label class="flex items-center justify-between">
<span>New Likes Alerts</span>
<input type="checkbox" class="w-5 h-5 text-purple-600">
</label>

<label class="flex items-center justify-between">
<span>New Comments Alerts</span>
<input type="checkbox" class="w-5 h-5 text-purple-600">
</label>

<label class="flex items-center justify-between">
<span>New Follower Alerts</span>
<input type="checkbox" class="w-5 h-5 text-purple-600">
</label>

</div>

<button class="mt-6 bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
Save Preferences
</button>
</div>

<!-- Privacy Controls -->
<div class="bg-white rounded-xl shadow-sm p-8">
<h2 class="text-xl font-semibold mb-6">🛡 Privacy Controls</h2>

<div class="space-y-4">

<label class="flex items-center justify-between">
<span>Make Profile Private</span>
<input type="checkbox" class="w-5 h-5 text-purple-600">
</label>

<label class="flex items-center justify-between">
<span>Hide Follower Count</span>
<input type="checkbox" class="w-5 h-5 text-purple-600">
</label>

<label class="flex items-center justify-between">
<span>Allow Direct Messages</span>
<input type="checkbox" class="w-5 h-5 text-purple-600">
</label>

</div>

<button class="mt-6 bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
Update Privacy
</button>
</div>

<!-- Theme Preferences -->
<div class="bg-white rounded-xl shadow-sm p-8">
<h2 class="text-xl font-semibold mb-6">🎨 Appearance</h2>

<select class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
<option>Light Mode</option>
<option>Dark Mode</option>
<option>System Default</option>
</select>

<button class="mt-6 bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
Save Theme
</button>
</div>

<!-- Danger Zone -->
<div class="bg-white border border-red-200 rounded-xl shadow-sm p-8">
<h2 class="text-xl font-semibold text-red-600 mb-6">⚠ Danger Zone</h2>

<p class="text-sm text-gray-600 mb-4">
Deleting your account is permanent and cannot be undone.
</p>

<button class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
Delete Account
</button>
</div>

</div>

</body>
</html>