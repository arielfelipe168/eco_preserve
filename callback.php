<?php
require 'db_config.php';

$id_article = $_GET['id'] ?? null;
$status = $_GET['status'] ?? ''; 

if ($id_article && $status === 'approved') {
    

    $stmt = $pdo->prepare("SELECT a.*, u.email as email_reparateur, u.nom as nom_reparateur 
                           FROM articles a 
                           JOIN utilisateurs u ON a.id_reparateur = u.id 
                           WHERE a.id = ?");
    $stmt->execute([$id_article]);
    $article = $stmt->fetch();

    if ($article) {

        $update = $pdo->prepare("UPDATE articles SET statut = 'vendu' WHERE id = ?");
        $update->execute([$id_article]);


        $to = $article['email_reparateur'];
        $subject = "🔥 Bonne nouvelle ! Un article a été vendu sur EcoPreserve";
        
        $message = "
        <html>
        <head>
          <title>Vente Confirmée</title>
        </head>
        <body style='font-family: Arial; line-height: 1.6;'>
          <h2>Félicitations " . $article['nom_reparateur'] . " !</h2>
          <p>Votre article <strong>" . $article['titre'] . "</strong> vient d'être acheté par un client.</p>
          <p><strong>Montant à recevoir :</strong> " . number_format($article['prix_estime'], 0, '.', ' ') . " FCFA</p>
          <hr>
          <p><strong>Action requise :</strong> Veuillez préparer l'article pour la livraison. Un agent EcoPreserve ou le client vous contactera sous peu.</p>
          <br>
          <p>Merci de contribuer à l'économie circulaire au Bénin !</p>
          <p><em>L'équipe EcoPreserve</em></p>
        </body>
        </html>
        ";


        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'From: EcoPreserve <noreply@ecopreserve.bj>';

        mail($to, $subject, $message, implode("\r\n", $headers));


        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-slate-50 flex items-center justify-center h-screen">
            <div class="text-center p-10 bg-white rounded-[3rem] shadow-2xl max-w-md">
                <div class="text-6xl mb-6">🎉</div>
                <h1 class="text-3xl font-black text-slate-900 mb-4">Paiement Réussi !</h1>
                <p class="text-slate-500 mb-8">Merci pour votre achat. Le réparateur a été informé et prépare votre colis pour une livraison sous 24h.</p>
                <a href="index.php" class="bg-green-500 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-green-200">Retour à l'accueil</a>
            </div>
        </body>
        </html>
        <?php
    }
} else {
    echo "Le paiement n'a pas pu être validé ou a été annulé.";
}
?>