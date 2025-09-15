<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin - DGLINK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('public/images/logo1.png') no-repeat center center fixed;
            background-size: 50%; /* taille du logo en arrière-plan */
            background-color: #f0f2f5; /* couleur de fond pour contraste */
        }
        .login-container {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            background-color: rgba(255, 255, 255, 0.2); /* transparent légèrement */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
        .login-card h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #0056b3;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #0056b3;
            border: none;
        }
        .btn-primary:hover {
            background-color: #003f80;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2>Connexion Admin</h2>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?action=login">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="admin@dglink.com">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html>
