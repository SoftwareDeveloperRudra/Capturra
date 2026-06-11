<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();

/* DEFAULT VALUES */
if(!isset($_SESSION['name'])){
    $_SESSION['name'] = "Vidhisha Shinde";
    $_SESSION['bio']  = "Photographer 📸 | Story Teller";
}

/* SAVE PROFILE */
if(isset($_POST['save_profile'])){
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['bio']  = $_POST['bio'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Profile</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>
body{
    background: radial-gradient(circle at top,#1a0a2e,#0f0f13);
    color:white;
}

/* GLASS */
.glass{
    background: rgba(22,22,31,0.6);
    backdrop-filter: blur(18px);
    border:1px solid rgba(124,58,237,0.2);
    border-radius:20px;
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

/* GALLERY */
.gallery{ column-count:3; column-gap:16px; }
.card{ margin-bottom:16px; border-radius:12px; overflow:hidden; }
.card img{ width:100%; border-radius:12px; }

.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.9);
    display:none;
    justify-content:center;
    align-items:center;
}
</style>
</head>

<body>

<div class="max-w-6xl mx-auto p-6">

<!-- HEADER -->
<div class="glass p-6 flex gap-6 mb-8">

<img src="https://i.pravatar.cc/200" class="w-28 h-28 rounded-full border-4 border-purple-600">

<div>
<h1 class="text-2xl font-bold">
    <?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?>
</h1>

<p class="text-gray-400">
    @capturra_user
</p>

<p class="text-gray-300 mt-2">
    <?php echo !empty($_SESSION['bio']) 
        ? htmlspecialchars($_SESSION['bio']) 
        : 'No bio added yet'; ?>
</p>

<button onclick="openEdit()" class="mt-3 bg-purple-600 px-4 py-2 rounded">
Edit Profile
</button>

</div>
</div>

<!-- TABS -->
<div class="flex gap-3 mb-6">
<button class="tab active" onclick="showTab('gallery',this)">Gallery</button>
<button class="tab" onclick="showTab('about',this)">About</button>
</div>

<!-- GALLERY -->
<div id="gallery" class="tab-content">
<div class="gallery" id="galleryContainer"></div>
</div>

<!-- ABOUT -->
<div id="about" class="tab-content hidden">
<div class="glass p-6">
<h2 class="text-lg font-bold mb-2">About Me</h2>
<p>
Professional photographer passionate about storytelling and capturing emotions.
</p>
</div>
</div>

</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal">

    <form method="POST" class="glass p-6">
        <h2 class="mb-3">Edit Profile</h2>

        <input 
            type="text"
            name="name" 
            placeholder="Name"
            value="<?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>"
            class="w-full p-2 mb-2 bg-black text-white border border-gray-700 rounded"
        >

        <input 
            type="text"
            name="bio" 
            placeholder="Bio"
            value="<?php echo htmlspecialchars($_SESSION['bio'] ?? ''); ?>"
            class="w-full p-2 mb-2 bg-black text-white border border-gray-700 rounded"
        >

        <button 
            type="submit"
            class="w-full p-2 bg-white text-black rounded font-semibold hover:bg-gray-200 transition"
        >
            Save Changes
        </button>
    </form>

</div>
<button name="save_profile" class="bg-purple-600 px-4 py-2 rounded">
Save
</button>

</form>

</div>

<script>

/* LOAD IMAGES */
let container = document.getElementById("galleryContainer");

for(let i=0;i<12;i++){
container.innerHTML += `
<div class="card">
<img src="https://picsum.photos/400/${500+i}?random=${i}">
</div>`;
}

/* TAB FIX */
function showTab(tab,btn){

// hide all
document.querySelectorAll('.tab-content').forEach(t=>{
t.classList.add('hidden');
});

// show selected
document.getElementById(tab).classList.remove('hidden');

// remove active from all
document.querySelectorAll('.tab').forEach(t=>{
t.classList.remove('active');
});

// add active to clicked
btn.classList.add('active');
}

/* EDIT */
function openEdit(){
document.getElementById('editModal').style.display='flex';
}

</script>

</body>
</html>