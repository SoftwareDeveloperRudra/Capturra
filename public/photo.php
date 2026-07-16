<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/capturra/includes/auth.php');
requireRole("client");

include("../config/database.php");

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$photo_id = intval($_GET['id']);

// FETCH PHOTO + USER
$sql = "SELECT photos.*, users.username 
        FROM photos 
        JOIN users ON photos.user_id = users.id 
        WHERE photos.id = $photo_id";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Photo not found");
}

$photo = mysqli_fetch_assoc($result);

// Normalize photo path
$photo_filename = basename(str_replace('\\', '/', $photo['image']));
$img_src = "/Capturra/uploads/" . $photo_filename;

// FETCH COMMENTS
$comment_sql = "SELECT comments.*, users.username 
                FROM comments 
                JOIN users ON comments.user_id = users.id 
                WHERE comments.photo_id = $photo_id 
                ORDER BY comments.created_at DESC";

$comments = mysqli_query($conn, $comment_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Photo View</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-5xl mx-auto mt-10 bg-white shadow-lg rounded-xl overflow-hidden">

    <!-- IMAGE -->
    <div class="w-full bg-black flex justify-center">
        <img src="<?php echo htmlspecialchars($img_src); ?>"
             alt="Photo by <?php echo htmlspecialchars($photo['username']); ?>"
             class="max-h-[500px] object-contain">
    </div>

    <!-- DETAILS -->
    <div class="p-6">

        <!-- USER -->
        <h2 class="text-xl font-bold mb-2">
            <?php echo htmlspecialchars($photo['username']); ?>
        </h2>

        <!-- ACTIONS -->
        <div class="flex gap-6 mb-4">
            <button class="text-red-500 text-lg">❤️ Like</button>
            <button class="text-blue-500 text-lg">💬 Comment</button>
        </div>

        <!-- ADD COMMENT -->
        <form method="POST" action="add_comment.php" class="mb-6">
            <input type="hidden" name="photo_id" value="<?php echo $photo_id; ?>">
            
            <input type="text" name="comment" placeholder="Add a comment..."
                   class="w-full border p-2 rounded-lg mb-2" required>
            
            <button type="submit" 
                    class="bg-purple-600 text-white px-4 py-2 rounded-lg">
                Post Comment
            </button>
        </form>

        <!-- COMMENTS -->
        <div>
            <h3 class="font-semibold mb-3">Comments</h3>

            <?php while($row = mysqli_fetch_assoc($comments)) { ?>
                <div class="border-b py-2">
                    <span class="font-semibold">
                        <?php echo htmlspecialchars($row['username']); ?>:
                    </span>
                    <?php echo htmlspecialchars($row['comment']); ?>
                </div>
            <?php } ?>

        </div>

    </div>

</div>

</body>
</html>
