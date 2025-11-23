<?php

class JoueurController
{
    public function create()
    {
        requireLogin();
        # old = valeurs précédemment saisies en cas d'erreur
        $old = $_SESSION['old'] ?? [];
        $error = $_SESSION['error'] ?? null;

        # On nettoie ce qu'on a stocké
        unset($_SESSION['old'], $_SESSION['error']);

        ob_start();
        require __DIR__ . '/../views/joueurs/create.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layout.php';
    }

    public function store()
    {
        global $pdo;

        # Récupération des champs
        $nom = trim($_POST['nom'] ?? '');
        $age = (int) ($_POST['age'] ?? 0);
        $taille = (int) ($_POST['taille'] ?? 0);
        $poids = (int) ($_POST['poids'] ?? 0);
        $nationalite = trim($_POST['nationalite'] ?? '');
        $poste = trim($_POST['poste'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        # Stocker old values (pour réafficher le formulaire en cas d'erreur)
        $_SESSION['old'] = $_POST;

        # VALIDATIONS
        if ($nom === '' || strlen($nom) < 3 || strlen($nom) > 20) {
            return $this->error("Le nom doit contenir entre 3 et 20 caractères.");
        }

        if (!preg_match('/^[A-Za-z0-9 _-]+$/', $nom)) {
            return $this->error("Le nom contient des caractères invalides.");
        }

        if ($age < 18 || $age > 99) {
            return $this->error("L'âge doit être entre 18 et 99 ans.");
        }

        if ($taille < 140 || $taille > 240) {
            return $this->error("La taille doit être entre 140 et 240 cm.");
        }

        if ($poids < 40 || $poids > 150) {
            return $this->error("Le poids doit être entre 40 et 150 kg.");
        }

        if ($nationalite === '') {
            return $this->error("Veuillez sélectionner une nationalité.");
        }

        if ($poste === '') {
            return $this->error("Veuillez sélectionner un poste.");
        }

        if (strlen($bio) > 300) {
            return $this->error("La bio ne doit pas dépasser 300 caractères.");
        }

        # Vérifier si nom déjà utilisé
        $stmt = $pdo->prepare("SELECT id FROM player WHERE nom = :nom");
        $stmt->execute(['nom' => $nom]);

        if ($stmt->fetch()) {
            return $this->error("Ce nom est déjà utilisé.");
        }

        # UPLOAD PHOTO
        $photoName = null;

        if (!empty($_FILES['photo']['name'])) {

            $file = $_FILES['photo'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            # Format autorisé 
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                return $this->error("La photo doit être en PNG ou JPG.");
            }

            # Taille max : 2 Mo
            if ($file['size'] > 2 * 1024 * 1024) {
                return $this->error("La photo dépasse 2 Mo.");
            }

            # Dossier d'upload
            $destination = __DIR__ . '/../public/uploads/players/';
            if (!is_dir($destination)) {
                mkdir($destination, 0777, true);
            }

            # Nom unique
            $photoName = 'player_' . uniqid() . '.' . $ext;

            # Move upload
            if (!move_uploaded_file($file['tmp_name'], $destination . $photoName)) {
                return $this->error("Erreur lors de l'upload de la photo.");
            }
        }

        # INSERTION EN BDD
        $stmt = $pdo->prepare("
            INSERT INTO player (nom, age, taille, poids, nationalite, poste, bio, photo_path, user_id)
            VALUES (:nom, :age, :taille, :poids, :nationalite, :poste, :bio, :photo, :user)
        ");

        $stmt->execute([
            'nom' => $nom,
            'age' => $age,
            'taille' => $taille,
            'poids' => $poids,
            'nationalite' => $nationalite,
            'poste' => $poste,
            'bio' => $bio,
            'photo' => $photoName,
            'user' => $_SESSION['user_id']
        ]);

        # Récupère l'ID du joueur créé
        $id = $pdo->lastInsertId();

        # Nettoyage des old values
        unset($_SESSION['old']);

        # Redirection vers la fiche joueur
        header("Location: index.php?page=show_joueur&id=$id");
        exit;
    }

    private function error($message)
    {
        $_SESSION['error'] = $message;
        header("Location: index.php?page=create_joueur");
        exit;
    }

    public function show()
    {
        global $pdo;

        # Vérifier que l'ID du joueur est présent
        if (empty($_GET['id'])) {
            die("ID du joueur manquant.");
        }

        $id = (int) $_GET['id'];

        # Récupérer les infos du joueur
        $stmt = $pdo->prepare("
            SELECT p.*, u.pseudo AS createur
            FROM player p
            JOIN user u ON p.user_id = u.id
            WHERE p.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $joueur = $stmt->fetch();

        if (!$joueur) {
            die("Joueur introuvable.");
        }

        $loggedId = $_SESSION['user_id'] ?? null;
        $loggedRole = $_SESSION['role_id'] ?? null;

        # Permissions : admin OU créateur du joueur
        $peutModifier = ($loggedRole == 1 || $loggedId == $joueur['user_id']);

        ob_start();
        require __DIR__ . '/../views/joueurs/show.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layout.php';
    }

    public function mesJoueurs()
    {
        # L’utilisateur doit être connecté
        requireLogin();

        global $pdo;

        $userId = $_SESSION['user_id'];

        # Récupérer les joueurs créés par ce user
        $stmt = $pdo->prepare("
            SELECT * 
            FROM player
            WHERE user_id = :id
            ORDER BY created_at DESC
        ");
        $stmt->execute(['id' => $userId]);
        $joueurs = $stmt->fetchAll();

        ob_start();
        require __DIR__ . '/../views/joueurs/mes_joueurs.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layout.php';
    }

    public function index()
    {
        global $pdo;

        # Récupération de tous les joueurs avec le pseudo du créateur
        $query = "
            SELECT 
                player.*, 
                user.pseudo AS createur
            FROM player
            INNER JOIN user ON player.user_id = user.id
            ORDER BY player.created_at DESC
        ";

        $statement = $pdo->query($query);
        $joueurs = $statement->fetchAll();

        ob_start();
        require __DIR__ . '/../views/joueurs/index.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layout.php';
    }

    public function edit()
    {
        requireLogin();

        global $pdo;

        # Vérifier ID
        if (empty($_GET['id'])) {
            die("ID du joueur manquant.");
        }

        $joueurId = (int) $_GET['id'];
        $loggedUserId = $_SESSION['user_id'];
        $loggedRole = $_SESSION['role_id'];

        # Récupérer le joueur
        $stmt = $pdo->prepare("SELECT * FROM player WHERE id = :id");
        $stmt->execute(['id' => $joueurId]);
        $joueur = $stmt->fetch();

        if (!$joueur) {
            die("Joueur introuvable.");
        }

        # Vérifier permissions
        $peutModifier = ($loggedRole == 1 || $loggedUserId == $joueur['user_id']);

        if (!$peutModifier) {
            die("Accès non autorisé.");
        }

        # Messages éventuels
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        ob_start();
        require __DIR__ . '/../views/joueurs/edit.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layout.php';
    }

    public function update()
    {
        # Vérifier connexion
        requireLogin();

        global $pdo;

        if (empty($_GET['id'])) {
            die("ID du joueur manquant.");
        }

        $joueurId = (int) $_GET['id'];

        # Récupérer le joueur
        $stmt = $pdo->prepare("SELECT * FROM player WHERE id = :id");
        $stmt->execute(['id' => $joueurId]);
        $joueur = $stmt->fetch();

        if (!$joueur) {
            die("Joueur introuvable.");
        }

        $loggedUserId = $_SESSION['user_id'];
        $loggedRole = $_SESSION['role_id'];
        $peutModifier = ($loggedRole == 1 || $loggedUserId == $joueur['user_id']);

        if (!$peutModifier) {
            die("Accès non autorisé.");
        }

        # Récupérer les champs
        $nom = trim($_POST['nom'] ?? '');
        $age = (int) ($_POST['age'] ?? 0);
        $taille = (int) ($_POST['taille'] ?? 0);
        $poids = (int) ($_POST['poids'] ?? 0);
        $nationalite = trim($_POST['nationalite'] ?? '');
        $poste = trim($_POST['poste'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        # VALIDATIONS (exactement comme store)
        if ($nom === '' || strlen($nom) < 3 || strlen($nom) > 20) {
            return $this->redirectError("Le nom doit contenir entre 3 et 20 caractères.");
        }

        if (!preg_match('/^[A-Za-z0-9 _-]+$/', $nom)) {
            return $this->redirectError("Le nom contient des caractères invalides.");
        }

        # Vérifier nom unique sauf pour SON joueur
        $stmt = $pdo->prepare("SELECT id FROM player WHERE nom = :nom AND id != :id");
        $stmt->execute(['nom' => $nom, 'id' => $joueurId]);
        if ($stmt->fetch()) {
            return $this->redirectError("Ce nom est déjà utilisé.");
        }

        if ($age < 18 || $age > 99)
            return $this->redirectError("Âge invalide.");
        if ($taille < 140 || $taille > 240)
            return $this->redirectError("Taille invalide.");
        if ($poids < 40 || $poids > 150)
            return $this->redirectError("Poids invalide.");

        if (strlen($bio) > 300)
            return $this->redirectError("Bio trop longue.");

        # Upload photo (optionnel)
        $photoName = $joueur['photo_path']; # garder ancienne par défaut

        if (!empty($_FILES['photo']['name'])) {

            $file = $_FILES['photo'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                return $this->redirectError("La photo doit être en PNG ou JPG.");
            }

            if ($file['size'] > 2 * 1024 * 1024) {
                return $this->redirectError("La photo dépasse 2 Mo.");
            }

            $destination = __DIR__ . '/../public/uploads/players/';
            if (!is_dir($destination))
                mkdir($destination, 0777, true);

            # Supprimer ancienne photo si présente
            if (!empty($joueur['photo_path'])) {
                $oldPath = $destination . $joueur['photo_path'];
                if (file_exists($oldPath))
                    unlink($oldPath);
            }

            $photoName = 'player_' . uniqid() . '.' . $ext;

            if (!move_uploaded_file($file['tmp_name'], $destination . $photoName)) {
                return $this->redirectError("Erreur lors de l'upload de la photo.");
            }
        }

        # UPDATE
        $stmt = $pdo->prepare("
            UPDATE player
            SET nom = :nom, age = :age, taille = :taille, poids = :poids,
                nationalite = :nationalite, poste = :poste, bio = :bio, photo_path = :photo
            WHERE id = :id
        ");

        $stmt->execute([
            'nom' => $nom,
            'age' => $age,
            'taille' => $taille,
            'poids' => $poids,
            'nationalite' => $nationalite,
            'poste' => $poste,
            'bio' => $bio,
            'photo' => $photoName,
            'id' => $joueurId
        ]);

        header("Location: index.php?page=show_joueur&id=$joueurId");
        exit;
    }

    private function redirectError($message)
    {
        $_SESSION['error'] = $message;
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }


    public function delete()
    {
        # Vérifier connexion
        requireLogin();

        global $pdo;

        # L’ID doit exister dans l’URL
        if (empty($_GET['id'])) {
            die("ID du joueur manquant.");
        }

        $joueurId = (int) $_GET['id'];
        $loggedUserId = $_SESSION['user_id'];
        $loggedRole = $_SESSION['role_id'];

        # Récupérer le joueur pour vérifier permissions
        $statement = $pdo->prepare("
            SELECT * FROM player
            WHERE id = :id
        ");
        $statement->execute(['id' => $joueurId]);
        $joueur = $statement->fetch();

        if (!$joueur) {
            die("Joueur introuvable.");
        }

        # Vérifier permission : admin OU créateur
        $peutSupprimer = ($loggedRole == 1 || $loggedUserId == $joueur['user_id']);

        if (!$peutSupprimer) {
            die("Vous n'avez pas les droits pour supprimer ce joueur.");
        }

        # Supprimer la photo si existe
        if (!empty($joueur['photo_path'])) {
            $photoPath = __DIR__ . '/../public/uploads/players/' . $joueur['photo_path'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        # Supprimer en base
        $statement = $pdo->prepare("DELETE FROM player WHERE id = :id");
        $statement->execute(['id' => $joueurId]);

        # Redirection
        header("Location: index.php?page=mes_joueurs");
        exit;
    }

}
