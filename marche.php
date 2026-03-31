<?php
require 'db_config.php';

$stmt = $pdo->query("SELECT * FROM articles WHERE statut = 'disponible' ORDER BY est_reconditionne DESC, id DESC");
$articles = $stmt->fetchAll();

function formatCFA($prix) {
    return number_format($prix, 0, '.', ' ') . ' FCFA';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Marché EcoPreserve - Articles Reconditionnés</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 font-['Plus_Jakarta_Sans']">
    <nav class="bg-slate-900 p-4 text-white flex justify-between items-center">
        <a href="index.php" class="font-bold text-xl">♻️ EcoPreserve</a>
        <a href="index.php" class="text-sm border border-white/20 px-4 py-2 rounded-lg">Retour Accueil</a>
    </nav>

    <main class="max-w-7xl mx-auto py-12 px-6">
        <h2 class="text-4xl font-black text-slate-900 mb-2">Le Marché Circulaire</h2>
        <p class="text-slate-500 mb-10">Trouvez des pépites reconditionnées par nos experts au Bénin.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($articles as $item): ?>
                <div class="bg-white rounded-[2rem] p-4 shadow-sm border border-slate-200">
                    <img src="uploads/<?= $item['photo'] ?>" class="w-full h-48 object-cover rounded-2xl mb-4">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg"><?= htmlspecialchars($item['titre']) ?></h3>
                        <span class="text-green-600 font-black"><?= formatCFA($item['prix_estime']) ?></span>
                    </div>
                    
                    <?php if ($item['est_reconditionne']): ?>
                        <div class="mb-4 text-[10px] font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded inline-block">✨ RECONDITIONNÉ PAR UN PRO</div>
                        <a href="paiement.php?id=<?= $item['id'] ?>" class="block w-full text-center bg-green-500 text-white font-bold py-3 rounded-xl hover:bg-green-600 transition">
                            🛒 ACHETER MAINTENANT
                        </a>
                    <?php else: ?>
                        <div class="mb-4 text-[10px] font-bold text-orange-500 bg-orange-50 px-2 py-1 rounded inline-block">📦 OCCASION (À RÉPARER)</div>
                        <a href="contact_vendeur.php?id=<?= $item['id'] ?>" class="block w-full text-center bg-slate-100 text-slate-700 font-bold py-3 rounded-xl hover:bg-slate-200 transition">
                            CONTACTER LE VENDEUR
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>