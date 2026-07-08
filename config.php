<?php
// Database credentials
define('DB_SERVER', '127.0.0.1:3307');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); // Leave empty for default XAMPP
define('DB_NAME', 'document_db');

// Attempt to connect to MySQL database
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>