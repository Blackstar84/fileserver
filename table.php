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
    <style>
        .extra-columns {
            display: none;
        }
    </style>
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
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" id="expandColumnsBtn">
                                        <i class="fas fa-columns"></i> Show More Columns
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th class="extra-columns">Size</th>
                                        <th class="extra-columns">Date Modified</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // Example file data, replace with your actual data fetching logic
                                    $files = [
                                        ['name' => 'file1.txt', 'type' => 'Text File', 'size' => '1KB', 'date' => '2024-05-22'],
                                        ['name' => 'file2.jpg', 'type' => 'Image File', 'size' => '2MB', 'date' => '2024-05-21']
                                    ];
                                    foreach ($files as $file): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($file['name']) ?></td>
                                            <td><?= htmlspecialchars($file['type']) ?></td>
                                            <td class="extra-columns"><?= htmlspecialchars($file['size']) ?></td>
                                            <td class="extra-columns"><?= htmlspecialchars($file['date']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
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
    $(document).ready(function() {
        $('#expandColumnsBtn').click(function() {
            $('.extra-columns').toggle();
            var isVisible = $('.extra-columns').is(':visible');
            $(this).html(isVisible ? '<i class="fas fa-columns"></i> Show Less Columns' : '<i class="fas fa-columns"></i> Show More Columns');
        });
    });
</script>
</body>
</html>
