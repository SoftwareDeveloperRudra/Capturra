<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/auth.php";
requireRole("client");
$name = $_SESSION['name'];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Settings - Capturra</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="max-w-3xl mx-auto p-8">
    <h1 class="text-2xl font-bold mb-4">Settings</h1>
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
      <p class="text-sm text-gray-600">Settings for <?php echo htmlspecialchars($name); ?>. Add account and preference controls here.</p>
      <div class="mt-6">
        <a href="client_home.php" class="text-sm text-purple-600 hover:underline">← Back to Home</a>
      </div>
    </div>
  </div>
</body>
</html>