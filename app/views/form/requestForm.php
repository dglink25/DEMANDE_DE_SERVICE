<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger text-center fw-bold">
        <?= $_SESSION['error_message']; ?>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success text-center fw-bold">
        <?= $_SESSION['success_message']; ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Demande de Service - DGLINK</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Important pour mobile -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa, #e6ebf1);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card { border: none; border-radius: 12px; }
    .card-header { border-radius: 12px 12px 0 0; background: #348ae6ff; text-align: center; }
    .card-header h3 { font-weight: bold; margin: 0; }
    .required { color: red; font-weight: bold; }
    .form-label { font-weight: 500; }
    .btn-primary {
      background: #0056b3; border: none; font-size: 1.1rem;
      padding: 12px; border-radius: 8px; transition: 0.3s;
    }
    .btn-primary:hover { background: #003f80; transform: scale(1.02); }
    .section-title {
      font-size: 1.2rem; font-weight: bold; color: #0056b3;
      margin-top: 25px; margin-bottom: 10px;
      border-bottom: 2px solid #0056b3; display: inline-block; padding-bottom: 3px;
    }
    /* ✅ Améliorations Mobile */
@media (max-width: 576px) {
    body {
        padding: 10px;
        background: #f5f7fa;
    }

    .card {
        margin: 0;
        border-radius: 8px;
    }

    /* Header avec logos */
    .card-header {
        flex-direction: column;
        text-align: center;
        padding: 15px;
    }

    .card-header img {
        height: 60px;
        width: 60px;
        margin: 5px 0;
    }

    .card-header h3 {
        font-size: 1.3rem;
    }

    .card-header p {
        font-size: 0.9rem;
    }

    /* Espacement réduit */
    .card-body {
        padding: 15px;
    }

    .form-label {
        font-size: 0.9rem;
    }

    input, select, textarea {
        font-size: 0.95rem;
        padding: 10px;
    }

    /* Bouton plus gros et ergonomique */
    #submitBtn {
        font-size: 1rem;
        padding: 14px;
        border-radius: 6px;
    }

    /* Messages */
    .alert {
        font-size: 0.9rem;
        padding: 10px;
    }
}

  </style>
</head>
<body>
  <div class="container my-5">
    <div class="card shadow-lg">
      <div class="card-header text-white d-flex align-items-center justify-content-between" 
           style="background: linear-gradient(135deg, #0056b3, #0056b3); border-radius: 12px 12px 0 0;">
        <!-- Logo gauche -->
        <img src="public/images/logo1.png" alt="" style="height: 50px; width: 50px; border-radius: 50%; object-fit: cover;">
        
        <!-- Texte central -->
        <div class="card-header text-white">
        <h3> Demande de Service</h3>
        <p class="mb-0">Merci de faire confiance à <strong>DGLINK</strong>. Remplissez ce formulaire pour soumettre votre projet.</p>
      </div>

        <!-- Logo droit -->
        <img src="public/images/logo1.png" alt="" style="height: 50px; width: 50px; border-radius: 50%; object-fit: cover;">
      </div>

      <div class="card-body p-4">
        <form id="requestForm" action="index.php?action=submitRequest" method="POST" enctype="multipart/form-data">

          <!-- Type de personne -->
          <div class="section-title">Type de personne</div>
          <div class="mb-3">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="type_personne" id="typeEntreprise" value="entreprise" checked>
              <label class="form-check-label" for="typeEntreprise">Entreprise</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="type_personne" id="typeIndividu" value="individu">
              <label class="form-check-label" for="typeIndividu">Individu</label>
            </div>
          </div>

          <!-- Champs Entreprise -->
          <div id="entrepriseFields">
            <div class="section-title">Informations sur l’entreprise</div>
            <div class="mb-3">
              <label class="form-label">Nom de l'entreprise <span class="required">*</span></label>
              <input type="text" name="entreprise" class="form-control" placeholder="Ex: DGLink SARL">
            </div>
            <div class="mb-3">
              <label class="form-label">Secteur d'activité</label>
              <input type="text" name="secteur" class="form-control" placeholder="Ex: Technologie, Commerce...">
            </div>
          </div>

          <!-- Contact -->
          <div class="section-title">Informations personnelles</div>
          <div class="mb-3">
            <label class="form-label">Nom & Prénom <span class="required">*</span></label>
            <input type="text" name="contact" class="form-control" placeholder="Ex: Jean Dupont">
          </div>
          <div class="mb-3">
            <label class="form-label">Email(Opérationnelle) <span class="required">*</span></label>
            <input type="email" name="email" class="form-control" placeholder="Ex: contact@entreprise.com">
          </div>
          <div class="mb-3">
            <label class="form-label">Téléphone <span class="required">*</span></label>
            <input type="tel" name="telephone" class="form-control" placeholder="Ex: +229 01 00 00 00 00">
          </div>

          <!-- Projet -->
          <div class="section-title">Projet</div>
          <div class="mb-3">
            <label class="form-label">Type de projet</label>
            <select name="type_projet" id="type_projet" class="form-select">
              <option value="">-- Sélectionnez --</option>
              <option value="site_vitrine">Site vitrine</option>
              <option value="ecommerce">Site e-commerce</option>
              <option value="application_web">Application web</option>
              <option value="application_mobile">Application mobile</option>
              <option value="autres">Autres</option>
            </select>
            <input type="text" name="type_projet_autre" id="type_projet_autre" class="form-control mt-2" placeholder="Précisez le type de projet" style="display:none;">
          </div>

          <div class="mb-3">
            <label class="form-label">Objectifs du projet</label>
            <textarea name="objectifs" class="form-control" rows="3" placeholder="Ex: Améliorer la visibilité en ligne..."></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Fonctionnalités souhaitées</label><br>
            <div class="form-check">
              <input type="checkbox" name="fonctionnalites[]" value="contact" class="form-check-input">
              <label class="form-check-label">Formulaire de contact</label>
            </div>
            <div class="form-check">
              <input type="checkbox" name="fonctionnalites[]" value="blog" class="form-check-input">
              <label class="form-check-label">Blog</label>
            </div>
            <div class="form-check">
              <input type="checkbox" name="fonctionnalites[]" value="paiement" class="form-check-input">
              <label class="form-check-label">Paiement en ligne</label>
            </div>
            <div class="form-check">
              <input type="checkbox" name="fonctionnalites[]" value="chat" class="form-check-input">
              <label class="form-check-label">Chat en direct</label>
            </div>
            <div class="form-check">
              <input type="checkbox" name="fonctionnalites[]" value="autres" class="form-check-input">
              <label class="form-check-label">Autres</label>
            </div>
          </div>

          <!-- Budget / Délai -->
          <div class="section-title">Budget & Délai</div>
          <div class="mb-3">
            <label class="form-label">Budget estimatif</label>
            <select name="budget" id="budget" class="form-select">
              <option value="">-- Sélectionnez --</option>
              <option value="moins_500k">Moins de 500 000 FCFA</option>
              <option value="500k_1m">500 000 – 1 000 000 FCFA</option>
              <option value="plus_1m">Plus d’1 000 000 FCFA</option>
              <option value="autres">Autres</option>
              <option value="non_defini">Non défini</option>
            </select>
            <input type="text" name="budget_autre" id="budget_autre" class="form-control mt-2" placeholder="Précisez votre budget" style="display:none;">
          </div>
          <div class="mb-3">
            <label class="form-label">Délai souhaité</label>
            <select name="delai" class="form-select">
              <option value="">-- Sélectionnez --</option>
              <option value="moins_1mois">Moins d’1 mois</option>
              <option value="1_3mois">1 à 3 mois</option>
              <option value="plus_3mois">Plus de 3 mois</option>
            </select>
          </div>

          <!-- Description -->
          <div class="mb-3">
            <label class="form-label">Description détaillée</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Décrivez votre projet..."></textarea>
          </div>

          <!-- Document -->
          <div class="mb-3">
            <label class="form-label">Joindre un document</label>
            <input type="file" name="document" class="form-control">
          </div>

          <!-- Consentement -->
          <div class="form-check mb-3">
            <input type="checkbox" name="consentement" id="consentement" value="1" class="form-check-input">
            <label class="form-check-label">J’accepte que mes données soient utilisées <span class="required">*</span></label>
          </div>

          <!-- Bouton -->
          <button type="submit" id="submitBtn" class="btn btn-primary w-100" disabled>
             Envoyer ma demande
          </button>

          <p class="mt-3 text-muted"><span class="required">*</span> Champs obligatoires</p>
        </form>
      </div>
    </div>
  </div>

  <script>
    const entrepriseRadio = document.getElementById('typeEntreprise');
    const individuRadio = document.getElementById('typeIndividu');
    const consentement = document.getElementById('consentement');
    const submitBtn = document.getElementById('submitBtn');
    const entrepriseFields = document.getElementById('entrepriseFields');

    function toggleEntrepriseFields() {
      entrepriseFields.style.display = entrepriseRadio.checked ? 'block' : 'none';
    }

    entrepriseRadio.addEventListener('change', toggleEntrepriseFields);
    individuRadio.addEventListener('change', toggleEntrepriseFields);
    toggleEntrepriseFields();

    consentement.addEventListener('change', () => {
      submitBtn.disabled = !consentement.checked;
    });

    const typeProjet = document.getElementById('type_projet');
    const typeProjetAutre = document.getElementById('type_projet_autre');
    typeProjet.addEventListener('change', () => {
      typeProjetAutre.style.display = (typeProjet.value === 'autres') ? 'block' : 'none';
    });

    const budget = document.getElementById('budget');
    const budgetAutre = document.getElementById('budget_autre');
    budget.addEventListener('change', () => {
      budgetAutre.style.display = (budget.value === 'autres') ? 'block' : 'none';
    });
  </script>
</body>
</html>
