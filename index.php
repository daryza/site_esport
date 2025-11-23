<?php
session_start();
require_once __DIR__ . '/config/database.php';
$page = $_GET['page'] ?? 'home';
# $_SESSION['user_id'] = 1;   # test


#routeur

switch ($page) {

    case 'login':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    case 'logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'register':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;

    case 'account':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->account();
        break;

    case 'update_pseudo':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->updatePseudo();
        break;

    case 'update_password':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->updatePassword();
        break;

    case 'delete_account':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->deleteAccount();
        break;

    case 'joueurs':
        require_once __DIR__ . '/controllers/JoueurController.php';
        $controller = new JoueurController();
        $controller->index();
        break;

    case 'mes_joueurs':
        require_once __DIR__ . '/controllers/JoueurController.php';
        $controller = new JoueurController();
        $controller->mesJoueurs();
        break;

    case 'create_joueur':
        require_once __DIR__ . '/controllers/JoueurController.php';
        $controller = new JoueurController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } else {
            $controller->create();
        }
        break;

    case 'show_joueur':
        require_once __DIR__ . '/controllers/JoueurController.php';
        $controller = new JoueurController();
        $controller->show();
        break;

    case 'edit_joueur':
        require_once __DIR__ . '/controllers/JoueurController.php';
        $controller = new JoueurController();
        $controller->edit();
        break;

    case 'update_joueur':
        require_once __DIR__ . '/controllers/JoueurController.php';
        $controller = new JoueurController();
        $controller->update();
        break;

    case 'delete_joueur':
        require_once __DIR__ . '/controllers/JoueurController.php';
        $controller = new JoueurController();
        $controller->delete();
        break;

    case 'admin':
        require_once __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->index();
        break;

    case 'delete_user':
        require_once __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->deleteUser();
        break;


    default:
        require_once __DIR__ . '/controllers/HomeController.php';
        $controller = new HomeController();
        $controller->home();
        break;
}
