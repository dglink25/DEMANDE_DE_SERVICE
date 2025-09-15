<?php
require_once __DIR__ . "/../Models/Admin.php";
require_once __DIR__ . '/../Models/ProjectRequest.php';
class AdminController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $adminModel = new Admin($this->pdo);
            $admin = $adminModel->findByEmail($email);

            if ($admin && password_verify($password, $admin['mot_de_passe'])) {
                // Stockage dans la session
                $_SESSION['admin_id']   = $admin['id'];
                $_SESSION['admin_nom']  = $admin['nom'];
                $_SESSION['admin_role'] = $admin['role'];

                // Redirection vers dashboard
                header("Location: index.php?action=dashboard");
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
                include __DIR__ . "/../views/admin/login.php";
            }
        } else {
            include __DIR__ . "/../views/admin/login.php";
        }
    }

    // Dashboard

public function dashboard() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: index.php?action=login");
        exit;
    }

    $requestModel = new ProjectRequest($this->pdo);

    // Statistiques
    $totalDemandes = $requestModel->countByStatus();
    $attente       = $requestModel->countByStatus('en_attente');
    $approuve      = $requestModel->countByStatus('approuve');
    $rejete        = $requestModel->countByStatus('rejete');

    // Dernières demandes (5)
    $recentDemandes = $requestModel->getRequests(null, 5);

    include __DIR__ . "/../views/admin/dashboard.php";
}

// Historique des demandes
public function historique() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: index.php?action=login");
        exit;
    }

    // Récupérer les filtres depuis l'URL
    $status = $_GET['status'] ?? '';
    $date   = $_GET['date'] ?? '';

    // Base SQL
    $sql = "SELECT * FROM project_requests WHERE 1=1";
    $params = [];

    // Filtre statut
    if (!empty($status)) {
        $sql .= " AND statut = :status";
        $params[':status'] = $status;
    }

    // Filtre date
    if (!empty($date)) {
        $sql .= " AND DATE(date_soumission) = :date";
        $params[':date'] = $date;
    }

    $sql .= " ORDER BY date_soumission DESC";

    // Exécuter la requête
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Charger la vue
    include __DIR__ . "/../views/admin/historique.php";
}

// Pour récupérer les informations du formulaire utilisateur
public function submitRequest() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Vérifier consentement (checkbox envoie souvent "on")
        if (empty($_POST['consentement'])) {
            $_SESSION['error_message'] = "Vous devez accepter que vos données soient utilisées.";
            header("Location: index.php?action=requestForm");
            exit;
        }

        $typePersonne = $_POST['type_personne'] ?? 'entreprise';

        // Champs obligatoires selon type de personne
        $requiredFields = ['contact', 'email'];
        if ($typePersonne === 'entreprise') {
            $requiredFields[] = 'entreprise';
        }

        foreach ($requiredFields as $field) {
            if (empty(trim((string)($_POST[$field] ?? '')))) {
                $_SESSION['error_message'] = "Veuillez remplir tous les champs obligatoires (*) avant de soumettre le formulaire.";
                header("Location: index.php?action=requestForm");
                exit;
            }
        }

        $fonctionnalites = $_POST['fonctionnalites'] ?? [];

        // Upload fichier utilisateur
        $fileName = null;
        if (!empty($_FILES['document']['name']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/../../uploads/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $originalName = basename($_FILES['document']['name']);
            $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);
            $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'png'];

            if (in_array(strtolower($fileExtension), $allowedExtensions, true)) {
                $fileName = time() . "_" . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
                $destination = $uploadDir . $fileName;
                if (!move_uploaded_file($_FILES['document']['tmp_name'], $destination)) {
                    $_SESSION['error_message'] = "Erreur lors de l'upload du fichier.";
                    header("Location: index.php?action=requestForm");
                    exit;
                }
            } else {
                $_SESSION['error_message'] = "Extension non autorisée (formats acceptés : PDF, DOC, DOCX, JPG, PNG).";
                header("Location: index.php?action=requestForm");
                exit;
            }
        }

        // Données à sauvegarder
        $data = [
            'type_personne'     => $typePersonne,
            'entreprise'        => $_POST['entreprise'] ?? null,
            'secteur'           => $_POST['secteur'] ?? null,
            'contact'           => $_POST['contact'],
            'email'             => $_POST['email'],
            'telephone'         => $_POST['telephone'] ?? null,
            'type_projet'       => $_POST['type_projet'] ?? null,
            'type_projet_autre' => $_POST['type_projet_autre'] ?? null,
            'objectifs'         => $_POST['objectifs'] ?? null,
            'fonctionnalites'   => $fonctionnalites,
            'budget'            => $_POST['budget'] ?? null,
            'budget_autre'      => $_POST['budget_autre'] ?? null,
            'delai'             => $_POST['delai'] ?? null,
            'description'       => $_POST['description'] ?? null,
            'document'          => $fileName,
            'consentement'      => 1
        ];

        $requestModel = new ProjectRequest($this->pdo);
        $requestModel->create($data);

        // Envoi des mails (admin et client)
        require_once __DIR__ . '/../helpers/Mailer.php';

        // Mail admin
        Mailer::sendMail(
            "dglink25@gmail.com",
            "=?UTF-8?B?" . base64_encode("Nouvelle demande soumise") . "?=",
            $this->mailTemplate("Nouvelle demande", "
                Une nouvelle demande a été soumise.<br><br>
                <b>Type :</b> {$data['type_personne']}<br>
                <b>Contact :</b> " . htmlspecialchars($data['contact']) . "<br>
                <b>Email :</b> " . htmlspecialchars($data['email']) . "<br><br>
                Connectez-vous au dashboard pour la consulter.")
        );

        // Mail client
        Mailer::sendMail(
            $data['email'],
            "=?UTF-8?B?" . base64_encode("Votre demande a bien été envoyée") . "?=",
            $this->mailTemplate("Confirmation de soumission", "
                Bonjour <b>" . htmlspecialchars($data['contact']) . "</b>,<br><br>
                Merci d’avoir contacté <b>DGLINK</b>.<br>
                Votre demande sera traitée sous 48h.<br><br>
                Cordialement,<br>L’équipe DGLINK.")
        );

        $_SESSION['success_message'] = "Votre demande a bien été transmise. Un retour vous sera envoyé par e-mail après traitement par l’équipe <strong>DGLINK</strong>. Merci pour votre confiance."
;
        header("Location: index.php?action=requestForm");
        exit;
    } else {
        include __DIR__ . "/../views/form/requestForm.php";
    }
}

// Approuver une demande
public function approuverDemande() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['demande_id'];
        $justification = $_POST['justification'] ?? '';

        // Upload fichier admin (optionnel)
        $fileName = null;
        if (!empty($_FILES['justification_file']['name']) && $_FILES['justification_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/../../public/uploads/justifications/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $original = basename($_FILES['justification_file']['name']);
            $fileName = time() . "_" . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $original);
            move_uploaded_file($_FILES['justification_file']['tmp_name'], $uploadDir . $fileName);
        }

        // Récupérer demande
        $stmt = $this->pdo->prepare("SELECT email, contact FROM project_requests WHERE id=:id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $demande = $stmt->fetch(PDO::FETCH_ASSOC);

        // Update via le modèle
        $requestModel = new ProjectRequest($this->pdo);
        $requestModel->updateStatus($id, 'approuve', $justification, $fileName);

        // Préparer chemin réel du fichier (si présent) pour l'attachement
        $attachment = null;
        if (!empty($fileName)) {
            $attachment = realpath(__DIR__ . "/../../public/uploads/justifications/" . $fileName);
        }

        // Mail au client
        require_once __DIR__ . '/../helpers/Mailer.php';
        Mailer::sendMail(
            $demande['email'],
            "=?UTF-8?B?" . base64_encode("Votre demande a été approuvée") . "?=",
            $this->mailTemplate("Demande approuvée", "
                Bonjour <b>" . htmlspecialchars($demande['contact']) . "</b>,<br><br>
                Félicitations 🎉, votre demande a été <b>approuvée</b>.<br><br>
                <b>Motif:</b><br>" . nl2br(htmlspecialchars($justification))
            ),
            $attachment
        );

        header("Location: index.php?action=historique&success=1");
        exit;
    }
}

// Rejeter une demande
public function rejeterDemande() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['demande_id'];
        $justification = $_POST['justification'] ?? '';

        // Upload fichier admin (optionnel)
        $fileName = null;
        if (!empty($_FILES['justification_file']['name']) && $_FILES['justification_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/../../public/uploads/justifications/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $original = basename($_FILES['justification_file']['name']);
            $fileName = time() . "_" . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $original);
            move_uploaded_file($_FILES['justification_file']['tmp_name'], $uploadDir . $fileName);
        }

        // Récupérer demande
        $stmt = $this->pdo->prepare("SELECT email, contact FROM project_requests WHERE id=:id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $demande = $stmt->fetch(PDO::FETCH_ASSOC);

        // Update via le modèle
        $requestModel = new ProjectRequest($this->pdo);
        $requestModel->updateStatus($id, 'rejete', $justification, $fileName);

        $attachment = null;
        if (!empty($fileName)) {
            $attachment = realpath(__DIR__ . "/../../public/uploads/justifications/" . $fileName);
        }

        // Mail au client
        require_once __DIR__ . '/../helpers/Mailer.php';
        Mailer::sendMail(
            $demande['email'],
            "=?UTF-8?B?" . base64_encode("Votre demande a été rejetée") . "?=",
            $this->mailTemplate("Demande rejetée", "
                Bonjour <b>" . htmlspecialchars($demande['contact']) . "</b>,<br><br>
                Nous sommes au regret de vous informer que votre demande a été <b>rejetée</b>.<br><br>
                <b>Motif :</b><br>" . nl2br(htmlspecialchars($justification))
            ),
            $attachment
        );

        header("Location: index.php?action=historique&success=1");
        exit;
    }
}

// Petit helper pour mails HTML
private function mailTemplate($titre, $contenu) {
    return '
    <html>
    <head>
        <meta charset="utf-8">
        <style>
            .header { background:#0d6efd; color:white; padding:15px; text-align:center; font-size:18px; font-family:Arial,Helvetica,sans-serif; }
            .footer { background:#f8f9fa; color:#333; padding:10px; text-align:center; font-size:13px; margin-top:20px; font-family:Arial,Helvetica,sans-serif; }
            .content { padding:20px; font-size:15px; line-height:1.6; font-family:Arial,Helvetica,sans-serif; color:#333; }
            a { color:#0d6efd; text-decoration:none; }
        </style>
    </head>
    <body>
        <div class="header">DGLINK - ' . htmlspecialchars($titre) . '</div>
        <div class="content">' . $contenu . '</div>
        <div class="footer">DGLINK © ' . date("Y") . ' | Assistance : dglink25@gmail.com</div>
    </body>
    </html>';
}
// Afficher la page paramètres
public function parametres() {
    $adminModel = new Admin($this->pdo);
    $admin = $adminModel->getById($_SESSION['admin_id']); // Récupère infos admin connecté
    include __DIR__ . '/../views/admin/parametres.php';
}

// Mettre à jour les paramètres
public function updateParametres() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_SESSION['admin_id'];

        $data = [
            'nom'   => $_POST['nom'] ?? '',
            'email' => $_POST['email'] ?? '',
        ];

        // Si un nouveau mot de passe est saisi
        if (!empty($_POST['password'])) {
            $data['motdepasse'] = $_POST['password'];
        }

        $adminModel = new Admin($this->pdo);
        $updated = $adminModel->update($id, $data);

        if ($updated) {
            // Mettre à jour la session
            $_SESSION['admin_nom'] = $data['nom'];
            $_SESSION['admin_email'] = $data['email'];

            header("Location: index.php?action=parametres&success=1");
            exit;
        } else {
            header("Location: index.php?action=parametres&error=1");
            exit;
        }
    }
}
    // Déconnexion
    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}   