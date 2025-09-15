<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start(); // obligatoire pour toutes les pages

require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/app/Controllers/FormController.php";

// Création du contrôleur admin avec PDO
$controller = new AdminController($pdo);

// Récupération de l'action depuis l'URL
$action = $_GET['action'] ?? 'login';

// === ROUTAGE PERSONNALISÉ POUR LE FORMULAIRE CLIENT ===
if ($action === "requestForm") {
    include __DIR__ . "/app/views/form/requestForm.php";
    exit;
} elseif ($action === "submitRequest") {
    // Ici on appelle le FormController pour traiter l'envoi
    $formController = new AdminController($pdo);
    $formController->submitRequest();
    exit;
}

// === ROUTAGE CLASSIQUE ADMIN ===
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    echo "Action inconnue : " . htmlspecialchars($action);
}
