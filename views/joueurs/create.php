<?php require_once __DIR__ . '/../../helpers/country.php';
$pays = getCountryList();
?>


<div class="row justify-content-center">
    <div class="col-md-8">

        <h1 class="mb-4">Créer un joueur</h1>

        <!-- Message d’erreur global -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php?page=create_joueur" enctype="multipart/form-data">

            <!-- NOM -->
            <div class="mb-3">
                <label class="form-label">Nom (3-20 caractères, unique)</label>
                <input type="text" name="nom" class="form-control" maxlength="20"
                    value="<?= htmlspecialchars($old['nom'] ?? '') ?>" required>
            </div>

            <!-- AGE -->
            <div class="mb-3">
                <label class="form-label">Âge (18 à 99)</label>
                <input type="number" name="age" class="form-control" min="18" max="99"
                    value="<?= htmlspecialchars($old['age'] ?? '') ?>" required>
            </div>

            <!-- TAILLE -->
            <div class="mb-3">
                <label class="form-label">Taille (en cm, 140 à 220)</label>
                <input type="number" name="taille" class="form-control" min="140" max="220"
                    value="<?= htmlspecialchars($old['taille'] ?? '') ?>" required>
            </div>

            <!-- POIDS -->
            <div class="mb-3">
                <label class="form-label">Poids (en kg, 40 à 150)</label>
                <input type="number" name="poids" class="form-control" min="40" max="150"
                    value="<?= htmlspecialchars($old['poids'] ?? '') ?>" required>
            </div>

            <!-- NATIONALITÉ -->
            <div class="mb-3">
                <label class="form-label">Nationalité</label>
                <select name="nationalite" class="form-select" required>
                    <option value="">-- Sélectionnez un pays --</option>

                    <?php
                    $selected = $old['nationalite'] ?? '';
                    foreach ($pays as $p):
                        ?>
                        <option value="<?= $p ?>" <?= ($selected === $p) ? 'selected' : '' ?>>
                            <?= $p ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- POSTE -->
            <div class="mb-3">
                <label class="form-label">Poste</label>
                <select name="poste" class="form-select" required>
                    <?php
                    $postes = ['Gardien', 'Défenseur', 'Milieu', 'Attaquant'];
                    $selected = $old['poste'] ?? '';
                    foreach ($postes as $p): ?>
                        <option value="<?= $p ?>" <?= ($selected === $p) ? 'selected' : '' ?>>
                            <?= $p ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- BIO -->
            <div class="mb-3">
                <label class="form-label">Bio (max 300 caractères)</label>
                <textarea name="bio" class="form-control" maxlength="300"
                    rows="4"><?= htmlspecialchars($old['bio'] ?? '') ?></textarea>
            </div>

            <!-- PHOTO -->
            <div class="mb-3">
                <label class="form-label">Photo (PNG/JPG, max 2 Mo)</label>
                <input type="file" name="photo" class="form-control" accept="image/png, image/jpeg">
            </div>

            <button type="submit" class="btn btn-primary">
                Créer le joueur
            </button>
        </form>

    </div>
</div>