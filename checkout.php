<?php
require 'db_config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: marche.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ? AND statut = 'disponible'");
$stmt->execute([$id]);
$item = $stmt->fetch();


if (!$item) {
    die("Désolé, cet article n'est plus disponible.");
}

function formatCFA($prix) {
    return number_format($prix, 0, '.', ' ') . ' FCFA';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finaliser l'achat - EcoPreserve</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 font-['Plus_Jakarta_Sans']">

    <div class="max-w-4xl mx-auto py-12 px-6">
        <a href="marche.php" class="text-slate-500 hover:text-slate-900 font-bold text-sm flex items-center mb-8">
            ← Retour au marché
        </a>

        <div class="grid md:grid-cols-2 gap-12">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                <h2 class="text-2xl font-black text-slate-900 mb-6">Votre commande</h2>
                
                <div class="relative h-64 rounded-3xl overflow-hidden mb-6">
                    <img src="uploads/<?= $item['photo'] ?>" class="w-full h-full object-cover">
                    <div class="absolute top-4 left-4 bg-blue-500 text-white text-[10px] font-black px-3 py-1.5 rounded-lg shadow-lg">
                        ✨ RECONDITIONNÉ
                    </div>
                </div>

                <h3 class="text-xl font-extrabold text-slate-800 mb-2"><?= htmlspecialchars($item['titre']) ?></h3>
                <p class="text-slate-500 text-sm mb-6 line-clamp-3 italic">"<?= htmlspecialchars($item['description']) ?>"</p>

                <div class="border-t border-dashed border-slate-200 pt-6 space-y-3">
                    <div class="flex justify-between text-slate-500">
                        <span>Prix de l'article</span>
                        <span><?= formatCFA($item['prix_estime']) ?></span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Livraison (Cotonou)</span>
                        <span class="text-green-500 font-bold text-xs uppercase tracking-widest">Gratuit</span>
                    </div>
                    <div class="flex justify-between text-xl font-black text-slate-900 pt-4">
                        <span>Total</span>
                        <span class="text-green-600"><?= formatCFA($item['prix_estime']) ?></span>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <form action="process_payment.php" method="POST" id="checkout-form" class="bg-slate-900 p-8 rounded-[2.5rem] text-white shadow-2xl shadow-green-900/20">
                    <h2 class="text-xl font-black mb-8 flex items-center">
                        <span class="bg-green-500/20 text-green-400 p-2 rounded-lg mr-3">📍</span> 
                        Infos de livraison
                    </h2>

                    <input type="hidden" name="article_id" value="<?= $item['id'] ?>">
                    <input type="hidden" name="montant" value="<?= $item['prix_estime'] ?>">
                    
                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="lng">

                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Nom Complet</label>
                            <input type="text" name="nom" required placeholder="Ex: Jean Houndété" 
                                class="w-full bg-white/5 border border-white/10 p-4 rounded-2xl outline-none focus:border-green-500 focus:bg-white/10 transition">
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Quartier / Adresse Précise</label>
                            <input type="text" name="adresse" required placeholder="Ex: Fidjrossè, Pavé Fidjrossè" 
                                class="w-full bg-white/5 border border-white/10 p-4 rounded-2xl outline-none focus:border-green-500 focus:bg-white/10 transition">
                        </div>

                        <div class="pt-2">
                            <button type="button" onclick="getLocation()" id="loc-btn" class="w-full flex items-center justify-center gap-3 bg-white/5 border border-dashed border-white/20 p-4 rounded-2xl hover:bg-white/10 transition group">
                                <span class="text-xl group-hover:animate-bounce">📍</span>
                                <div class="text-left">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-green-400" id="loc-status">Partager ma position GPS</p>
                                    <p class="text-[9px] text-slate-500 italic">Pour une livraison plus précise</p>
                                </div>
                            </button>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Numéro Mobile Money (Bénin)</label>
                            <div class="flex gap-2">
                                <span class="bg-white/5 border border-white/10 p-4 rounded-2xl flex items-center text-xs font-bold text-slate-400">+229</span>
                                <input type="tel" name="phone" required placeholder="67000000" 
                                    class="flex-1 bg-white/5 border border-white/10 p-4 rounded-2xl outline-none focus:border-green-500 focus:bg-white/10 transition">
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 space-y-4">
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-400 text-white font-black py-5 rounded-2xl transition shadow-xl shadow-green-500/20 transform active:scale-95">
                            PAYER <?= formatCFA($item['prix_estime']) ?>
                        </button>
                        
                        <div class="flex items-center justify-center gap-4 opacity-50 grayscale">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/9/93/Mtn-logo.webp" class="h-6">
                            <span class="text-[10px] font-bold">MOOV MONEY</span>
                        </div>
                    </div>
                </form>

                <div class="p-6 bg-blue-50 rounded-3xl border border-blue-100">
                    <p class="text-blue-800 text-xs leading-relaxed">
                        <strong>Note :</strong> Une fois le paiement validé, le réparateur recevra votre position GPS pour vous livrer exactement là où vous êtes.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function getLocation() {
            const status = document.getElementById('loc-status');
            const btn = document.getElementById('loc-btn');

            if (navigator.geolocation) {
                status.innerText = "Localisation en cours...";
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        document.getElementById('lat').value = position.coords.latitude;
                        document.getElementById('lng').value = position.coords.longitude;
                        
                        status.innerText = "Position GPS enregistrée ✅";
                        status.classList.remove('text-green-400');
                        status.classList.add('text-blue-400');
                        btn.classList.add('border-blue-500/50', 'bg-blue-500/5');
                    },
                    (error) => {
                        status.innerText = "Erreur : Permission refusée ❌";
                        console.error(error);
                    }
                );
            } else {
                status.innerText = "GPS non supporté par votre navigateur";
            }
        }
    </script>
</body>
</html>