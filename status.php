<?php
// status.php
require_once 'config.php';

$query = "SELECT * FROM verifications ORDER BY id DESC";
$result = mysqli_query($link, $query);
if (!$result) {
    die("Database Query Failed: " . mysqli_error($link));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verification Status</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f9f9f9; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { color: #0066cc; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <h2>Your Uploaded Documents</h2>
    <p><a href="dashboard.php">Back to Dashboard</a> | <a href="dashboard.php">Upload Another Document</a></p>

    <table>
        <thead>
            <tr>
                <th>File Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <!-- Displays the unique filename matching the filesystem -->
                        <td><?php echo htmlspecialchars($row['file_name']); ?></td>
                        <td><strong><?php echo htmlspecialchars($row['status']); ?></strong></td>
                        <td>
                            <!-- Perfectly tracks and routes straight to the real physical file -->
                            <a href="uploads/<?php echo urlencode($row['file_name']); ?>" target="_blank">View File</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No documents uploaded yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>