<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Para verificar los errores en el servidor, quitar esto al poner en producción
error_reporting(E_ALL);
ini_set('display_errors', 1);

$filesToDownload = [];

// Función para obtener los archivos del directorio de manera recursiva
function getFilesInDirectory($dir) {
    $files = [];
    if (!is_dir($dir)) {        
        // Mensaje si el directorio no existe
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


// Obtenemos los archivos seleccionados
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


// Obtenemos las carpetas seleccionadas y sus contenidos
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


// Creamos un archivo ZIP con los archivos seleccionados
$zip = new ZipArchive();
$zipFileName = 'downloads.zip';

if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
    exit("Unable to open <$zipFileName>\n");
}


// Agregamos los archivos al ZIP
foreach ($filesToDownload as $file) {
    if (file_exists($file)) {
        // Obtenemos la ruta relativa para mantener la estructura del directorio
        $relativePath = substr($file, strlen(realpath('uploads/')) + 1);
        $zip->addFile($file, $relativePath);
    } else {
        echo "File not found: " . htmlspecialchars($file) . "<br>";
    }
}

$zip->close();

// Verificamos que el archivo ZIP haya sido creado 
if (!file_exists($zipFileName)) {
    exit("Failed to create the ZIP file.\n");
}

// Ponemos el header a la descarga del archivo ZIP
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename=' . basename($zipFileName));
header('Content-Length: ' . filesize($zipFileName));

// Limpiamos el buffer del output para prevenir cualquier contenido extra
ob_clean();
flush();

// Enviamos el archivo ZIP al navegador
readfile($zipFileName);

// Borramos el archivo ZIP después de descargarlo
unlink($zipFileName);

exit;
?>
