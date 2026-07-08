<?php
session_start();
require_once "config.php";

// Check if the user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doc_id = intval($_POST["document_id"]);
    $new_status = $_POST["new_status"];

    // Validate the incoming status string to prevent injection or invalid entries
    if (in_array($new_status, ['Pending', 'Verified', 'Duplicate'])) {
        $sql = "UPDATE documents SET status = ? WHERE id = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $new_status, $doc_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    
    // Send the user directly back to the status page to view their change
    header("Location: status.php");
    exit;
}
?>