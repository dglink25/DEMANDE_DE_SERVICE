<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
// Vérifier si l’admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?action=login");
    exit;
}
?>

<div class="container mt-4">
     <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-primary fw-bold"> Paramètres du compte</h1>
    </div>
    <hr>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?action=updateParametres">
        <!-- Nom -->
        <div class="mb-3">
            <label for="nom" class="form-label">Nom complet</label>
            <input type="text" class="form-control" id="nom" name="nom" 
                   value="<?= htmlspecialchars($admin['nom']) ?>" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?= htmlspecialchars($admin['email']) ?>" required>
        </div>

        <!-- Ancien mot de passe -->
        <div class="mb-3">
            <label for="old_password" class="form-label">Mot de passe actuel (obligatoire pour toute modification)</label>
            <input type="password" class="form-control" id="old_password" name="old_password" required>
        </div>

        <!-- Nouveau mot de passe -->
        <div class="mb-3">
            <label for="new_password" class="form-label">Nouveau mot de passe</label>
            <input type="password" class="form-control" id="new_password" name="new_password">
        </div>

        <!-- Confirmation -->
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        </div>

        <!-- Rôle -->
        <div class="mb-3">
            <label class="form-label">Rôle</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($admin['role']) ?>" disabled>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
