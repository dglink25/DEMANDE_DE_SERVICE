<?php

class ProjectRequest {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Créer une demande
    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO project_requests 
            (type_personne, entreprise, secteur, contact, email, telephone, type_projet, type_projet_autre, objectifs, fonctionnalites, budget, budget_autre, delai, description, document, consentement, statut, date_soumission) 
            VALUES 
            (:type_personne, :entreprise, :secteur, :contact, :email, :telephone, :type_projet, :type_projet_autre, :objectifs, :fonctionnalites, :budget, :budget_autre, :delai, :description, :document, :consentement, 'en_attente', NOW())
        ");

        $fonctionnalites = isset($data['fonctionnalites']) && is_array($data['fonctionnalites'])
            ? json_encode($data['fonctionnalites'])
            : $data['fonctionnalites'] ?? null;

        $stmt->execute([
            ':type_personne'     => $data['type_personne'] ?? 'entreprise',
            ':entreprise'        => $data['entreprise'] ?? null,
            ':secteur'           => $data['secteur'] ?? null,
            ':contact'           => $data['contact'] ?? null,
            ':email'             => $data['email'] ?? null,
            ':telephone'         => $data['telephone'] ?? null,
            ':type_projet'       => $data['type_projet'] ?? null,
            ':type_projet_autre' => $data['type_projet_autre'] ?? null,
            ':objectifs'         => $data['objectifs'] ?? null,
            ':fonctionnalites'   => $fonctionnalites,
            ':budget'            => $data['budget'] ?? null,
            ':budget_autre'      => $data['budget_autre'] ?? null,
            ':delai'             => $data['delai'] ?? null,
            ':description'       => $data['description'] ?? null,
            ':document'          => $data['document'] ?? null,
            ':consentement'      => isset($data['consentement']) ? 1 : 0,
        ]);
    }

    // Mettre à jour le statut + justification + fichier (admin)
    public function updateStatus($id, $statut, $justification, $justificationFile = null) {
        $sql = "UPDATE project_requests 
                SET statut = :statut, justification = :justification, justification_file = :justification_file 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':statut' => $statut,
            ':justification' => $justification,
            ':justification_file' => $justificationFile,
            ':id' => $id
        ]);
    }

    // Récupérer toutes les demandes (optionnellement filtrées par statut et limite)
    public function getRequests($statut = null, $limit = null) {
        $sql = "SELECT * FROM project_requests";
        $params = [];

        if ($statut) {
            $sql .= " WHERE statut = :statut";
            $params[':statut'] = $statut;
        }

        $sql .= " ORDER BY date_soumission DESC";

        if ($limit) {
            $sql .= " LIMIT :limit";
            $params[':limit'] = (int)$limit;
        }

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => &$val) {
            if ($key === ':limit') $stmt->bindParam($key, $val, PDO::PARAM_INT);
            else $stmt->bindParam($key, $val);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compter les demandes par statut
    public function countByStatus($statut = null) {
        if ($statut) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM project_requests WHERE statut = :statut");
            $stmt->execute([':statut'=>$statut]);
        } else {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM project_requests");
        }
        return $stmt->fetchColumn();
    }

    // Filtrer par statut et date
    public function getAllDemandes($status = '', $date = '') {
        $sql = "SELECT * FROM project_requests WHERE 1=1";
        $params = [];

        if (!empty($status)) {
            $sql .= " AND statut = :status";
            $params[':status'] = $status;
        }

        if (!empty($date)) {
            $sql .= " AND DATE(date_soumission) = :date";
            $params[':date'] = $date;
        }

        $sql .= " ORDER BY date_soumission DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

