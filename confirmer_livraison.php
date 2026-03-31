<?php
require 'db_config.php';
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'reparateur') {
    header('Location: login.php');
    exit();
}

$article_id = $_GET['id'] ?? null;
$reparateur_id = $_SESSION['user_id'];

if ($article_id) {
    try {
        $pdo->beginTransaction();

        $check = $pdo->prepare("SELECT id FROM articles WHERE id = ? AND id_reparateur = ? AND statut = 'vendu'");
        $check->execute([$article_id, $reparateur_id]);
        
        if ($check->fetch()) {
            $updateArticle = $pdo->prepare("UPDATE articles SET statut = 'livre' WHERE id = ?");
            $updateArticle->execute([$article_id]);

            $updateCommande = $pdo->prepare("UPDATE commandes SET statut_paiement = 'complete' WHERE article_id = ?");
            $updateCommande->execute([$article_id]);

            $pdo->commit();
            header('Location: dashboard_reparateur.php?tab=ventes&success=livraison');
        } else {
            $pdo->rollBack();
            die("Erreur : Cet article ne vous appartient pas ou n'est pas prêt pour la livraison.");
        }

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erreur lors de la confirmation : " . $e->getMessage());
    }
} else {
    header('Location: dashboard_reparateur.php');
}
exit();