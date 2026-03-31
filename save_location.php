<?php
require 'db_config.php';
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $lat = isset($_POST['lat']) ? $_POST['lat'] : null;
    $lng = isset($_POST['lng']) ? $_POST['lng'] : null;
    $article_id = isset($_POST['article_id']) ? $_POST['article_id'] : null;

    if ($lat && $lng && $article_id) {
        try {
            
            $stmt = $pdo->prepare("UPDATE articles SET latitude = ?, longitude = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$lat, $lng, $article_id, $_SESSION['user_id']]);

            
            header('Location: dashboard_menage.php?status=pos_ok');
            exit();
        } catch (PDOException $e) {
            die("Erreur lors de l'enregistrement de la position : " . $e->getMessage());
        }
    }
}

header('Location: dashboard_menage.php');
exit();