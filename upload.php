<?php
// upload.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document'])) {
    $file = $_FILES['document'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Upload failed with error code: " . $file['error']);
    }

    $original_name = basename($file['name']);
    $file_ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

    // Create a uniform unique filename with a timestamp to prevent overwriting
    $timestamp = time();
    $clean_name = preg_replace("/[^a-zA-Z0-9._-]/", "_", pathinfo($original_name, PATHINFO_FILENAME));
    $unique_filename = $timestamp . "_" . $clean_name . "." . $file_ext;
    
    $target_dir = "uploads/";
    $target_file_path = $target_dir . $unique_filename;

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], $target_file_path)) {
        $file_hash = md5_file($target_file_path);
        $extracted_text = "";
        $status = "Pending";

        // Optional OCR processing for images
        if (in_array($file_ext, ['jpg', 'jpeg', 'png'])) {
            $output_base = $target_dir . $timestamp . "_ocr";
            $command = "\"C:\\Program Files\\Tesseract-OCR\\tesseract.exe\" " . escapeshellarg($target_file_path) . " " . escapeshellarg($output_base);
            exec($command);
            
            $ocr_file = $output_base . ".txt";
            if (file_exists($ocr_file)) {
                $extracted_text = trim(file_get_contents($ocr_file));
                unlink($ocr_file); 
            }
        }

        if (stripos($extracted_text, 'invoice') !== false) {
            $status = "Verified";
        }

        // INSERT THE EXACT FILENAME SAVED ON DISK INTO THE DATABASE
        $stmt = $link->prepare("INSERT INTO verifications (file_name, file_hash, extracted_text, status) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            die("Database Prepare Error: " . $link->error);
        }

        $stmt->bind_param("ssss", $unique_filename, $file_hash, $extracted_text, $status);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: status.php");
            exit();
        } else {
            die("Database Execution Error: " . $stmt->error);
        }
    } else {
        die("Error: Failed to save the file to the uploads directory.");
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>