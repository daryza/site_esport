<?php

class AuthController
{
    public function login()
    {
        global $pdo;  # Connexion PDO

        $error = null;
        $pseudo = '';

        # Si le formulaire est soumis (POST), on traite la connexion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = trim($_POST['pseudo'] ?? '');
            $password = $_POST['password'] ?? '';

            # Vérifications des champs
            if ($pseudo === '' || $password === '') {
                $error = "Veuillez remplir tous les champs.";
            } else {
                # Cherche l'utilisateur en base
                $stmt = $pdo->prepare("SELECT * FROM user WHERE pseudo = :pseudo");
                $stmt->execute(['pseudo' => $pseudo]);
                $user = $stmt->fetch();

                # Si l'utilisateur existe et que le mot de passe est correct
                if ($user && password_verify($password, $user['mdp_hash'])) {

                    # On stocke les infos en session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role_id'] = $user['role_id'];
                    $_SESSION['pseudo'] = $user['pseudo'];

                    # Redirection vers la page d'accueil
                    header('Location: index.php?page=home');
                    exit;
                } else {
                    $error = "Identifiants invalides.";
                }
            }
        }

        # Affichage de la vue login
        ob_start();
        require __DIR__ . '/../views/auth/login.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layout.php';
    }

    public function logout()
    {
        # Efface les données de session liées à l'utilisateur
        $_SESSION = [];
        session_regenerate_id(true);

        # Redirection vers l'accueil
        header('Location: index.php?page=home');
        exit;
    }

    public function register()
    {
        global $pdo;

        $error = null;
        $success = null;
        $pseudo = '';

        # Si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $pseudo = trim($_POST['pseudo'] ?? '');
            $password = $_POST['password'] ?? '';
            $password2 = $_POST['password2'] ?? '';

            # Vérification des champs
            if ($pseudo === '' || $password === '' || $password2 === '') {
                $error = "Veuillez remplir tous les champs.";
            } elseif ($password !== $password2) {
                $error = "Les mots de passe ne correspondent pas.";
            } else {
                # Vérifier si le pseudo existe déjà
                $stmt = $pdo->prepare("SELECT id FROM user WHERE pseudo = :pseudo");
                $stmt->execute(['pseudo' => $pseudo]);
                if ($stmt->fetch()) {
                    $error = "Ce pseudo est déjà utilisé.";
                } else {
                    # Hash du mot de passe
                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    # Insertion
                    $stmt = $pdo->prepare("
                        INSERT INTO user (pseudo, mdp_hash, role_id)
                        VALUES (:pseudo, :mdp_hash, 2)
                    ");
                    $stmt->execute([
                        'pseudo' => $pseudo,
                        'mdp_hash' => $hash
                    ]);

                    $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
                }
            }
        }

        # Affichage de la vue
        ob_start();
        require __DIR__ . '/../views/auth/register.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layout.php';
    }

    public function account()
    {
        # Doit être connecté
        requireLogin();

        $userId = $_SESSION['user_id'];
        $roleId = $_SESSION['role_id'];

        # Messages éventuels
        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['success'], $_SESSION['error']);

        global $pdo;

        # Récupérer l'utilisateur
        $stmt = $pdo->prepare("SELECT id, pseudo, role_id FROM user WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();

        $editMode = isset($_GET['edit']);


        if (!$user) {
            die("Utilisateur introuvable.");
        }

        ob_start();
        require __DIR__ . '/../views/auth/account.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layout.php';
    }

    public function updatePseudo()
    {
        requireLogin();

        global $pdo;


        $newPseudo = trim($_POST['pseudo'] ?? '');

        if ($newPseudo === '' || strlen($newPseudo) < 3 || strlen($newPseudo) > 20) {
            $_SESSION['error'] = "Le pseudo doit contenir entre 3 et 20 caractères.";
            header("Location: index.php?page=account");
            exit;
        }

        # Vérifier si pseudo déjà utilisé
        $stmt = $pdo->prepare("SELECT id FROM user WHERE pseudo = :pseudo AND id != :id");
        $stmt->execute([
            'pseudo' => $newPseudo,
            'id' => $_SESSION['user_id']
        ]);

        if ($stmt->fetch()) {
            $_SESSION['error'] = "Ce pseudo est déjà utilisé.";
            header("Location: index.php?page=account");
            exit;
        }

        # Update
        $stmt = $pdo->prepare("UPDATE user SET pseudo = :pseudo WHERE id = :id");
        $stmt->execute([
            'pseudo' => $newPseudo,
            'id' => $_SESSION['user_id']
        ]);

        # Mettre à jour la session
        $_SESSION['pseudo'] = $newPseudo;

        $_SESSION['success'] = "Pseudo mis à jour.";
        header("Location: index.php?page=account");
        exit;
    }

    public function updatePassword()
    {
        requireLogin();

        global $pdo;


        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $new2 = $_POST['new_password2'] ?? '';

        if ($new === '' || $new2 === '' || $current === '') {
            $_SESSION['errorPass'] = "Veuillez remplir tous les champs.";
            header("Location: index.php?page=account#password");
            exit;
        }

        if ($new !== $new2) {
            $_SESSION['errorPass'] = "Les mots de passe ne correspondent pas.";
            header("Location: index.php?page=account#password");
            exit;
        }

        if (strlen($new) < 5) {
            $_SESSION['errorPass'] = "Le mot de passe doit faire au moins 5 caractères.";
            header("Location: index.php?page=account#password");
            exit;
        }

        # Vérifier l'ancien mot de passe
        $stmt = $pdo->prepare("SELECT mdp_hash FROM user WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (!password_verify($current, $user['mdp_hash'])) {
            $_SESSION['errorPass'] = "Ancien mot de passe incorrect.";
            header("Location: index.php?page=account#password");
            exit;
        }

        # Hash
        $hash = password_hash($new, PASSWORD_DEFAULT);

        # Update
        $stmt = $pdo->prepare("UPDATE user SET mdp_hash = :hash WHERE id = :id");
        $stmt->execute([
            'hash' => $hash,
            'id' => $_SESSION['user_id']
        ]);

        $_SESSION['successPass'] = "Mot de passe mis à jour.";
        header("Location: index.php?page=account#password");
        exit;
    }


    public function deleteAccount()
    {
        requireLogin();

        global $pdo;

        $userId = $_SESSION['user_id'];
        $roleId = $_SESSION['role_id'];

        # Admin impossible à supprimer
        if ($roleId == 1) {
            $_SESSION['error'] = "Impossible de supprimer le compte administrateur.";
            header("Location: index.php?page=account");
            exit;
        }

        # Supprimer les joueurs créés par ce user (ON DELETE CASCADE existe)
        $stmt = $pdo->prepare("DELETE FROM user WHERE id = :id");
        $stmt->execute(['id' => $userId]);

        # Déconnexion
        $_SESSION = [];
        session_regenerate_id(true);

        header("Location: index.php?page=home");
        exit;
    }

}
