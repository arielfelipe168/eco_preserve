<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions Légales & CGU - EcoPreserve</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-700 leading-relaxed">

    <nav class="bg-slate-900 p-4 shadow-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-extrabold text-white tracking-tighter">
                <a href="index.php" class="flex items-center"><span class="text-green-400 mr-2">♻️</span>EcoPreserve</a>
            </h1>
            <a href="index.php" class="text-slate-400 hover:text-white text-sm font-bold transition">Retour à l'accueil</a>
        </div>
    </nav>

    <header class="bg-slate-900 py-16 px-6 text-center text-white">
        <h2 class="text-4xl font-black mb-4">Cadre Juridique</h2>
        <p class="text-slate-400 max-w-2xl mx-auto">Consultez les mentions légales et conditions d'utilisation d'EcoPreserve, une initiative indépendante dédiée à la durabilité.</p>
    </header>

    <main class="max-w-4xl mx-auto p-6 md:p-12 -mt-10">
        
        <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl mb-10 border border-slate-100">
            <h3 class="text-2xl font-black text-slate-900 mb-6 flex items-center">
                <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3 text-sm font-bold">01</span>
                Mentions Légales
            </h3>
            <div class="space-y-4 text-sm md:text-base">
                <p><strong>Propriétaire du projet :</strong> EcoPreserve est une initiative personnelle indépendante.</p>
                <p><strong>Localisation :</strong> Cotonou, République du Bénin.</p>
                <p><strong>Contact direct :</strong> contact@ecopreserve.com</p>
                <p><strong>Hébergement :</strong> Hostinger </p>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-100">
            <h3 class="text-2xl font-black text-slate-900 mb-6 flex items-center">
                <span class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-3 text-sm font-bold">02</span>
                Conditions Générales d'Utilisation (CGU)
            </h3>
            
            <div class="space-y-8">
                <section>
                    <h4 class="font-bold text-slate-800 uppercase text-xs tracking-widest mb-3">1. Nature du service</h4>
                    <p class="text-sm">EcoPreserve est une plateforme web visant à mettre en relation des citoyens béninois pour la réparation et le reconditionnement d'appareils, afin de réduire les déchets électroniques.</p>
                </section>

                <section class="bg-red-50 p-6 rounded-2xl border border-red-100">
                    <h4 class="font-bold text-red-800 uppercase text-xs tracking-widest mb-3 flex items-center">
                        <span class="mr-2">🛡️</span> 2. Sécurité et Droit de Bannissement
                    </h4>
                    <p class="text-sm text-red-900 mb-3 font-medium">Pour garantir la sécurité de tous, l'administrateur se réserve le droit de suspendre ou de bannir un compte en cas de :</p>
                    <ul class="text-xs space-y-2 text-red-800 list-disc ml-5 font-semibold">
                        <li>Signalements répétés par d'autres utilisateurs pour comportement suspect.</li>
                        <li>Non-respect manifeste des engagements pris entre les parties.</li>
                        <li>Propos injurieux ou tentatives de fraude sur la plateforme.</li>
                    </ul>
                    <p class="text-[10px] mt-4 text-red-700 italic">Le bannissement entraîne la désactivation immédiate de l'accès à l'espace membre.</p>
                </section>

                <section>
                    <h4 class="font-bold text-slate-800 uppercase text-xs tracking-widest mb-3">3. Protection des données (Vie Privée)</h4>
                    <p class="text-sm">Les données collectées (Email, Nom) sont uniquement utilisées pour la mise en relation sur le site. Aucune donnée n'est revendue à des tiers. Vous pouvez demander la suppression de votre compte via votre profil.</p>
                </section>

                <section>
                    <h4 class="font-bold text-slate-800 uppercase text-xs tracking-widest mb-3">4. Responsabilité</h4>
                    <p class="text-sm">En tant que plateforme de mise en relation, EcoPreserve ne peut être tenu responsable de la qualité des réparations ou des transactions privées entre membres. Nous encourageons les utilisateurs à être vigilants.</p>
                </section>
            </div>
        </div>
    </main>

    <footer class="py-12 text-center">
        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">© 2026 EcoPreserve • Projet Indépendant • Bénin</p>
    </footer>

</body>
</html>