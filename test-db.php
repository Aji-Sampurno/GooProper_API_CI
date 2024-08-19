<?php
$servername = "5.181.217.105";
$username = "root";
$password = "b4rJnDY2PEczek4ta2OicJbIHKX6h5ZhWr6HVjHVNHeLQOHC0cIJ5gmzul5neqZs";
$dbname = "gooprope_android";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
