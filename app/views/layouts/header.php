<?php
// Vérifier si l'admin est connecté
//session_start();
if (!isset($_SESSION['admin_id'])) {
    // Redirection vers la page de login si non connecté
    header("Location: index.php?action=login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - DGLINK</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?action=dashboard">DGLINK <?= htmlspecialchars($_SESSION['admin_role']) ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarAdmin">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <span class="nav-link text-white">Bienvenu, <?= htmlspecialchars($_SESSION['admin_nom']) ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=historique">Historique</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=parametres">Paramètres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="index.php?action=logout">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mt-4">
