<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$isRootDir = !isset($_GET['dir']) || $_GET['dir'] === '.';

// Obtenemos el directorio actual de la URL, por defecto será uploads
$currentDir = isset($_GET['dir']) ? 'uploads/' . urldecode($_GET['dir']) : 'uploads/';

// Nos aeguramos que el directorio esté dentro de 'uploads'
if (strpos(realpath($currentDir), realpath('uploads')) !== 0) {
    die('Invalid directory.');
}


// Obtenemos los directorios y archivos
$items = scandir($currentDir);
$directories = [];
$files = [];

foreach ($items as $item) {
    if ($item !== '.' && $item !== '..') {
        if (is_dir($currentDir . '/' . $item)) {
            $directories[] = $item;
        } else {
            $files[] = $item;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Server</title>
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
                <a href="#" class="nav-link">Home</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php" role="button">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">File Server</span>
        </a>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Files</h1>
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
                                <h3 class="card-title">File List</h3>
                            </div>
                            <div class="card-body">
                                <form method="post" action="download.php">
                                    <input type="hidden" class="form-control role" id="role" value="<?= htmlspecialchars(isset($_SESSION['role']) ? $_SESSION['role'] : '') ?>" />
                                    <ul class="list-group">
                                        <?php if (!$isRootDir): ?>
                                            <li class="list-group-item"><a href="?dir=<?= urlencode(isset($_GET['dir']) ? dirname($_GET['dir']) : '') ?>">.. (ir al directorio principal)</a></li>
                                        <?php endif; ?>

                                        <?php foreach ($directories as $directory): ?>
                                            <li class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="folders[]" value="<?= urlencode(isset($_GET['dir']) ? $_GET['dir'] . '/' . $directory : $directory) ?>">
                                                    <label class="form-check-label">
                                                        <a href="?dir=<?= urlencode(isset($_GET['dir']) ? $_GET['dir'] . '/' . $directory : $directory) ?>">
                                                            <i class="fas fa-folder-open"></i> <?= htmlspecialchars($directory) ?>
                                                        </a>
                                                    </label>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>

                                        <?php foreach ($files as $file): ?>
                                            <li class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="files[]" value="<?= urlencode(isset($_GET['dir']) ? $_GET['dir'] . '/' . $file : $file) ?>">
                                                    <label class="form-check-label">
                                                        <i class="fas fa-file"></i> <?= htmlspecialchars($file) ?>
                                                    </label>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <button type="submit" class="btn btn-primary mt-3">Download Selected</button>
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
</body>
</html>
