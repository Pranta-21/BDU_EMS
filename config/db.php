<?php
$host = 'lmsserver.mysql.database.azure.com';
$db = 'exam_system';
$user = 'pranta';
$pass = 'pranta@2000';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
