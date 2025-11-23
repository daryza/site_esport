<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <title>Esport Player</title>

    <!-- Chargement du CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>

    <!-- Menu sur toutes les pages - Fichier séparé -->
    <header>
        <?php include __DIR__ . '/partials/navbar.php'; ?>
    </header>

    <!-- Contenu spécifique à chaque page -->
    <main class="container mt-4">
        <?= $content ?>
    </main>

    <!-- Chargement du JS de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>