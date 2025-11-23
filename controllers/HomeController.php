<?php

class HomeController
{
    public function home()
    {
        ob_start();

        # Charge la vue spécifique
        require __DIR__ . '/../views/home.php';

        # Charge $content
        $content = ob_get_clean();

        # Charge le layout global
        require __DIR__ . '/../views/layout.php';
    }
}
