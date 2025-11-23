<?php

class AdminController
{
    public function index()
    {
        # Vérification ADMIN obligatoire
        if (empty($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            header("Location: index.php?page=home");
            exit;
        }

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

        $statement = $pdo->query($query);
        $users = $statement->fetchAll();

        ob_start();
        require __DIR__ . '/../views/admin/index.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layout.php';
    }


    public function deleteUser()
    {
        # Vérif admin
        if (empty($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            header("Location: index.php?page=home");
            exit;
        }

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

        # Suppression (cascade supprime ses joueurs)
        $stmt = $pdo->prepare("DELETE FROM user WHERE id = :id");
        $stmt->execute(['id' => $id]);

        header("Location: index.php?page=admin");
        exit;
    }
}