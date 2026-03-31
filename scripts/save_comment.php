<?php
require '../db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $message = htmlspecialchars($_POST['message']);
    $note = (int)$_POST['note'];

    $stmt = $pdo->prepare("INSERT INTO commentaires (pseudo, message, note, date_envoi) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$pseudo, $message, $note]);

    header('Location: ../index.php?success=1');
}