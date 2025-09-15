<?php include __DIR__ . '/../layouts/header.php'; ?>
<!-- <link rel="stylesheet" href="public/assets/css/style.css"> -->

<?php
$totalDemandes   = $requestModel->countByStatus();
$approuve        = $requestModel->countByStatus('approuve');
$rejete          = $requestModel->countByStatus('rejete');
$attente         = $requestModel->countByStatus('en_attente');
$recentDemandes  = $requestModel->getRequests(null, 5);
?>

<div class="row">
<!-- Cartes stats -->
<div class="col-md-3 mb-3">
    <div class="card text-white bg-primary shadow-sm h-100">
        <div class="card-body">
            <h5 class="card-title">Total des demandes</h5>
            <p class="card-text display-6 fw-bold"><?= $totalDemandes ?></p>
            <a href="index.php?action=historique" class="text-white">Voir détails</a>
        </div>
    </div>
</div>
<div class="col-md-3 mb-3">
    <div class="card text-white bg-success shadow-sm h-100">
        <div class="card-body">
            <h5 class="card-title">Approuvées</h5>
            <p class="card-text display-6 fw-bold"><?= $approuve ?></p>
            <a href="index.php?action=historique&status=approuve" class="text-white">Voir détails</a>
        </div>
    </div>
</div>
<div class="col-md-3 mb-3">
    <div class="card text-white bg-danger shadow-sm h-100">
        <div class="card-body">
            <h5 class="card-title">Rejetées</h5>
            <p class="card-text display-6 fw-bold"><?= $rejete ?></p>
            <a href="index.php?action=historique&status=rejete" class="text-white">Voir détails</a>
        </div>
    </div>
</div>
<div class="col-md-3 mb-3">
    <div class="card text-white bg-warning shadow-sm h-100">
        <div class="card-body">
            <h5 class="card-title">En attente</h5>
            <p class="card-text display-6 fw-bold"><?= $attente ?></p>
            <a href="index.php?action=historique&status=en_attente" class="text-white">Voir détails</a>
        </div>
    </div>
</div>


<!-- Tableau des dernières demandes -->
<div class="card mt-4 shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Dernières demandes</h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>N°</th>
                    <th>Entreprise</th>
                    <th>Soumis par</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recentDemandes)): ?>
                    <?php foreach ($recentDemandes as $index => $demande): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <?= !empty($demande['entreprise']) 
                                    ? htmlspecialchars($demande['entreprise']) 
                                    : '-' ?>
                            </td>
                            <td><?= htmlspecialchars($demande['contact']) ?></td>
                            <td><?= htmlspecialchars($demande['email']) ?></td>
                            <td><?= htmlspecialchars($demande['date_soumission']) ?></td>
                            <td>
                                <?php
                                switch ($demande['statut']) {
                                    case 'en_attente': echo '<span class="badge bg-warning">En attente</span>'; break;
                                    case 'approuve': echo '<span class="badge bg-success">Approuvée</span>'; break;
                                    case 'rejete': echo '<span class="badge bg-danger">Rejetée</span>'; break;
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($demande['statut'] === 'en_attente'): ?>
                                    <!-- Bouton Approuver -->
                                    <button type="button" class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalApprouver<?= $demande['id'] ?>">
                                        Approuver
                                    </button>

                                    <!-- Modal Approuver -->
                                    <!-- Modal Approuver -->
<div class="modal fade" id="modalApprouver<?= $demande['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Justification d’approbation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?action=approuverDemande" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="demande_id" value="<?= $demande['id'] ?>">
                    <div class="mb-3">
                        <label>Justification :</label>
                        <textarea name="justification" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Joindre un fichier (optionnel) :</label>
                        <input type="file" name="justification_file" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>



                                    <!-- Bouton Rejeter -->
                                    <button type="button" class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalRejeter<?= $demande['id'] ?>">
                                        Rejeter
                                    </button>

                                    <!-- Modal Rejeter -->
                                    <!-- Modal Rejeter -->
<div class="modal fade" id="modalRejeter<?= $demande['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Justification de rejet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?action=rejeterDemande" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="demande_id" value="<?= $demande['id'] ?>">
                    <div class="mb-3">
                        <label>Justification :</label>
                        <textarea name="justification" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Joindre un fichier (optionnel) :</label>
                        <input type="file" name="justification_file" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>

                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>Action terminée</button>
                                <?php endif; ?>

                                <!-- Bouton Voir -->
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#voirModal<?= $demande['id'] ?>">
                                    Voir
                                </button>

                                <!-- Modal Voir (inchangé, détails demande) -->
                                <div class="modal fade" id="voirModal<?= $demande['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title">Détails de la demande</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Entreprise :</strong> <?= htmlspecialchars($demande['entreprise']) ?></p>
                                                <p><strong>Contact :</strong> <?= htmlspecialchars($demande['contact']) ?></p>
                                                <p><strong>Email :</strong> <?= htmlspecialchars($demande['email']) ?></p>
                                                <p><strong>Téléphone :</strong> <?= htmlspecialchars($demande['telephone']) ?></p>
                                                <p><strong>Secteur :</strong> <?= htmlspecialchars($demande['secteur']) ?></p>
                                                <p><strong>Objectifs :</strong> <?= nl2br(htmlspecialchars($demande['objectifs'])) ?></p>
                                                <?php if (!empty($demande['document'])): ?>
                                                    <p><strong>Document :</strong> 
                                                        <a href="uploads/<?= $demande['document'] ?>" target="_blank">Voir le fichier</a>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">Aucune demande trouvée</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
