<?php
require 'db_config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (nom, email, password, role) VALUES (?, ?, ?, ?)");
    try {
        $stmt->execute([$nom, $email, $password, $role]);
        header('Location: login.php?reg=success');
        exit();
    } catch (PDOException $e) {
        $error = "Cet email est déjà utilisé sur EcoPreserve.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - EcoPreserve</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col">

    <nav class="bg-slate-900 p-4 flex justify-between items-center shadow-xl sticky top-0 z-50">
        <h1 class="text-2xl font-extrabold text-white tracking-tighter flex items-center">
            <a href="index.php" class="flex items-center"><span class="text-green-400 mr-2">♻️</span>EcoPreserve</a>
        </h1>
        <a href="login.php" class="text-slate-300 font-bold text-sm hover:text-white transition">Se connecter</a>
    </nav>

    <main class="flex-grow flex items-center justify-center p-6">
        <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl w-full max-w-lg border border-slate-100 relative">
            
            <div class="text-center mb-8">
                <span class="inline-block bg-green-100 text-green-700 text-[10px] font-black px-4 py-1.5 rounded-full mb-4 tracking-widest uppercase">Rejoignez le mouvement</span>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">Créer un compte</h2>
            </div>

            <?php if(isset($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-2xl text-xs font-bold mb-6 text-center">
                    ⚠️ <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nom Complet</label>
                        <input type="text" name="nom"  
                               class="w-full bg-slate-50 border-2 border-slate-50 p-4 rounded-2xl outline-none focus:bg-white focus:border-green-500 transition-all font-medium" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Adresse Email</label>
                        <input type="email" name="email"  
                               class="w-full bg-slate-50 border-2 border-slate-50 p-4 rounded-2xl outline-none focus:bg-white focus:border-green-500 transition-all font-medium" required>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Mot de passe</label>
                    <input type="password" name="password" placeholder="••••••••" 
                           class="w-full bg-slate-50 border-2 border-slate-50 p-4 rounded-2xl outline-none focus:bg-white focus:border-green-500 transition-all font-medium" required>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Je souhaite m'inscrire en tant que :</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="menage" class="peer hidden" checked>
                            <div class="p-4 rounded-2xl border-2 border-slate-50 bg-slate-50 text-center transition-all peer-checked:border-green-500 peer-checked:bg-white peer-checked:shadow-lg">
                                <span class="block text-2xl mb-1">🏠</span>
                                <span class="text-xs font-bold text-slate-600">Ménage</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="reparateur" class="peer hidden">
                            <div class="p-4 rounded-2xl border-2 border-slate-50 bg-slate-50 text-center transition-all peer-checked:border-blue-500 peer-checked:bg-white peer-checked:shadow-lg">
                                <span class="block text-2xl mb-1">🛠️</span>
                                <span class="text-xs font-bold text-slate-600">Réparateur</span>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black shadow-xl hover:bg-green-500 transition-all transform active:scale-95 duration-300 uppercase tracking-widest text-xs">
                    Lancer mon aventure
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-50 text-center">
                <p class="text-sm text-slate-400 font-medium">
                    Vous avez déjà un compte ? 
                    <a href="login.php" class="text-green-600 font-black hover:underline">Connectez-vous</a>
                </p>
            </div>
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