<?php

function requireAdmin()
{
    if (empty($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
        header("Location: index.php?page=home");
        exit;
    }
}

function requireLogin()
{
    if (empty($_SESSION['user_id'])) {
        header("Location: index.php?page=login");
        exit;
    }
}
