<?php
require 'db_config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        
        if (isset($user['est_bloque']) && $user['est_bloque'] == 1) {
            header('Location: login.php?error=account_blocked');
            exit();
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header('Location: admin_panel.php');
        } elseif ($user['role'] == 'menage') {
            header('Location: dashboard_menage.php');
        } else {
            header('Location: dashboard_reparateur.php');
        }
        exit();
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - EcoPreserve</title>
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
        <a href="register.php" class="bg-green-500 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-green-400 transition">S'inscrire</a>
    </nav>

    <main class="flex-grow flex items-center justify-center p-6">
        <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl w-full max-w-md border border-slate-100 relative overflow-hidden">
            
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-50 rounded-full blur-3xl"></div>

            <div class="relative z-10 text-center mb-10">
                <div class="inline-block p-4 bg-slate-50 rounded-2xl mb-4">
                    <span class="text-3xl">🔑</span>
                </div>
                <h2 class="text-3xl font-black text-slate-800">Bon retour !</h2>
                <p class="text-slate-400 text-sm mt-2 font-medium">Connectez-vous pour gérer vos objets.</p>
            </div>

            <?php if(isset($_GET['error'])): ?>
                <div class="bg-red-100 text-red-600 p-4 rounded-2xl mb-6 text-xs font-bold text-center border border-red-200">
                    <?php 
                        if($_GET['error'] == 'account_blocked') echo "🚫 Votre compte a été suspendu par l'administrateur.";
                        if($_GET['error'] == 'invalid_credentials') echo "❌ Email ou mot de passe incorrect.";
                        if($_GET['error'] == 'access_denied') echo "🛡️ Accès réservé aux administrateurs.";
                    ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['reg'])): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-2xl text-xs font-bold mb-6 text-center animate-bounce">
                    ✨ Inscription réussie ! Connectez-vous.
                </div>
            <?php endif; ?>

            <?php if(isset($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-2xl text-xs font-bold mb-6 text-center">
                    ⚠️ <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-5">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Adresse Email</label>
                    <input type="email" name="email"  
                           class="w-full bg-slate-50 border-2 border-slate-50 p-4 rounded-2xl outline-none focus:bg-white focus:border-green-500 transition-all font-medium text-slate-700" required>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2 ml-1">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400">Mot de passe</label>
                        <a href="forgot_password.php" class="text-[10px] font-bold text-green-600 hover:text-slate-900 transition-colors uppercase tracking-tight">Mot de passe oublié ?</a>
                    </div>
                    <input type="password" name="password"  
                           class="w-full bg-slate-50 border-2 border-slate-50 p-4 rounded-2xl outline-none focus:bg-white focus:border-green-500 transition-all font-medium text-slate-700" required>
                </div>

                <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black shadow-xl shadow-slate-200 hover:bg-green-500 transition-all transform active:scale-95 duration-300 uppercase tracking-widest text-xs">
                    Se connecter
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-slate-50 text-center">
                <p class="text-sm text-slate-400 font-medium">
                    Pas encore de compte ? 
                    <a href="register.php" class="text-green-600 font-black hover:underline">Inscrivez-vous ici</a>
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