<?php require_once __DIR__ . '/../../helpers/country.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 player-show-container">

        <?php if (!empty($joueur['photo_path'])): ?>
            <img src="public/uploads/players/<?= htmlspecialchars($joueur['photo_path']) ?>"
                class="img-fluid rounded player-photo-show mb-3" alt="Photo du joueur">
        <?php else: ?>
            <div class="border text-center p-4 mb-3">
                <em>Aucune photo</em>
            </div>
        <?php endif; ?>

        <h2><?= htmlspecialchars($joueur['nom']) ?></h2>

        <p><strong>Âge :</strong> <?= $joueur['age'] ?> ans</p>
        <p><strong>Taille :</strong> <?= $joueur['taille'] ?> cm</p>
        <p><strong>Poids :</strong> <?= $joueur['poids'] ?> kg</p>

        <p><strong>Nationalité :</strong>
            <img src="<?= countryFlag($joueur['nationalite']) ?>" class="flag-sm">
        </p>

        <p><strong>Poste :</strong> <?= htmlspecialchars($joueur['poste']) ?></p>

        <?php if (!empty($joueur['bio'])): ?>
            <p><strong>Bio :</strong><br><?= nl2br(htmlspecialchars($joueur['bio'])) ?></p>
        <?php endif; ?>

        <p><strong>Créé par :</strong> <?= htmlspecialchars($joueur['createur']) ?></p>

        <hr>

        <?php if ($peutModifier): ?>
            <a href="index.php?page=edit_joueur&id=<?= $joueur['id'] ?>" class="btn btn-warning me-2">
                Modifier
            </a>

            <a href="index.php?page=delete_joueur&id=<?= $joueur['id'] ?>"
                onclick="return confirm('Supprimer ce joueur ?');" class="btn btn-danger">
                Supprimer
            </a>
        <?php endif; ?>

    </div>
</div>