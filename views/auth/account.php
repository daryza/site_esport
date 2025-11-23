<?php $editMode = $editMode ?? false; ?>

<h1 class="mb-4">Mon compte</h1>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>


<!-- MODE NORMAL : juste les infos -->
<?php if (!$editMode): ?>

    <p><strong>Pseudo :</strong> <?= htmlspecialchars($_SESSION['pseudo']) ?></p>
    <p><strong>Rôle :</strong> <?= ($_SESSION['role_id'] == 1 ? 'Admin' : 'Utilisateur') ?></p>

    <a href="index.php?page=account&edit=1" class="btn btn-primary mt-3">
        Modifier mes informations
    </a>

    <!-- MODE ÉDITION : formulaires -->
<?php else: ?>

    <!-- Modifier PSEUDO -->
    <div class="card mb-4">
        <div class="card-body">
            <h4>Modifier mon pseudo</h4>

            <form method="post" action="index.php?page=update_pseudo">
                <div class="mb-3">
                    <label class="form-label">Nouveau pseudo</label>
                    <input type="text" name="pseudo" class="form-control"
                        value="<?= htmlspecialchars($_SESSION['pseudo']) ?>" required>
                </div>

                <button class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>

    <!-- Messages mot de passe -->
    <?php if (!empty($_SESSION['successPass'])): ?>
        <div class="alert alert-success"><?= $_SESSION['successPass'] ?></div>
        <?php unset($_SESSION['successPass']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['errorPass'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['errorPass'] ?></div>
        <?php unset($_SESSION['errorPass']); ?>
    <?php endif; ?>

    <!-- Modifier MDP -->
    <div class="card mb-4">
        <div class="card-body">
            <h4>Modifier mon mot de passe</h4>

            <form method="post" action="index.php?page=update_password">
                <div class="mb-3">
                    <label>Ancien mot de passe</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Nouveau mot de passe</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Confirmation</label>
                    <input type="password" name="new_password2" class="form-control" required>
                </div>

                <button class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>

    <!-- Supprimer compte -->
    <div class="card border-danger">
        <div class="card-body">
            <h4 class="text-danger">Supprimer mon compte</h4>
            <p>Cette action est définitive.</p>

            <?php if ($_SESSION['role_id'] != 1): ?>
                <a href="index.php?page=delete_account" onclick="return confirm('Supprimer votre compte ?');"
                    class="btn btn-danger">Supprimer mon compte</a>
            <?php else: ?>
                <p class="text-muted">Le compte administrateur ne peut pas être supprimé.</p>
            <?php endif; ?>
        </div>
    </div>

    <a href="index.php?page=account" class="btn btn-secondary mt-3">Retour</a>

<?php endif; ?>