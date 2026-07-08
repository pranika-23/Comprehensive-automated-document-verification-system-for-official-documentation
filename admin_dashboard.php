<?php
session_start();
require_once "config.php";

// Simple check: For now, we assume if they logged in as 'admin', they can access this page.
if(!isset($_SESSION["loggedin"]) || $_SESSION["username"] !== 'admin'){
    header("location: login.php");
    exit;
}

// Handle Status Updates
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["document_id"]) && isset($_POST["new_status"])){
    $update_sql = "UPDATE documents SET status = ? WHERE id = ?";
    if($stmt = mysqli_prepare($link, $update_sql)){
        mysqli_stmt_bind_param($stmt, "si", $_POST["new_status"], $_POST["document_id"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Fetch ALL uploaded documents across the entire system
$sql = "SELECT d.id, u.username, d.file_name, d.file_path, d.status, d.uploaded_at 
        FROM documents d 
        JOIN users u ON d.user_id = u.id 
        ORDER BY d.uploaded_at DESC";

$result = mysqli_query($link, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Verification</title>
    <!-- Links to your new external style sheet -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- The modern container card wrapper -->
    <div class="container">
        <h2>Admin Document Verification Panel</h2>
        <p>Logged in as: <strong>Admin</strong> | <a href="login.php">Logout</a></p>
        <hr>

        <table>
            <tr>
                <th>User</th>
                <th>File Name</th>
                <th>Uploaded At</th>
                <th>Current Status</th>
                <th>Action / Update Status</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">View File</a></td>
                    <td><?php echo $row['uploaded_at']; ?></td>
                    <td>
                        <!-- Dynamic status styling badges from style.css -->
                        <?php 
                        $status = htmlspecialchars($row['status']); 
                        $badge_class = 'badge-pending';
                        if ($status === 'Approved') {
                            $badge_class = 'badge-approved';
                        } elseif ($status === 'Rejected') {
                            $badge_class = 'badge-rejected';
                        }
                        ?>
                        <span class="badge <?php echo $badge_class; ?>"><?php echo $status; ?></span>
                    </td>
                    <td>
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="document_id" value="<?php echo $row['id']; ?>">
                            <select name="new_status" style="width: auto; margin-bottom: 0; padding: 0.4rem;">
                                <option value="Pending" <?php if($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Approved" <?php if($row['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
                                <option value="Rejected" <?php if($row['status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
                            </select>
                            <input type="submit" value="Update" style="padding: 0.4rem 1rem;">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>