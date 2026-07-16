<?php
$title ="Nature Photograpgs"; // change per page
$folder = "Nature Photograpgs"; // change per page folder name
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $title; ?> - Capturra</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>
body {
    background: #0f0f13;
    color: #fff;
}

/* Masonry Layout */
.masonry {
    column-count: 4;
    column-gap: 16px;
}
@media(max-width:1024px){ .masonry{ column-count:3; } }
@media(max-width:768px){ .masonry{ column-count:2; } }
@media(max-width:500px){ .masonry{ column-count:1; } }

.card {
    break-inside: avoid;
    margin-bottom: 16px;
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    background: #16161f;
    border: 1px solid #2a2a3e;
}

.card img {
    width: 100%;
    display: block;
    cursor: pointer;
    transition: 0.3s;
}
.card img:hover {
    transform: scale(1.05);
}

/* Overlay */
.overlay {
    position: absolute;
    bottom: 0;
    width: 100%;
    background: linear-gradient(transparent, rgba(0,0,0,0.8));
    padding: 10px;
    display: flex;
    justify-content: space-between;
}

/* Buttons */
.btn {
    background: rgba(255,255,255,0.1);
    padding: 6px 10px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 12px;
}

/* MODAL */
.modal {
    position: fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background: rgba(0,0,0,0.9);
    display:none;
    justify-content:center;
    align-items:center;
    flex-direction: column;
    z-index:999;
}

.modal img {
    max-width:90%;
    max-height:80%;
}

.close {
    position:absolute;
    top:20px;
    right:30px;
    font-size:30px;
    cursor:pointer;
}

/* Comment Box */
.comment-box {
    background:#16161f;
    padding:10px;
    border-radius:10px;
    width:300px;
}
</style>
</head>

<body>

<div class="max-w-7xl mx-auto p-6">

<h1 class="text-3xl mb-6 text-purple-400"><?php echo $title; ?></h1>

<div class="masonry">

<?php
for($i=1;$i<=15;$i++){
$img = "https://picsum.photos/600/800?random=" . $i;
// ?>
<div class="card">

<img src="<?php echo $img; ?>" onclick="openModal(this.src, <?php echo $i; ?>)">

<div class="overlay">
    <span class="btn" onclick="like(<?php echo $i; ?>)">❤️ <span id="like<?php echo $i; ?>">0</span></span>
    <span class="btn" onclick="openComments(<?php echo $i; ?>)">💬</span>
    <a href="<?php echo $img; ?>" download class="btn">📥</a>
</div>

<div class="absolute top-2 left-2 text-xs bg-black/60 px-2 py-1 rounded">
👁 <span id="view<?php echo $i; ?>">0</span>
</div>

</div>
<?php } ?>

</div>
</div>

<!-- MODAL -->
<div class="modal" id="modal">
<span class="close" onclick="closeModal()">×</span>
<img id="modalImg">
</div>

<!-- COMMENT POPUP -->
<div id="commentPopup" class="modal">
<div class="comment-box">
<h3 class="mb-2">Comments</h3>
<textarea id="commentText" class="w-full p-2 bg-black mb-2"></textarea>
<button onclick="addComment()" class="bg-purple-600 px-3 py-1 rounded">Post</button>
<div id="commentList" class="mt-2 text-sm"></div>
</div>
</div>

<script>

// LIKE SYSTEM
function like(id){
let count = localStorage.getItem("like"+id) || 0;
count++;
localStorage.setItem("like"+id, count);
document.getElementById("like"+id).innerText = count;
}

// LOAD LIKES + VIEWS
window.onload = () => {
for(let i=1;i<=15;i++){
document.getElementById("like"+i).innerText = localStorage.getItem("like"+i) || 0;

let v = localStorage.getItem("view"+i) || 0;
document.getElementById("view"+i).innerText = v;
}
}

// MODAL
function openModal(src,id){
document.getElementById("modal").style.display="flex";
document.getElementById("modalImg").src=src;

// view count
let v = localStorage.getItem("view"+id) || 0;
v++;
localStorage.setItem("view"+id, v);
document.getElementById("view"+id).innerText = v;
}

function closeModal(){
document.getElementById("modal").style.display="none";
}

// COMMENTS
let currentId = null;

function openComments(id){
currentId = id;
document.getElementById("commentPopup").style.display="flex";

let comments = JSON.parse(localStorage.getItem("comments"+id)) || [];
let html = "";
comments.forEach(c => html += "<p>"+c+"</p>");
document.getElementById("commentList").innerHTML = html;
}

function addComment(){
let text = document.getElementById("commentText").value;

let comments = JSON.parse(localStorage.getItem("comments"+currentId)) || [];
comments.push(text);
localStorage.setItem("comments"+currentId, JSON.stringify(comments));

openComments(currentId);
document.getElementById("commentText").value="";
}

</script>

</body>
</html>