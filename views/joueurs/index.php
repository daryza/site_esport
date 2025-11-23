<?php require_once __DIR__ . '/../../helpers/country.php'; ?>

<h1 class="mb-4">Tous les joueurs</h1>

<?php if (empty($joueurs)): ?>
    <div class="alert alert-info">
        Aucun joueur n'a encore été créé.
    </div>

<?php else: ?>

    <table class="table table-borderless align-middle">
        <tbody>

            <?php foreach ($joueurs as $joueur): ?>
                <tr>

                    <!-- PHOTO -->
                    <td class="w-70">
                        <?php if (!empty($joueur['photo_path'])): ?>
                            <img src="public/uploads/players/<?= htmlspecialchars($joueur['photo_path']) ?>"
                                class="rounded player-photo-sm" alt="photo du joueur">
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>

                    <!-- NOM + CRÉATEUR -->
                    <td class="w-260">
                        <div class="text-large">
                            <?= htmlspecialchars($joueur['nom']) ?>
                        </div>
                        <div class="text-muted text-small">
                            Créé par <?= htmlspecialchars($joueur['createur']) ?>
                        </div>
                    </td>

                    <!-- POSTE -->
                    <td class="w-150">
                        <?= htmlspecialchars($joueur['poste']) ?>
                    </td>

                    <!-- DRAPEAU -->
                    <td class="w-70">
                        <img src="<?= countryFlag($joueur['nationalite']) ?>" class="flag-sm"
                            alt="<?= htmlspecialchars($joueur['nationalite']) ?>">
                    </td>

                    <!-- ACTION -->
                    <td class="w-120">
                        <a href="index.php?page=show_joueur&id=<?= $joueur['id'] ?>" class="btn btn-sm btn-primary">
                            Voir
                        </a>
                    </td>

                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

<?php endif; ?>