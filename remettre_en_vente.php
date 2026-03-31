<?php
require 'db_config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'reparateur') {
    header('Location: login.php');
    exit();
}

$article_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nouveau_prix = $_POST['prix_vente'];
    $nouvelle_desc = $_POST['description'];
    $reparateur_id = $_SESSION['user_id']; 

    $stmt = $pdo->prepare("UPDATE articles SET 
        statut = 'disponible', 
        prix_estime = ?, 
        description = ?, 
        est_reconditionne = TRUE,
        type_action = 'vente',
        id_reparateur = ? 
        WHERE id = ?");
    
    $stmt->execute([$nouveau_prix, $nouvelle_desc, $reparateur_id, $article_id]);

    header('Location: dashboard_reparateur.php?msg=revendu');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remise en vente - EcoPreserve</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-0 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100">
        
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-white text-center">
            <div class="inline-block bg-white/20 p-3 rounded-2xl backdrop-blur-md mb-4">
                <span class="text-3xl">✨</span>
            </div>
            <h2 class="text-2xl font-black uppercase tracking-tight">Objet Réparé !</h2>
            <p class="text-blue-100 text-sm mt-1 opacity-90">Préparez la fiche pour sa nouvelle vie</p>
        </div>

        <div class="p-8">
            <div class="flex items-center gap-4 mb-8 p-3 bg-slate-50 rounded-2xl border border-slate-100">
                <img src="uploads/<?= $article['photo'] ?>" class="w-16 h-16 rounded-xl object-cover shadow-sm">
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase">Article source</p>
                    <h3 class="font-bold text-slate-800"><?= htmlspecialchars($article['titre']) ?></h3>
                </div>
            </div>
            
            <form action="" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nouveau Prix de Vente (FCFA)</label>
                    <div class="relative">
                        <input type="number" name="prix_vente" step="1" 
                               class="w-full border-2 border-slate-100 p-4 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-xl font-black text-blue-700" 
                               placeholder="Ex: 15000" required>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 font-black text-slate-300">FCFA</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Détails du reconditionnement</label>
                    <textarea name="description" rows="5" 
                              class="w-full border-2 border-slate-100 p-4 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-slate-600 italic" 
                              required><?= htmlspecialchars($article['description']) ?>&#10;&#10;---&#10;🛠️ RECONDITIONNÉ : L'objet a été entièrement révisé et remis à neuf par un professionnel.</textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-indigo-700 text-white py-4 rounded-2xl font-black text-lg shadow-xl shadow-blue-200 transition-all transform active:scale-95">
                    🚀 Publier l'annonce pro
                </button>

                <a href="dashboard_reparateur.php" class="block text-center text-sm font-bold text-slate-400 hover:text-slate-600 transition">
                    Annuler et retourner au dashboard
                </a>
            </form>
        </div>
    </div>

</body>
</html>