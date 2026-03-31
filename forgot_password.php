<?php
require 'db_config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; 

session_start();

$message = "";
$status = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT id, nom FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expire = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $update = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $update->execute([$token, $expire, $email]);

        $mail = new PHPMailer(true);
        try {
            
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';             
            $mail->SMTPAuth   = true;
            $mail->Username   = 'arielhogbato@gmail.com';     
            $mail->Password   = 'ufsk nggf ruaf hzjm';         
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port       = 587;                         

            $mail->setFrom('arielhogbato@gmail.com', 'EcoPreserve'); 
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Réinitialisation de votre accès - EcoPreserve';
            
            $resetLink = "http://localhost/eco_preserve/reset_password.php?token=" . $token;
            
            $mail->Body = "
            <div style='background-color: #f8fafc; padding: 40px 20px; font-family: sans-serif;'>
                <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);'>
                    <div style='background-color: #0f172a; padding: 30px; text-align: center;'>
                        <h1 style='color: #ffffff; margin: 0; font-size: 24px;'>♻️ EcoPreserve</h1>
                    </div>
                    <div style='padding: 40px;'>
                        <h2 style='color: #1e293b; font-size: 20px; margin-bottom: 16px;'>Bonjour ".htmlspecialchars($user['nom']).",</h2>
                        <p style='color: #64748b; line-height: 1.6;'>Nous avons reçu une demande de réinitialisation de mot de passe pour votre compte EcoPreserve. Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email.</p>
                        <div style='text-align: center; margin: 40px 0;'>
                            <a href='$resetLink' style='background-color: #22c55e; color: #ffffff; padding: 16px 32px; border-radius: 12px; text-decoration: none; font-weight: bold; font-size: 14px; display: inline-block;'>Réinitialiser mon mot de passe</a>
                        </div>
                        <p style='color: #94a3b8; font-size: 12px; text-align: center;'>Ce lien est valable pendant 60 minutes.</p>
                    </div>
                    <div style='background-color: #f1f5f9; padding: 20px; text-align: center; color: #94a3b8; font-size: 11px;'>
                        &copy; 2026 EcoPreserve • Économie Circulaire au Bénin
                    </div>
                </div>
            </div>";

            $mail->send();
            $message = "Vérifiez votre boîte mail. Un lien vous a été envoyé.";
            $status = "success";
        } catch (Exception $e) {
            $message = "L'envoi a échoué. Veuillez réessayer plus tard.";
            $status = "error";
        }
    } else {
        $message = "Cette adresse email n'est pas enregistrée.";
        $status = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - EcoPreserve</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col items-center justify-center p-6">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl w-full max-w-md border border-slate-100 relative">
        <div class="text-center mb-10">
            <div class="inline-block p-4 bg-green-50 rounded-2xl mb-4 text-3xl">📧</div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Oubli ?</h2>
            <p class="text-slate-400 text-sm mt-2 font-medium">Entrez votre email pour recevoir un lien.</p>
        </div>

        <?php if($message): ?>
            <div class="<?= $status == 'success' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-600 border-red-200' ?> p-4 rounded-2xl text-xs font-bold mb-6 border text-center animate-pulse">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-6">
            <input type="email" name="email" placeholder="votre@email.com" class="w-full bg-slate-50 border-2 border-slate-50 p-4 rounded-2xl outline-none focus:bg-white focus:border-green-500 transition-all font-medium text-slate-700" required>
            <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black shadow-xl hover:bg-green-500 transition-all uppercase tracking-widest text-xs">Envoyer le lien</button>
        </form>

        <div class="mt-8 text-center">
            <a href="login.php" class="text-xs font-bold text-slate-400 hover:text-green-600 transition-colors">RETOUR À LA CONNEXION</a>
        </div>
    </div>
</body>
</html>