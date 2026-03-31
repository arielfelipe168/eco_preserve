<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php?error=unauthorized');
    exit();
}

$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_articles = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();

$stmt_users = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$all_users = $stmt_users->fetchAll();

$stmt_comm = $pdo->query("SELECT * FROM commentaires ORDER BY date_envoi DESC");
$all_comments = $stmt_comm->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EcoPreserve - Administration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 font-['Plus_Jakarta_Sans']">

    <nav class="bg-slate-900 p-6 text-white flex justify-between items-center shadow-2xl">
        <div class="flex items-center">
            <span class="text-2xl mr-3">🛡️</span>
            <h1 class="text-xl font-black tracking-tighter uppercase">Panel Contrôle <span class="text-green-400">EcoPreserve</span></h1>
        </div>
        <div class="flex items-center space-x-4">
            <span class="text-slate-400 text-sm">Admin: <?= htmlspecialchars($_SESSION['nom']) ?></span>
            <a href="logout.php" class="bg-red-500/10 text-red-400 px-4 py-2 rounded-xl text-xs font-bold hover:bg-red-500 hover:text-white transition">Déconnexion</a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 px-6">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <div class="text-slate-400 text-xs font-black uppercase mb-2">Utilisateurs inscrits</div>
                <div class="text-4xl font-black text-slate-900"><?= $total_users ?></div>
            </div>
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <div class="text-slate-400 text-xs font-black uppercase mb-2">Articles en base</div>
                <div class="text-4xl font-black text-green-500"><?= $total_articles ?></div>
            </div>
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <div class="text-slate-400 text-xs font-black uppercase mb-2">Région</div>
                <div class="text-4xl font-black text-blue-500">Bénin</div>
            </div>
        </div>

        <section class="mb-12">
            <h2 class="text-2xl font-black text-slate-800 mb-6">Membres de la plateforme</h2>
            <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-slate-100">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="p-6 text-xs font-black uppercase text-slate-400">Utilisateur & Email</th>
                            <th class="p-6 text-xs font-black uppercase text-slate-400">Rôle</th>
                            <th class="p-6 text-xs font-black uppercase text-slate-400">Statut</th>
                            <th class="p-6 text-xs font-black uppercase text-slate-400 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php foreach($all_users as $user): ?>
                        <tr>
                            <td class="p-6">
                                <div class="font-bold text-slate-800"><?= htmlspecialchars($user['nom']) ?></div>
                                <div class="text-xs text-blue-500 font-medium"><?= htmlspecialchars($user['email']) ?></div>
                            </td>
                            <td class="p-6">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black <?= $user['role'] == 'admin' ? 'bg-purple-100 text-purple-600' : ($user['role'] == 'reparateur' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600') ?>">
                                    <?= strtoupper($user['role'] ?? 'CLIENT') ?>
                                </span>
                            </td>
                            <td class="p-6">
                                <?php if(isset($user['est_bloque']) && $user['est_bloque']): ?>
                                    <span class="text-red-500 text-xs font-bold">🚫 BLOQUÉ</span>
                                <?php else: ?>
                                    <span class="text-green-500 text-xs font-bold">✅ ACTIF</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-6 text-right">
                                <?php if($user['id'] != $_SESSION['user_id']): ?>
                                <a href="scripts/admin_actions.php?action=toggle_block&id=<?= $user['id'] ?>" class="inline-block p-2 bg-slate-100 rounded-lg hover:bg-slate-200 transition text-xs font-bold">
                                    ⚙️ Gérer
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section>
            <h2 class="text-2xl font-black text-slate-800 mb-6">Derniers Avis Clients</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach($all_comments as $c): ?>
                <div class="bg-white p-8 rounded-[2rem] shadow-lg border border-slate-100 relative">
                    <div class="flex justify-between items-start mb-4">
                        <div class="font-black text-slate-900"><?= htmlspecialchars($c['pseudo']) ?></div>
                        <div class="text-yellow-400 text-xs"><?= str_repeat('★', $c['note']) ?></div>
                    </div>
                    <p class="text-slate-500 text-sm italic mb-6">
                        "<?= htmlspecialchars_decode(htmlspecialchars($c['message'])) ?>"
                    </p>
                    <div class="flex justify-between items-center border-t border-slate-50 pt-4">
                        <span class="text-[10px] text-slate-300 font-bold"><?= $c['date_envoi'] ?></span>
                        <a href="scripts/admin_actions.php?action=delete_comment&id=<?= $c['id'] ?>" onclick="return confirm('Supprimer ce commentaire ?')" class="text-red-400 text-[10px] font-black hover:text-red-600 uppercase tracking-widest">Supprimer</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

    </main>

</body>
</html>