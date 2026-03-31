<?php
require 'db_config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'menage') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];


function formatCFA($prix) {
    return number_format($prix, 0, '.', ' ') . ' FCFA';
}

$stmt = $pdo->prepare("
    SELECT a.*, p.id as prop_id, p.offre_prix, p.message, p.statut as prop_statut, u.nom as reparateur_nom 
    FROM articles a
    LEFT JOIN propositions p ON a.id = p.article_id
    LEFT JOIN users u ON p.reparateur_id = u.id
    WHERE a.user_id = ?
    ORDER BY a.id DESC
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_GROUP); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace - EcoPreserve</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <nav class="bg-slate-900 p-4 flex justify-between items-center shadow-xl sticky top-0 z-50">
        <h1 class="text-xl font-extrabold text-white tracking-tighter flex items-center">
            <a href="index.php" class="flex items-center"><span class="text-green-400 mr-2">♻️</span>EcoPreserve</a>
        </h1>
        <div class="flex items-center space-x-4">
            <a href="ajouter_article.php" class="bg-green-500 text-white px-4 py-2 rounded-xl font-bold text-xs shadow-lg hover:bg-green-400 transition transform active:scale-95">
                + Publier
            </a>
            <a href="logout.php" class="text-slate-400 text-xs font-bold hover:text-white transition">Déconnexion</a>
        </div>
    </nav>

    <main class="p-6 max-w-6xl mx-auto">
        <header class="mb-10 mt-4">
            <span class="text-green-600 text-[10px] font-black uppercase tracking-[0.2em]">Tableau de bord</span>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Suivi de mes annonces</h2>
        </header>

        <?php if (empty($items)): ?>
            <div class="bg-white p-12 text-center rounded-[2.5rem] shadow-xl border border-slate-100">
                <div class="text-5xl mb-4">📦</div>
                <p class="text-slate-500 font-medium">Vous n'avez pas encore posté d'article.</p>
                <a href="ajouter_article.php" class="inline-block mt-6 text-green-600 font-black text-sm underline">Commencer maintenant</a>
            </div>
        <?php endif; ?>

        <?php foreach ($items as $article_id => $propositions): 
            $first = $propositions[0]; 
        ?>
            <div class="bg-white rounded-[2.5rem] shadow-xl mb-10 overflow-hidden border border-slate-100 transition-all hover:shadow-2xl hover:shadow-slate-200/50">
                <div class="md:flex">
                    <div class="md:w-1/3 relative h-64 md:h-auto">
                        <img src="uploads/<?= $first['photo'] ?>" class="h-full w-full object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-slate-900/80 backdrop-blur text-white px-3 py-1.5 rounded-lg text-[10px] font-black tracking-widest uppercase">
                                <?= $first['type_action'] ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-8 md:w-2/3">
                        <div class="flex justify-between items-start mb-6">
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight"><?= htmlspecialchars($first['titre']) ?></h3>
                        </div>
                        
                        <div class="mb-8 p-4 bg-slate-50 rounded-2xl flex items-center justify-between border border-slate-100">
                            <div class="flex items-center">
                                <span class="bg-white p-2 rounded-xl shadow-sm mr-3">📍</span>
                                <span class="text-xs font-bold text-slate-600 tracking-tight">Localisation de l'objet</span>
                            </div>
                            <?php if (empty($first['latitude'])): ?>
                                <button onclick="envoyerMaPosition(<?= $article_id ?>)" 
                                        class="bg-blue-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-500 transition-all shadow-lg shadow-blue-100 flex items-center">
                                    Partager ma position
                                </button>
                            <?php else: ?>
                                <span class="text-green-600 text-[10px] font-black uppercase tracking-widest flex items-center bg-green-50 px-3 py-2 rounded-xl">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg>
                                    Transmise
                                </span>
                            <?php endif; ?>
                        </div>

                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Offres des réparateurs</h4>
                            <div class="space-y-4">
                                <?php foreach ($propositions as $prop): if (!$prop['prop_id']) continue; ?>
                                    <div class="flex items-center justify-between p-5 rounded-2xl border-2 transition-all <?= $prop['prop_statut'] == 'accepte' ? 'border-green-500 bg-green-50/50' : 'bg-slate-50 border-slate-50' ?>">
                                        <div>
                                            <p class="font-black text-xl text-slate-800"><?= formatCFA($prop['offre_prix']) ?></p>
                                            <p class="text-xs text-slate-500 font-medium italic mt-1">"<?= htmlspecialchars($prop['message']) ?>"</p>
                                            <p class="text-[9px] text-slate-400 mt-2 uppercase font-black tracking-widest">Par : <?= htmlspecialchars($prop['reparateur_nom']) ?></p>
                                        </div>
                                        
                                        <div class="flex flex-col items-end">
                                            <?php if ($prop['prop_statut'] == 'attente'): ?>
                                                <div class="flex gap-2">
                                                    <a href="traitement_offre.php?id=<?= $prop['prop_id'] ?>&action=accepter" 
                                                       class="bg-green-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-green-500 shadow-lg shadow-green-100 transition-all">Accepter</a>
                                                    <a href="traitement_offre.php?id=<?= $prop['prop_id'] ?>&action=rejeter" 
                                                       class="bg-white text-red-500 border border-red-100 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-50 transition-all">Refuser</a>
                                                </div>
                                            <?php else: ?>
                                                <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest <?= $prop['prop_statut'] == 'accepte' ? 'bg-green-200 text-green-800' : 'bg-red-100 text-red-600' ?>">
                                                    <?= $prop['prop_statut'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; if (!$propositions[0]['prop_id']) echo "<div class='text-center py-6 border-2 border-dashed border-slate-100 rounded-2xl text-slate-400 text-[10px] font-bold uppercase tracking-widest'>Aucune offre pour le moment</div>"; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <footer class="py-12 text-center bg-slate-50">
        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">&copy; 2026 EcoPreserve • Économie Circulaire au Bénin</p>
    </footer>

    <script>
    function envoyerMaPosition(articleId) {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'save_location.php';

                const fields = {
                    'lat': lat,
                    'lng': lng,
                    'article_id': articleId
                };

                for (const [key, value] of Object.entries(fields)) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }

                document.body.appendChild(form);
                form.submit();
            }, function(error) {
                alert("Erreur : Impossible de récupérer votre position. Vérifiez vos autorisations.");
            });
        } else {
            alert("Votre navigateur ne supporte pas la géolocalisation.");
        }
    }
    </script>
</body>
</html>