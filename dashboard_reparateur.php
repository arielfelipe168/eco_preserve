<?php
require 'db_config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'reparateur') {
    header('Location: login.php');
    exit();
}

$reparateur_id = $_SESSION['user_id'];
$tab = $_GET['tab'] ?? 'flux';

$stmtStats = $pdo->prepare("SELECT SUM(prix_estime) as total FROM articles WHERE id_reparateur = ? AND (statut = 'vendu' OR statut = 'livre')");
$stmtStats->execute([$reparateur_id]);
$statsGains = $stmtStats->fetch();
$totalGains = $statsGains['total'] ?? 0;

$stmtCount = $pdo->prepare("SELECT COUNT(*) as nb FROM articles WHERE id_reparateur = ? AND (statut = 'vendu' OR statut = 'livre')");
$stmtCount->execute([$reparateur_id]);
$statsCount = $stmtCount->fetch();
$totalVentes = $statsCount['nb'] ?? 0;

$stmtMissions = $pdo->prepare("SELECT COUNT(*) as nb FROM articles a JOIN propositions p ON a.id = p.article_id WHERE p.reparateur_id = ? AND p.statut = 'accepte' AND a.statut = 'en_cours'");
$stmtMissions->execute([$reparateur_id]);
$nbMissions = $stmtMissions->fetch()['nb'] ?? 0;


if ($tab === 'missions') {

    $stmt = $pdo->prepare("
        SELECT a.*, u.nom, p.statut as prop_statut 
        FROM articles a
        JOIN users u ON a.user_id = u.id 
        JOIN propositions p ON a.id = p.article_id
        WHERE p.reparateur_id = ? AND p.statut = 'accepte' AND a.statut = 'en_cours'
        ORDER BY a.id DESC
    ");
    $stmt->execute([$reparateur_id]);
} elseif ($tab === 'ventes') {

    $stmt = $pdo->prepare("
        SELECT a.*, c.nom_client as nom, c.telephone as contact_client, c.latitude, c.longitude, c.adresse
        FROM articles a
        JOIN commandes c ON a.id = c.article_id
        WHERE a.id_reparateur = ? AND a.statut = 'vendu' AND c.statut_paiement = 'paye'
        ORDER BY c.id DESC
    ");
    $stmt->execute([$reparateur_id]);
} else {

    $stmt = $pdo->prepare("
        SELECT a.*, u.nom, p.statut as prop_statut 
        FROM articles a
        JOIN users u ON a.user_id = u.id 
        LEFT JOIN propositions p ON a.id = p.article_id AND p.reparateur_id = ?
        WHERE a.statut = 'disponible' 
        AND a.id_reparateur IS NULL 
        AND a.est_reconditionne = 0
        ORDER BY a.id DESC
    ");
    $stmt->execute([$reparateur_id]);
}

$articles = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoPreserve - Espace Réparateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    
    <nav class="bg-slate-900 p-4 flex justify-between items-center shadow-xl sticky top-0 z-50">
        <h1 class="text-xl font-extrabold text-white tracking-tighter flex items-center">
            <a href="index.php" class="flex items-center"><span class="text-blue-400 mr-2">🛠️</span>EcoPreserve <span class="ml-2 text-[10px] bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded-md uppercase tracking-widest">Pro</span></a>
        </h1>
        <div class="flex items-center space-x-4">
            <span class="text-slate-400 text-xs font-bold hidden md:block">Expert : <?= htmlspecialchars($_SESSION['nom']) ?></span>
            <a href="logout.php" class="bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">Quitter</a>
        </div>
    </nav>

    <main class="p-6 max-w-7xl mx-auto">

        <?php if (isset($_GET['success']) && $_GET['success'] === 'livraison'): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl font-bold text-sm animate-pulse">
                ✅ Livraison confirmée ! L'article a été archivé.
            </div>
        <?php endif; ?>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Gains cumulés</p>
                <h3 class="text-2xl font-black text-slate-900"><?= number_format($totalGains, 0, '.', ' ') ?> <span class="text-sm text-green-500">FCFA</span></h3>
            </div>
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Articles vendus</p>
                <h3 class="text-2xl font-black text-slate-900"><?= $totalVentes ?> <span class="text-sm text-blue-500">Objets</span></h3>
            </div>
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Missions en cours</p>
                <h3 class="text-2xl font-black text-slate-900"><?= $nbMissions ?> <span class="text-sm text-orange-500">Atelier</span></h3>
            </div>
        </section>

        <header class="mb-8 mt-4 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="text-blue-600 text-[10px] font-black uppercase tracking-[0.2em]">Flux de travail</span>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">
                    <?php 
                        if($tab === 'missions') echo 'Mes Missions en cours';
                        elseif($tab === 'ventes') echo 'Ventes à livrer 📦';
                        else echo 'Flux des opportunités';
                    ?>
                </h2>
            </div>

            <div class="flex bg-slate-200/50 p-1 rounded-2xl w-fit overflow-x-auto">
                <a href="?tab=flux" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all <?= $tab === 'flux' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' ?>">Opportunités</a>
                <a href="?tab=missions" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all <?= $tab === 'missions' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' ?>">Missions</a>
                <a href="?tab=ventes" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all <?= $tab === 'ventes' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' ?>">Ventes <span class="ml-1 bg-green-500 text-white px-1.5 py-0.5 rounded-full text-[8px]">New</span></a>
            </div>
        </header>
        
        <?php if (empty($articles)): ?>
            <div class="bg-white rounded-[2.5rem] p-20 text-center border-2 border-dashed border-slate-200">
                <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">Aucun article trouvé dans cette section</p>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($articles as $article): ?>
            <div class="group bg-white rounded-[2.5rem] shadow-sm border border-slate-200/60 overflow-hidden hover:shadow-2xl transition-all duration-500">
                <div class="relative">
                    <img src="uploads/<?= $article['photo'] ?>" class="w-full h-60 object-cover group-hover:scale-105 transition duration-700">
                    
                    <div class="absolute top-4 right-4 flex flex-col gap-2 items-end">
                        <?php if($tab === 'ventes'): ?>
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black shadow-lg bg-green-500 text-white">💰 PAYÉ</span>
                        <?php else: ?>
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black shadow-lg backdrop-blur-md <?= $article['type_action'] == 'vente' ? 'bg-orange-500/90 text-white' : 'bg-purple-600/90 text-white' ?>">
                                <?= strtoupper($article['type_action']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="font-black text-xl text-slate-800 leading-tight mb-2"><?= htmlspecialchars($article['titre']) ?></h3>
                    
                    <p class="text-slate-500 text-sm line-clamp-2 mb-6 font-medium italic">
                        "<?= htmlspecialchars($article['description']) ?>"
                    </p>
                    
                    <div class="bg-slate-50 rounded-2xl p-4 mb-6 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest"><?= $tab === 'ventes' ? 'Acheteur' : 'Client' ?></span>
                            <span class="text-xs font-bold text-slate-700"><?= htmlspecialchars($article['nom']) ?></span>
                        </div>
                        
                        <?php if ($tab !== 'flux'): ?>
                        <div class="flex items-center justify-between border-t border-slate-200/60 pt-3">
                            <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Téléphone</span>
                            <?php $phone = $tab === 'ventes' ? $article['contact_client'] : $article['contact']; ?>
                            <a href="tel:<?= $phone ?>" class="text-xs font-black text-blue-600 hover:underline">
                                📞 <?= htmlspecialchars($phone) ?>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if ($tab === 'ventes' && !empty($article['adresse'])): ?>
                        <div class="flex flex-col border-t border-slate-200/60 pt-3">
                            <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Adresse de livraison</span>
                            <span class="text-[11px] font-medium text-slate-600"><?= htmlspecialchars($article['adresse']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="space-y-3">
                        <?php if ($tab === 'flux'): ?>
                            <a href="proposer_offre.php?id=<?= $article['id'] ?>" 
                               class="block text-center w-full bg-slate-900 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-600 transition-all shadow-xl shadow-slate-200">
                                Faire une offre
                            </a>
                        <?php else: ?>
                            <?php $target_phone = $tab === 'ventes' ? $article['contact_client'] : $article['contact']; ?>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $target_phone) ?>" 
                               target="_blank"
                               class="block text-center w-full bg-green-500 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-green-600 transition-all shadow-lg shadow-green-100">
                               💬 Contacter sur WhatsApp
                            </a>

                            <?php if (!empty($article['latitude'])): ?>
                                <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $article['latitude'] ?>,<?= $article['longitude'] ?>" 
                                   target="_blank" 
                                   class="block text-center w-full bg-white border-2 border-slate-100 text-slate-800 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">
                                   📍 Ouvrir dans Maps
                                </a>
                            <?php else: ?>
                                <div class="text-center py-4 bg-amber-50 rounded-2xl border border-amber-100">
                                    <p class="text-[9px] font-black text-amber-700 uppercase tracking-tighter italic">⏳ Position GPS non reçue</p>
                                </div>
                            <?php endif; ?>

                            <?php if ($tab === 'missions'): ?>
                                <a href="remettre_en_vente.php?id=<?= $article['id'] ?>" 
                                   class="block text-center w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all border-b-4 border-blue-800 shadow-lg shadow-blue-100">
                                    🛠️ Terminer la réparation
                                </a>
                            <?php elseif ($tab === 'ventes'): ?>
                                <a href="confirmer_livraison.php?id=<?= $article['id'] ?>" 
                                   class="block text-center w-full bg-slate-900 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-green-600 transition-all shadow-lg shadow-slate-200">
                                    ✅ Confirmer la livraison
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

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