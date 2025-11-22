<?php
$page_title = [
    'home' => 'Beranda - EduNext',
    'courses' => 'Kursus - EduNext', 
    'login' => 'Login - EduNext',
    'register' => 'Daftar - EduNext',
    'dashboard' => 'Dashboard - EduNext',
    'admin' => 'Admin - EduNext'
];

$current_page = $_GET['page'] ?? 'home';
$title = $page_title[$current_page] ?? 'EduNext';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="index.php" class="logo">EduNext</a>
            <nav class="nav">
                <a href="index.php" class="<?php echo $current_page == 'home' ? 'active' : ''; ?>">Beranda</a>
                <a href="index.php?page=courses" class="<?php echo $current_page == 'courses' ? 'active' : ''; ?>">Kursus</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <a href="index.php?page=admin" class="<?php echo $current_page == 'admin' ? 'active' : ''; ?>">Admin</a>
                    <?php else: ?>
                        <a href="index.php?page=dashboard" class="<?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
                    <?php endif; ?>
                    <a href="index.php?action=logout">Logout (<?php echo $_SESSION['username']; ?>)</a>
                <?php else: ?>
                    <a href="index.php?page=login" class="<?php echo $current_page == 'login' ? 'active' : ''; ?>">Login</a>
                    <a href="index.php?page=register" class="btn btn-primary">Daftar</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>