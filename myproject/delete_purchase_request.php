<?php
include 'db.php';

$id = intval($_GET['id']);

// Optionally remove uploaded file
$result = $conn->query("SELECT supporting_document FROM purchase_requests WHERE id = $id");
if ($row = $result->fetch_assoc()) {
    if (!empty($row['supporting_document']) && file_exists($row['supporting_document'])) {
        unlink($row['supporting_document']);
    }
}

$conn->query("DELETE FROM purchase_requests WHERE id = $id");
header("Location: purchase_request.php");
exit;
?>
