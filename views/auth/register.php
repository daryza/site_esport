<div class="row justify-content-center">
    <div class="col-md-6">

        <h1 class="mb-4">Créer un compte</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php?page=register">

            <div class="mb-3">
                <label class="form-label">Pseudo</label>
                <input type="text" class="form-control" name="pseudo" value="<?= htmlspecialchars($pseudo ?? '') ?>"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmer le mot de passe</label>
                <input type="password" class="form-control" name="password2" required>
            </div>

            <button class="btn btn-primary" type="submit">
                Créer le compte
            </button>

        </form>

    </div>
</div>