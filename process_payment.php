<?php
require 'db_config.php';
require 'vendor/autoload.php'; 

$id = $_POST['article_id'];
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();


\FedaPay\FedaPay::setApiKey("sk_test_votre_cle_secrete");
\FedaPay\FedaPay::setEnvironment("sandbox"); 

try {
    $transaction = \FedaPay\Transaction::create([
        "description" => "Achat de : " . $article['titre'],
        "amount" => $article['prix_estime'],
        "currency" => ["iso" => "XOF"],
        "callback_url" => "https://votre-site.com/callback.php?id=" . $id, 
        "customer" => [
            "firstname" => "Client",
            "lastname" => "EcoPreserve",
            "email" => "client@email.com", 
            "phone_number" => [
                "number" => $_POST['phone'],
                "country" => "bj"
            ]
        ]
    ]);

    $token = $transaction->generateToken();
    header("Location: " . $token->url);

} catch (Exception $e) {
    echo "Erreur lors de la création de la transaction : " . $e->getMessage();
}