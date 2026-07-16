<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/auth.php";
requireRole("client");
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/config/database.php";

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$username = $_SESSION['username'];

// Try to get bio if column exists
$bio = '';
$colCheck = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'bio'");
if($colCheck && mysqli_num_rows($colCheck) > 0){
    $stmt = $conn->prepare("SELECT bio FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res && $res->num_rows > 0){
        $row = $res->fetch_assoc();
        $bio = $row['bio'];
    }
}

// Get user's uploads
$photos = [];
$pstmt = $conn->prepare("SELECT id, image FROM photos WHERE user_id = ? ORDER BY id DESC LIMIT 50");
$pstmt->bind_param("i", $user_id);
$pstmt->execute();
$pres = $pstmt->get_result();
if($pres){
    while($r = $pres->fetch_assoc()) $photos[] = $r;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Profile - Capturra</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="max-w-5xl mx-auto p-8">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold mb-1"><?php echo htmlspecialchars($name); ?></h1>
        <div class="text-sm text-gray-500">@<?php echo htmlspecialchars($username); ?></div>
      </div>
      <div class="space-x-2">
        <a href="client_home.php" class="text-sm text-gray-600 hover:underline">← Home</a>
        <a href="settings.php" class="text-sm text-purple-600 hover:underline">Settings</a>
      </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
      <div class="flex items-start space-x-6">
        <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center text-4xl">👤</div>
        <div class="flex-1">
          <div id="bioDisplay" class="text-sm text-gray-700" style="white-space:pre-wrap"><?php echo htmlspecialchars($bio ?: ""); ?></div>
          <textarea id="bioEditor" class="w-full mt-2 p-2 border border-gray-300 rounded hidden" rows="4"><?php echo htmlspecialchars($bio); ?></textarea>
          <div class="mt-3">
            <button id="editBioBtn" class="bg-purple-600 text-white px-4 py-2 rounded mr-2">Edit Bio</button>
            <button id="saveBioBtn" class="bg-green-600 text-white px-4 py-2 rounded mr-2 hidden">Save</button>
            <button id="cancelBioBtn" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hidden">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <h2 class="text-xl font-semibold mb-4">Uploads</h2>
    <?php if(count($photos) === 0): ?>
      <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-gray-600">You haven't uploaded any photos yet.</div>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php foreach($photos as $p):
            $img = '../' . $p['image'];
            $imgEsc = htmlspecialchars($img);
            $uploaded = 'Unknown';
            if(file_exists($img)){
                $uploaded = date('Y-m-d H:i', filemtime($img));
            }
        ?>
          <div class="bg-white rounded-lg overflow-hidden shadow-sm border border-gray-200">
            <img src="<?php echo $imgEsc; ?>" class="w-full" style="height:auto; object-fit:contain;" alt="photo">
            <div class="p-3 text-sm text-gray-600">Uploaded: <?php echo htmlspecialchars($uploaded); ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>

  <script>
    const editBtn = document.getElementById('editBioBtn');
    const saveBtn = document.getElementById('saveBioBtn');
    const cancelBtn = document.getElementById('cancelBioBtn');
    const bioDisplay = document.getElementById('bioDisplay');
    const bioEditor = document.getElementById('bioEditor');

    editBtn.addEventListener('click', () => {
      bioEditor.classList.remove('hidden');
      saveBtn.classList.remove('hidden');
      cancelBtn.classList.remove('hidden');
      editBtn.classList.add('hidden');
      bioDisplay.classList.add('hidden');
    });

    cancelBtn.addEventListener('click', () => {
      bioEditor.classList.add('hidden');
      saveBtn.classList.add('hidden');
      cancelBtn.classList.add('hidden');
      editBtn.classList.remove('hidden');
      bioDisplay.classList.remove('hidden');
    });

    saveBtn.addEventListener('click', () => {
      const bio = bioEditor.value;
      saveBtn.disabled = true;
      fetch('/Capturra/api/user/save_profile.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ bio })
      }).then(r => r.json()).then(data => {
        saveBtn.disabled = false;
        if(data && data.success){
          bioDisplay.textContent = bio;
          cancelBtn.click();
        } else {
          alert((data && data.message) || 'Failed to save bio');
        }
      }).catch(err => { saveBtn.disabled = false; alert('Server error'); });
    });
  </script>
</body>
</html>