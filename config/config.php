<?php
// config/config.php

$host = "localhost";
$dbname = "dglink_app";
$username = "root";   // change si nécessaire
$password = "";       // change si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur connexion DB : " . $e->getMessage());
}
?>
