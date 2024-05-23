<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Check if the user has the required permissions
/* if (!isset($_SESSION['role']) || !in_array('uploader', explode(',', $_SESSION['role']))) {
    $_SESSION['error_message'] = "Acceso Denegado. Solo los usuarios con permiso pueden acceder a esta página.";
    header("Location: 403.php");
    exit;
} */

$uploadDir = 'uploads/';
$targetDir = isset($_POST['targetDir']) ? $_POST['targetDir'] : '';

if ($targetDir && is_dir($uploadDir . $targetDir)) {
    $uploadDir .= $targetDir . '/';
} elseif ($targetDir !== '') {
    $_SESSION['error_message'] = "Directorio de destino no válido.";
    header("Location: upload.php");
    exit;
}

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

function createDirectories($filePath) {
    $dirs = explode('/', dirname($filePath));
    $path = '';
    foreach ($dirs as $dir) {
        $path .= $dir . '/';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }
}


// Manejar las cargas de archivos
foreach ($_FILES['files']['name'] as $key => $name) {
    if ($_FILES['files']['error'][$key] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['files']['tmp_name'][$key];
        $relativePath = $_FILES['files']['full_path'][$key];  // Usando full_path para obtener el webkitRelativePath
        $filePath = $uploadDir . $relativePath;
        createDirectories($filePath);
        move_uploaded_file($tmpName, $filePath);
    }
}

$_SESSION['success_message'] = "Archivos subidos correctamente.";
header("Location: upload.php");
exit;
?>
