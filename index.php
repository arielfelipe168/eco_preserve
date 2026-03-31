<?php
require 'db_config.php';


$stmt = $pdo->query("
    SELECT * FROM articles 
    WHERE statut = 'disponible' 
    AND (id_reparateur IS NULL OR est_reconditionne = TRUE)
    ORDER BY id DESC LIMIT 6
");
$vitrine = $stmt->fetchAll();

function formatCFA($prix) {
    return number_format($prix, 0, '.', ' ') . ' FCFA';
}

$total_sauves = $pdo->query("SELECT COUNT(*) FROM articles WHERE est_reconditionne = TRUE OR statut = 'livre'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoPreserve - Économie Circulaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-mesh {
            background-color: #f8fafc;
            background-image: radial-gradient(at 0% 0%, rgba(34, 197, 94, 0.05) 0px, transparent 50%), 
                              radial-gradient(at 100% 0%, rgba(59, 130, 246, 0.05) 0px, transparent 50%);
        }
    </style>
</head>
<body class="gradient-mesh">

    <nav class="bg-slate-900 p-4 flex flex-wrap justify-between items-center shadow-xl sticky top-0 z-50">
        <h1 class="text-xl md:text-2xl font-extrabold text-white tracking-tighter flex items-center shrink-0">
            <a href="index.php" class="flex items-center">
                <span class="text-green-400 mr-1.5 md:mr-2">♻️</span>EcoPreserve
            </a>
        </h1>

        <div class="flex items-center space-x-3 md:space-x-6">
            <a href="login.php" class="text-slate-300 font-medium hover:text-white transition text-[12px] md:text-sm">
                Connexion
            </a>
            <a href="register.php" class="bg-green-500 text-white px-3 md:px-6 py-2 rounded-xl font-bold text-[12px] md:text-sm shadow-lg shadow-green-900/20 hover:bg-green-400 transition transform active:scale-95 whitespace-nowrap">
                Rejoindre
            </a>
        </div>
    </nav>

    <header class="relative py-20 px-6 text-center bg-slate-900 text-white rounded-b-[3rem] shadow-2xl">
        <div class="relative z-10 max-w-4xl mx-auto">
            <span class="inline-block bg-white/10 backdrop-blur-md text-green-400 text-[10px] font-black px-4 py-2 rounded-full mb-8 tracking-[0.2em] uppercase border border-white/10">
                Le futur de la réparation au Bénin
            </span>
            <h2 class="text-4xl md:text-6xl font-black mb-8 leading-tight">
                Donnez une seconde chance <br>à vos <span class="text-green-400">objets préférés.</span>
            </h2>
            <p class="text-slate-400 max-w-2xl mx-auto text-lg mb-12">
                Une plateforme simple pour vendre vos objets en panne ou les faire réparer par des experts certifiés.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="register.php" class="bg-green-500 text-white px-8 py-4 rounded-2xl font-black hover:bg-green-400 transition-all shadow-xl shadow-green-500/20">Vendre un objet</a>
                <a href="register.php" class="bg-white/10 backdrop-blur-md text-white border border-white/20 px-8 py-4 rounded-2xl font-black hover:bg-white/20 transition-all">Devenir réparateur</a>
            </div>
        </div>
    </header>

    <div class="max-w-5xl mx-auto -mt-10 grid grid-cols-3 gap-4 px-4 relative z-20">
        <div class="bg-white p-6 rounded-3xl shadow-xl text-center border border-slate-100">
            <div class="text-2xl font-black text-slate-800">+<?= (int)$total_sauves ?></div>
            <div class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Objets sauvés</div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-xl text-center border border-slate-100">
            <div class="text-2xl font-black text-green-500">24h</div>
            <div class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Délai moyen</div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-xl text-center border border-slate-100">
            <div class="text-2xl font-black text-blue-500">100%</div>
            <div class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Garanti pro</div>
        </div>
    </div>

    <section class="max-w-7xl mx-auto py-20 px-6">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h3 class="text-3xl font-black text-slate-900">Catalogue Récent</h3>
                <div class="h-1.5 w-20 bg-green-500 rounded-full mt-2"></div>
            </div>
            <a href="marche.php" class="hidden md:block text-slate-500 font-bold hover:text-green-600 transition">Explorer tout →</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($vitrine as $item): ?>
            <div class="group bg-slate-100/50 rounded-[2.5rem] p-3 border border-slate-200/60 hover:bg-white hover:shadow-2xl hover:shadow-slate-200 transition-all duration-500">
                <div class="relative h-64 rounded-[2rem] overflow-hidden shadow-inner">
                    <img src="uploads/<?= htmlspecialchars($item['photo']) ?>" class="h-full w-full object-cover group-hover:scale-110 transition duration-700">
                    
                    <div class="absolute top-4 left-4">
                        <?php if (isset($item['est_reconditionne']) && $item['est_reconditionne']): ?>
                            <span class="bg-slate-900/80 backdrop-blur text-blue-400 text-[9px] font-black px-3 py-1.5 rounded-lg border border-blue-400/30">✨ PRO RECONDITIONNÉ</span>
                        <?php else: ?>
                            <span class="bg-white/90 backdrop-blur text-orange-600 text-[9px] font-black px-3 py-1.5 rounded-lg shadow-sm">📦 OCCASION</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-extrabold text-slate-800 text-lg"><?= htmlspecialchars($item['titre']) ?></h4>
                        <span class="bg-green-100 text-green-700 text-[10px] font-black px-2 py-1 rounded-md">
                            <?= formatCFA($item['prix_estime'] ?? 0) ?>
                        </span>
                    </div>
                    
                    <p class="text-slate-500 text-sm line-clamp-2 mb-6 italic font-medium">
                        "<?= htmlspecialchars($item['description']) ?>"
                    </p>

                    <?php if ($item['est_reconditionne']): ?>
                        <a href="checkout.php?id=<?= $item['id'] ?>" class="w-full flex items-center justify-center bg-green-500 text-white font-bold text-xs py-4 rounded-2xl hover:bg-green-600 transition-colors shadow-lg shadow-green-500/20">
                            🛒 ACHETER L'ARTICLE
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="w-full flex items-center justify-center bg-slate-900 text-white font-bold text-xs py-4 rounded-2xl group-hover:bg-green-500 transition-colors">
                            VOIR LES DÉTAILS
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="py-24 bg-slate-900 text-white overflow-hidden relative">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-3 gap-12 text-center">
            <div class="relative p-8 rounded-3xl bg-white/5 border border-white/10">
                <div class="text-5xl mb-6">📸</div>
                <h3 class="font-black text-xl mb-4 text-green-400">Photographiez</h3>
                <p class="text-slate-400 text-sm leading-relaxed">Postez l'objet cassé qui traîne dans vos placards en quelques secondes.</p>
            </div>
            <div class="relative p-8 rounded-3xl bg-white/5 border border-white/10">
                <div class="text-5xl mb-6">🤝</div>
                <h3 class="font-black text-xl mb-4 text-blue-400">Négociez</h3>
                <p class="text-slate-400 text-sm leading-relaxed">Discutez avec des réparateurs locaux et recevez des offres en FCFA.</p>
            </div>
            <div class="relative p-8 rounded-3xl bg-white/5 border border-white/10">
                <div class="text-5xl mb-6">🌱</div>
                <h3 class="font-black text-xl mb-4 text-yellow-400">Recyclez</h3>
                <p class="text-slate-400 text-sm leading-relaxed">Évitez la décharge et gagnez de l'argent tout en sauvant la planète.</p>
            </div>
        </div>
    </section>

    <section class="max-w-4xl mx-auto py-20 px-6">
        <div class="bg-white rounded-[3rem] p-8 md:p-12 shadow-2xl border border-slate-100">
            <h3 class="text-2xl font-black text-slate-900 mb-2">Votre avis compte 💬</h3>
            <p class="text-slate-500 mb-8 text-sm">Partagez votre expérience avec EcoPreserve pour nous aider à grandir.</p>
            
            <form action="scripts/save_comment.php" method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="pseudo" placeholder="Votre nom ou pseudo" required 
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:border-green-500 outline-none transition">
                    <select name="note" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:border-green-500 outline-none transition">
                        <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                        <option value="4">⭐⭐⭐⭐ (Très bien)</option>
                        <option value="3">⭐⭐⭐ (Moyen)</option>
                        <option value="2">⭐⭐ (Décevant)</option>
                        <option value="1">⭐ (Mauvais)</option>
                    </select>
                </div>
                <textarea name="message" rows="4" placeholder="Votre commentaire..." required
                    class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:border-green-500 outline-none transition"></textarea>
                <button type="submit" class="w-full bg-slate-900 text-white font-bold py-4 rounded-2xl hover:bg-green-500 transition shadow-lg">
                    Envoyer mon avis
                </button>
            </form>
        </div>
    </section>

    <footer class="py-12 text-center bg-slate-50 border-t border-slate-200 mt-10">
        <div class="flex justify-center space-x-6 mb-4">
            <a href="legal.php" class="text-slate-500 hover:text-slate-800 text-[10px] font-black uppercase tracking-widest">Mentions Légales</a>
            <a href="legal.php" class="text-slate-500 hover:text-slate-800 text-[10px] font-black uppercase tracking-widest">CGU</a>
            <a href="mailto:contact@ecopreserve.bj" class="text-slate-500 hover:text-slate-800 text-[10px] font-black uppercase tracking-widest">Support</a>
        </div>
        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">&copy; 2026 EcoPreserve • Cotonou, Bénin</p>
    </footer>

</body>
</html>