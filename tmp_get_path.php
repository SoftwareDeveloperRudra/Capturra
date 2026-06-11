<?php
require 'config/database.php';
$id = 17;
$res = mysqli_query($conn, 'SELECT image FROM photos WHERE id=' . intval($id));
if ($res && $row = mysqli_fetch_assoc($res)) {
    echo $row['image'];
} else {
    echo 'NOT FOUND';
}
