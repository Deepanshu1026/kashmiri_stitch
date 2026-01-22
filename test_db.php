<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/credentials.php';

echo "Attempting connection to " . DB_SERVER . "...<br>";

$start = microtime(true);
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$end = microtime(true);

if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
} else {
    echo "Connection successful! Took " . round($end - $start, 4) . " seconds.<br>";
    echo "Host info: " . $conn->host_info;
}
?>
