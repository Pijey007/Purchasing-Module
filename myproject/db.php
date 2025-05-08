<?php
$conn = new mysqli("localhost", "root", "", "purchasing_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
