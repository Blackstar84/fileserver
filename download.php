<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$filesToDownload = [];

// Function to recursively get all files in a directory
function getFilesInDirectory($dir) {
    $files = [];
    if (!is_dir($dir)) {
        // Directory does not exist
        echo "Directory does not exist: " . htmlspecialchars($dir) . "<br>";
        return $files;
    }
    foreach (scandir($dir) as $item) {
        if ($item === '.' || $item === '..') continue;
        $filePath = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($filePath)) {
            $files = array_merge($files, getFilesInDirectory($filePath));
        } else {
            $files[] = $filePath;
        }
    }
    return $files;
}

// Collect selected files
if (isset($_POST['files'])) {
    foreach ($_POST['files'] as $file) {
        $decodedFile = urldecode($file);
        $fullPath = realpath('uploads/' . $decodedFile);
        if ($fullPath && file_exists($fullPath)) {
            $filesToDownload[] = $fullPath;
        } else {
            echo "File does not exist: " . htmlspecialchars($decodedFile) . "<br>";
        }
    }
}

// Collect selected folders and their contents
if (isset($_POST['folders'])) {
    foreach ($_POST['folders'] as $folder) {
        $decodedFolder = urldecode($folder);
        $fullPath = realpath('uploads/' . $decodedFolder);
        if ($fullPath && is_dir($fullPath)) {
            $filesToDownload = array_merge($filesToDownload, getFilesInDirectory($fullPath));
        } else {
            echo "Folder does not exist: " . htmlspecialchars($decodedFolder) . "<br>";
        }
    }
}

// Create a ZIP file with the selected files
$zip = new ZipArchive();
$zipFileName = 'downloads.zip';

if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
    exit("Unable to open <$zipFileName>\n");
}

// Add files to the ZIP archive
foreach ($filesToDownload as $file) {
    if (file_exists($file)) {
        // Get the relative path to maintain directory structure
        $relativePath = substr($file, strlen(realpath('uploads/')) + 1);
        $zip->addFile($file, $relativePath);
    } else {
        echo "File not found: " . htmlspecialchars($file) . "<br>";
    }
}

$zip->close();

// Ensure the ZIP file was created
if (!file_exists($zipFileName)) {
    exit("Failed to create the ZIP file.\n");
}

// Set headers to download the ZIP file
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename=' . basename($zipFileName));
header('Content-Length: ' . filesize($zipFileName));

// Clear output buffer to prevent any extra content
ob_clean();
flush();

// Send the ZIP file to the browser
readfile($zipFileName);

// Delete the ZIP file after download
unlink($zipFileName);

exit;
?>
