<?php
require 'db_config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'reparateur') {
    header('Location: login.php');
    exit();
}

$article_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prix = $_POST['offre_prix'];
    $message = $_POST['message'];
    $reparateur_id = $_SESSION['user_id'];

    $ins = $pdo->prepare("INSERT INTO propositions (article_id, reparateur_id, offre_prix, message) VALUES (?, ?, ?, ?)");
    $ins->execute([$article_id, $reparateur_id, $prix, $message]);

    echo "<script>alert('Offre envoyée avec succès !'); window.location='dashboard_reparateur.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faire une offre - EcoPreserve</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-3xl shadow-xl w-full max-w-md border border-slate-100">
        
        <div class="flex justify-center mb-4">
            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase">
                <?= $article['type_action'] == 'vente' ? 'Achat d\'objet' : 'Service de Réparation' ?>
            </span>
        </div>

        <h2 class="text-2xl font-black text-slate-800 mb-2 text-center">Votre Proposition</h2>
        <p class="text-center text-slate-500 mb-8 text-sm px-4">
            Vous proposez vos services pour : <span class="font-bold text-slate-700"><?= htmlspecialchars($article['titre']) ?></span>
        </p>
        
        <form action="" method="POST" class="space-y-6">
            <div>
                <label class="block text-slate-700 font-semibold mb-2">
                    <?= $article['type_action'] == 'vente' ? 'Prix d\'achat proposé (FCFA)' : 'Coût estimé (FCFA)' ?>
                </label>
                <div class="relative">
                    <input type="number" step="1" name="offre_prix" placeholder="Ex: 5000" required 
                           class="w-full border-slate-200 border-2 p-4 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-lg font-bold">
                    <span class="absolute right-4 top-4 text-slate-400 font-bold">FCFA</span>
                </div>
            </div>

            <div>
                <label class="block text-slate-700 font-semibold mb-2">Message au propriétaire</label>
                <textarea name="message" rows="4" 
                          class="w-full border-slate-200 border-2 p-4 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all" 
                          placeholder="Décrivez votre expertise ou précisez les détails de l'offre..."></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-extrabold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition-all active:scale-95">
                Envoyer la proposition
            </button>
            
            <a href="dashboard_reparateur.php" class="block text-center text-slate-400 text-sm font-medium hover:text-slate-600 transition">
                ← Revenir au catalogue
            </a>
        </form>
    </div>
</body>
</html>