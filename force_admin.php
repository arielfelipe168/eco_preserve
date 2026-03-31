<?php
require 'db_config.php';

try {

    $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = 10");
    $stmt->execute();

    echo "<h1 style='color:green; font-family:sans-serif;'>🚀 Opération terminée pour l'ID 10 !</h1>";
    echo "<p>Même si MySQL dit '0 lignes modifiées', votre compte est maintenant configuré.</p>";
    echo "<p><b>Étape cruciale :</b> Déconnectez-vous et reconnectez-vous pour actualiser vos droits.</p>";
    echo "<hr>";

    $check = $pdo->query("SELECT email, role FROM users WHERE id = 10")->fetch();
    echo "Statut actuel en base de données : <br>";
    echo "Email : <b>" . $check['email'] . "</b><br>";
    echo "Rôle : <b style='color:blue;'>" . $check['role'] . "</b>";

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}