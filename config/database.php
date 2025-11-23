<?php
#config/database.php

require_once __DIR__ . '/.env.php'; #Charge paramètres du .env

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,       # exception levée en cas d'erreurs
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  # fetch propre
            PDO::ATTR_EMULATE_PREPARES => false                # sécurité (requetes préparées)
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
