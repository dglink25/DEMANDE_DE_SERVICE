<?php include __DIR__ . "/../layouts/header.php"; ?>

<main class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-primary fw-bold"> Historique des demandes</h1>
    </div>

    <!-- Formulaire de filtrage -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="index.php" class="row g-3 align-items-end">
                <input type="hidden" name="action" value="historique">

                <div class="col-md-4">
                    <label for="status" class="form-label fw-semibold">Statut</label>
                    <select class="form-select" name="status" id="status">
                        <option value="">Tous</option>
                        <option value="approuve">Approuvé</option>
                        <option value="rejete">Rejeté</option>
                        <option value="en_attente">En attente</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="date" class="form-label fw-semibold">Date</label>
                    <input type="date" class="form-control" name="date" id="date">
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des demandes -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>N°</th>
                            <th>Entreprise</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php if (!empty($demandes)) : ?>
                            <?php $i = 1; foreach ($demandes as $demande) : ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td>
                                        <?= !empty($demande['entreprise']) 
                                            ? htmlspecialchars($demande['entreprise']) 
                                            : '-' ?>
                                    </td>
                                    <td><?= htmlspecialchars($demande['email']) ?></td>
                                    <td><?= htmlspecialchars($demande['date_soumission']) ?></td>
                                    <td>
                                        <?php if ($demande['statut'] === 'approuve') : ?>
                                            <span class="badge bg-success">Approuvé</span>
                                        <?php elseif ($demande['statut'] === 'rejete') : ?>
                                            <span class="badge bg-danger">Rejeté</span>
                                        <?php else : ?>
                                            <span class="badge bg-warning text-dark">En attente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- Bouton Voir détails -->
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal<?= $demande['id'] ?>">
                                            Voir détails
                                        </button>

                                        <!-- Si un document existe -->
                                        <?php if (!empty($demande['document'])) : ?>
                                            <a href="uploads/<?= htmlspecialchars($demande['document']) ?>" 
                                            download="<?= htmlspecialchars($demande['document']) ?>" 
                                            class="btn btn-secondary btn-sm">
                                             Télécharger
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                <!-- Modal détails -->
                                <div class="modal fade" id="detailsModal<?= $demande['id'] ?>" tabindex="-1" aria-labelledby="detailsLabel<?= $demande['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailsLabel<?= $demande['id'] ?>"> Détails de la demande</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Entreprise :</strong> <?= htmlspecialchars($demande['entreprise']) ?></p>
                                                <p><strong>Secteur :</strong> <?= htmlspecialchars($demande['secteur'] ?? '-') ?></p>
                                                <p><strong>Contact :</strong> <?= htmlspecialchars($demande['contact'] ?? '-') ?></p>
                                                <p><strong>Email :</strong> <?= htmlspecialchars($demande['email']) ?></p>
                                                <p><strong>Téléphone :</strong> <?= htmlspecialchars($demande['telephone'] ?? '-') ?></p>
                                                <p><strong>Type de projet :</strong> <?= htmlspecialchars($demande['type_projet'] ?? '-') ?></p>
                                                <p><strong>Objectifs :</strong> <?= htmlspecialchars($demande['objectifs'] ?? '-') ?></p>
                                                <p><strong>Fonctionnalités :</strong> <?= htmlspecialchars($demande['fonctionnalites'] ?? '-') ?></p>
                                                <p><strong>Budget :</strong> <?= htmlspecialchars($demande['budget'] ?? '-') ?></p>
                                                <p><strong>Délai :</strong> <?= htmlspecialchars($demande['delai'] ?? '-') ?></p>
                                                <p><strong>Description :</strong><br><?= nl2br(htmlspecialchars($demande['description'] ?? '-')) ?></p>
                                                <p><strong>Statut :</strong> <?= htmlspecialchars($demande['statut']) ?></p>
                                                <p><strong>Justification :</strong> <?= htmlspecialchars($demande['justification'] ?? '-') ?></p>
                                                <?php if (!empty($demande['document'])) : ?>
                                                    <p><strong>Fichier :</strong> 
                                                        <a href="uploads/<?= htmlspecialchars($demande['document']) ?>" target="_blank"> Voir document</a>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-muted">Aucune demande trouvée</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . "/../layouts/footer.php"; ?>
