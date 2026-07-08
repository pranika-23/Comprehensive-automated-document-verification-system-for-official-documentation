
<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>

<h1>Welcome Admin</h1>
<hr>

<h2>Dashboard</h2>

<!-- Real form interface that passes data parameters to PHP -->
    <form action="upload.php" method="POST" enctype="multipart/form-data" style="margin-bottom: 15px;">
        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Select Document to Verify:</label>
        <input type="file" name="document" accept="image/*" required style="margin-bottom: 10px;">
        <br>
        <button type="submit" style="padding: 6px 12px; cursor: pointer;">Upload and Scan</button>
    </form>


<a href="status.php">Check Verification Status</a>
<br><br>

<a href="logout.php">Logout</a>

</body>
</html>