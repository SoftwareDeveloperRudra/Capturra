<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/config/database.php";

$token = $_GET['token'] ?? '';

$res = mysqli_query($conn, "SELECT * FROM password_resets WHERE token='$token'");

if(mysqli_num_rows($res)==0){
    die("Invalid token");
}

$data = mysqli_fetch_assoc($res);

/* CHECK EXPIRY */
if(strtotime($data['expires_at']) < time()){
    die("Token expired");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>New Password</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#0f0f13] flex items-center justify-center h-screen text-white">

<div class="bg-[#16161f] p-8 rounded-xl border border-[#2a2a3e] w-80">

<h2 class="text-xl mb-4">Set New Password</h2>

<form action="update_password.php" method="POST">

<input type="hidden" name="token" value="<?php echo $token; ?>">

<input type="password" name="password" placeholder="New Password"
class="w-full p-2 mb-4 bg-black border border-gray-700 rounded" required>

<button class="w-full bg-purple-600 p-2 rounded">
Update Password
</button>

</form>

</div>

</body>
</html>