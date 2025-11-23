<?php
# Vérifier si l'utilisateur est connecté
$isLogged = !empty($_SESSION['user_id']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <!-- Nom du site -->
        <a class="navbar-brand" href="index.php?page=home">Esport Players</a>

        <!-- Burger (mobile) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenu du menu -->
        <div class="collapse navbar-collapse" id="navbarMain">

            <!-- Liens alignés à gauche -->
            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=home">Accueil</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=joueurs">Les joueurs</a>
                </li>

                <?php if ($isLogged): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=mes_joueurs">Mes joueurs</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=create_joueur">Créer un joueur</a>
                    </li>
                <?php endif; ?>

            </ul>

            <!-- Liens alignés à droite -->
            <ul class="navbar-nav">

                <?php if (!empty($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="index.php?page=admin">
                            Administration
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($isLogged): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=account">Mon compte</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-danger" href="index.php?page=logout">Déconnexion</a>
                    </li>

                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=login">Connexion</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=register">Créer un compte</a>
                    <?php endif; ?>

            </ul>

        </div>
    </div>
</nav>