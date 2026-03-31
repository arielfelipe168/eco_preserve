<?php
session_start();
require '../db_config.php';

// Sécurité : Seul l'admin peut exécuter ces actions
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Accès non autorisé.");
}

$action = $_GET['action'] ?? '';
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) die("ID invalide");

switch ($action) {
    case 'delete_comment':
        $stmt = $pdo->prepare("DELETE FROM commentaires WHERE id = ?");
        $stmt->execute([$id]);
        break;

    case 'toggle_block':
        // On inverse le statut (0 devient 1, 1 devient 0)
        $stmt = $pdo->prepare("UPDATE users SET est_bloque = NOT est_bloque WHERE id = ?");
        $stmt->execute([$id]);
        break;

    case 'delete_user':
        // Supprimer l'utilisateur (Attention : cela peut supprimer ses articles selon vos contraintes SQL)
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        break;
}

header('Location: ../admin_panel.php?msg=success');