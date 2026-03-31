<?php
require 'db_config.php';
session_start();

$prop_id = $_GET['id'];
$action = $_GET['action'];

if ($action == 'accepter') {
  
    $stmt = $pdo->prepare("UPDATE propositions SET statut = 'accepte' WHERE id = ?");
    $stmt->execute([$prop_id]);

    $stmt = $pdo->prepare("SELECT article_id FROM propositions WHERE id = ?");
    $stmt->execute([$prop_id]);
    $article_id = $stmt->fetchColumn();

    $pdo->prepare("UPDATE articles SET statut = 'en_cours' WHERE id = ?")->execute([$article_id]);
    $pdo->prepare("UPDATE propositions SET statut = 'rejete' WHERE article_id = ? AND id != ?")->execute([$article_id, $prop_id]);

    echo "<script>alert('Offre acceptée ! L\'acheteur va recevoir votre contact.'); window.location='dashboard_menage.php';</script>";

} else {
    
    $pdo->prepare("UPDATE propositions SET statut = 'rejete' WHERE id = ?")->execute([$prop_id]);
    header('Location: dashboard_menage.php');
}
?>