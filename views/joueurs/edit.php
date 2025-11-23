<?php
require_once __DIR__ . '/../../helpers/country.php';
$pays = getCountryList();
?>

<div class="row justify-content-center">
    <div class="col-md-8">

        <h1 class="mb-4">Modifier le joueur</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="index.php?page=update_joueur&id=<?= $joueur['id'] ?>" enctype="multipart/form-data">

            <!-- NOM -->
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" maxlength="20"
                    value="<?= htmlspecialchars($joueur['nom']) ?>" required>
            </div>

            <!-- AGE -->
            <div class="mb-3">
                <label class="form-label">Âge</label>
                <input type="number" name="age" class="form-control" min="18" max="99"
                    value="<?= htmlspecialchars($joueur['age']) ?>" required>
            </div>

            <!-- TAILLE -->
            <div class="mb-3">
                <label class="form-label">Taille (cm)</label>
                <input type="number" name="taille" class="form-control" min="140" max="240"
                    value="<?= htmlspecialchars($joueur['taille']) ?>" required>
            </div>

            <!-- POIDS -->
            <div class="mb-3">
                <label class="form-label">Poids (kg)</label>
                <input type="number" name="poids" class="form-control" min="40" max="150"
                    value="<?= htmlspecialchars($joueur['poids']) ?>" required>
            </div>

            <!-- NATIONALITÉ -->
            <div class="mb-3">
                <label class="form-label">Nationalité</label>
                <select name="nationalite" class="form-select" required>
                    <?php foreach ($pays as $p): ?>
                        <option value="<?= $p ?>" <?= ($joueur['nationalite'] === $p) ? 'selected' : '' ?>>
                            <?= $p ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- POSTE -->
            <div class="mb-3">
                <label class="form-label">Poste</label>
                <select name="poste" class="form-select" required>
                    <?php $postes = ['Gardien', 'Défenseur', 'Milieu', 'Attaquant']; ?>
                    <?php foreach ($postes as $p): ?>
                        <option value="<?= $p ?>" <?= ($joueur['poste'] === $p) ? 'selected' : '' ?>>
                            <?= $p ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- BIO -->
            <div class="mb-3">
                <label class="form-label">Bio</label>
                <textarea name="bio" class="form-control" maxlength="300" rows="4">
                    <?= htmlspecialchars($joueur['bio']) ?>
                </textarea>
            </div>

            <!-- PHOTO -->
            <div class="mb-3">
                <label class="form-label">Photo (PNG/JPG, max 2 Mo)</label>
                <input type="file" name="photo" class="form-control" accept="image/png, image/jpeg">

                <?php if (!empty($joueur['photo_path'])): ?>
                    <p class="mt-2">Photo actuelle :</p>
                    <img src="public/uploads/players/<?= htmlspecialchars($joueur['photo_path']) ?>" style="width: 80px;"
                        class="rounded player-photo-md">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>

    </div>
</div>