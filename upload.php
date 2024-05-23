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

// Function to get subdirectories within the 'uploads' directory
function getSubdirectories($dir) {
    $subdirs = [];
    foreach (scandir($dir) as $item) {
        if ($item === '.' || $item === '..') continue;
        if (is_dir($dir . DIRECTORY_SEPARATOR . $item)) {
            $subdirs[] = $item;
        }
    }
    return $subdirs;
}

$uploadDir = 'uploads/';
$subdirectories = getSubdirectories($uploadDir);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Archivos</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index.php" class="nav-link">Inicio</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php" role="button">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">Servidor de Archivos</span>
        </a>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Subir Archivos</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Seleccione Archivos o Carpetas para Subir</h3>
                            </div>
                            <div class="card-body">
                                <form id="uploadForm" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="targetDir">Directorio de Destino</label>
                                        <select id="targetDir" name="targetDir" class="form-control">
                                            <option value="">uploads (root)</option>
                                            <?php foreach ($subdirectories as $subdir): ?>
                                                <option value="<?= htmlspecialchars($subdir) ?>"><?= htmlspecialchars($subdir) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="files">Archivos y Carpetas</label>
                                        <input type="file" id="files" name="files[]" class="form-control" multiple webkitdirectory directory>
                                    </div>
                                    <button type="button" class="btn btn-primary mt-3" onclick="uploadFiles()">Subir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AdminLTE JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function uploadFiles() {
    var form = document.getElementById('uploadForm');
    var formData = new FormData(form);

    var filesInput = document.getElementById('files');

    // Append files with relative paths
    for (var i = 0; i < filesInput.files.length; i++) {
        formData.append('files[]', filesInput.files[i], filesInput.files[i].webkitRelativePath);
    }

    $.ajax({
        url: 'upload_handler.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            alert('Archivos subidos correctamente.');
            console.log(response);
        },
        error: function(response) {
            alert('Hubo un error al subir los archivos.');
            console.log(response);
        }
    });
}
</script>
</body>
</html>
