<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM item_file WHERE id = $id");
}

header("Location: item_file.php");
exit();
?>
