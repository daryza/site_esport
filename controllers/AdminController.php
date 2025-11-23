<?php

class AdminController
{
    public function index()
    {
        # Vérification ADMIN obligatoire
        # Un utilisateur non admin ou non connecté est redirigé
        requireAdmin();

        global $pdo;

        # Récupérer tous les utilisateurs + nombre de joueurs créés
        $query = "
            SELECT 
                user.id,
                user.pseudo,
                user.role_id,
                (
                    SELECT COUNT(*) FROM player WHERE player.user_id = user.id
                ) AS total_joueurs
            FROM user
            ORDER BY user.role_id ASC, user.pseudo ASC
        ";

        $statement = $pdo->query($query);    # Exécution de la requête
        $users = $statement->fetchAll();            # Tableau associatif contenant tous les users


        # Chargement de la vue Admin
        ob_start();
        require __DIR__ . '/../views/admin/index.php';
        $content = ob_get_clean();

        # Inclusion du layout dans la vue
        require __DIR__ . '/../views/layout.php';
    }


    public function deleteUser()
    {
        # Vérif admin
        requireAdmin();

        if (empty($_GET['id'])) {
            die("ID utilisateur manquant.");
        }

        $id = (int) $_GET['id'];

        global $pdo;

        # Interdiction de supprimer l'admin
        $stmt = $pdo->prepare("SELECT role_id FROM user WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        if (!$user) {
            die("Utilisateur introuvable.");
        }

        if ($user['role_id'] == 1) {
            die("Impossible de supprimer le compte administrateur.");
        }

        # Suppression user ( et ses joueurs)
        $stmt = $pdo->prepare("DELETE FROM user WHERE id = :id");
        $stmt->execute(['id' => $id]);

        header("Location: index.php?page=admin");
        exit;
    }
}