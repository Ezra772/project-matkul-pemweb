<?php
session_start();
include 'config/database.php';

// Simple routing
$page = $_GET['page'] ?? 'home';

// Handle logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Simple pages mapping - TAMBAH HALAMAN ADMIN
$pages = [
    'home' => 'views/home.php',
    'courses' => 'views/courses.php', 
    'course_detail' => 'views/course_detail.php',
    'login' => 'views/login.php',
    'register' => 'views/register.php',
    'dashboard' => 'views/dashboard.php',
    'admin' => 'views/admin.php',
    'admin_users' => 'views/admin_users.php',      // TAMBAH INI
    'admin_courses' => 'views/admin_courses.php',   // TAMBAH INI
    'admin_tutors' => 'views/admin_tutors.php',      // TAMBAH INI
    'admin_course_form' => 'views/admin_course_form.php',  // TAMBAH INI
    'admin_user_form' => 'views/admin_user_form.php',
    'admin_tutor_form' => 'views/admin_tutor_form.php'
];

$page_file = $pages[$page] ?? 'views/404.php';

// Include header
include 'views/partials/header.php';

// Include page content
include $page_file;

// Include footer  
include 'views/partials/footer.php';
?>