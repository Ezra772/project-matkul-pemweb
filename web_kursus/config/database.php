<?php
// Simple database connection
$host = 'localhost';
$user = 'root'; 
$pass = '';
$dbname = 'db_kursus';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>