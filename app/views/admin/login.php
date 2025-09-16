<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin - DGLINK</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Important pour mobile -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('public/images/logo1.png') no-repeat center center fixed;
            background-size: 100%; /* sur PC */
            background-color: #f0f2f5;
        }

        .login-container {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px; /* espace sur mobile */
        }

        .login-card {
            background-color: rgba(255, 255, 255, 0.9);
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

        /* ✅ Amélioration Mobile */
        @media (max-width: 576px) {
            body {
                background-size: cover; /* logo prend tout l’écran sur mobile */
                background-position: center;
            }

            .login-card {
                max-width: 100%; /* occupe presque tout l’écran */
                padding: 20px;
                border-radius: 8px;
            }

            .login-card h2 {
                font-size: 1.4rem; /* texte réduit */
            }

            input, button {
                font-size: 1rem; /* taille adaptée au doigt */
            }
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
