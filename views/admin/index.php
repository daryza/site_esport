<h1 class="mb-4">Administration – Liste des utilisateurs</h1>

<table class="table table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Pseudo</th>
            <th>Rôle</th>
            <th>Nb joueurs créés</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['pseudo']) ?></td>
                <td><?= ($u['role_id'] == 1 ? 'Admin' : 'Utilisateur') ?></td>
                <td><?= $u['total_joueurs'] ?></td>

                <td>
                    <?php if ($u['role_id'] != 1): ?>
                        <a href="index.php?page=delete_user&id=<?= $u['id'] ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Supprimer cet utilisateur ? Ses joueurs seront également supprimés.');">
                            Supprimer
                        </a>
                    <?php else: ?>
                        <span class="text-muted">—</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>