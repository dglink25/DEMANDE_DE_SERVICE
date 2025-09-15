<?php
class Admin {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Trouver un admin par email (déjà existant)
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Trouver un admin par ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour les infos d’un admin
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        if (!empty($data['nom'])) {
            $fields[] = "nom = :nom";
            $params[':nom'] = $data['nom'];
        }

        if (!empty($data['email'])) {
            $fields[] = "email = :email";
            $params[':email'] = $data['email'];
        }

        if (!empty($data['motdepasse'])) {
            $fields[] = "motdepasse = :motdepasse";
            $params[':motdepasse'] = password_hash($data['motdepasse'], PASSWORD_DEFAULT);
        }

        if (empty($fields)) {
            return false; // rien à mettre à jour
        }

        $sql = "UPDATE admin SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
