<?php
require 'db_config.php';
session_start();

$message = "";
$status = "";
$show_form = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $show_form = true;
    } else {
        $message = "Ce lien est expiré ou invalide.";
        $status = "error";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
    $token = $_POST['token'];
    $new_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $update = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?");
    if ($update->execute([$new_pass, $token])) {
        $message = "Votre mot de passe a été mis à jour avec succès !";
        $status = "success";
        $show_form = false;
    } else {
        $message = "Erreur technique. Veuillez réessayer.";
        $status = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation - EcoPreserve</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl w-full max-w-md border border-slate-100">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Nouveau départ</h2>
            <p class="text-slate-400 text-sm mt-2 font-medium">Choisissez un mot de passe robuste.</p>
        </div>

        <?php if($message): ?>
            <div class="<?= $status == 'success' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-600 border-red-200' ?> p-4 rounded-2xl text-xs font-bold mb-6 border text-center">
                <?= $message ?>
                <?php if($status == 'success'): ?>
                    <a href="login.php" class="block mt-4 bg-green-500 text-white py-3 rounded-xl uppercase tracking-tighter">Se connecter</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if($show_form): ?>
        <form action="" method="POST" class="space-y-6">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nouveau mot de passe</label>
                <input type="password" name="password" minlength="6" class="w-full bg-slate-50 border-2 border-slate-50 p-4 rounded-2xl outline-none focus:bg-white focus:border-green-500 transition-all font-medium text-slate-700" required placeholder="••••••••">
            </div>
            <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black shadow-xl hover:bg-green-500 transition-all uppercase tracking-widest text-xs">Mettre à jour</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>