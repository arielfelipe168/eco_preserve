<?php
require 'db_config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'menage') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre'];
    $desc = $_POST['description'];
    $type = $_POST['type_action'];
    
    if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
    
    $file_name = time() . "_" . basename($_FILES["photo"]["name"]);
    $target_file = "uploads/" . $file_name;

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        $stmt = $pdo->prepare("INSERT INTO articles (user_id, titre, description, photo, type_action) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $titre, $desc, $file_name, $type]);
        header('Location: dashboard_menage.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier un article - EcoPreserve</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col">

    <nav class="bg-green-600 p-4 text-white shadow-md">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <a href="dashboard_menage.php" class="font-bold flex items-center">
                <span class="mr-2">⬅️</span> Retour
            </a>
            <h1 class="font-bold text-lg">Nouvelle Annonce</h1>
            <div class="w-8"></div> </div>
    </nav>

    <main class="flex-grow flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
            <div class="bg-green-50 p-6 text-center border-b border-green-100">
                <div class="text-3xl mb-2">♻️</div>
                <h2 class="text-xl font-extrabold text-green-800">Donnez une seconde vie</h2>
                <p class="text-green-600 text-sm">Décrivez votre objet pour les réparateurs</p>
            </div>

            <form action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nom de l'objet</label>
                    <input type="text" name="titre" placeholder="Ex: Machine à café, Chaise en bois..." 
                           class="w-full border border-slate-200 p-3 rounded-xl outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Description détaillée</label>
                    <textarea name="description" rows="3" placeholder="État actuel, panne éventuelle, dimensions..." 
                              class="w-full border border-slate-200 p-3 rounded-xl outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Photo de l'article</label>
                    <div class="relative border-2 border-dashed border-slate-200 rounded-xl p-4 hover:bg-slate-50 transition text-center">
                        <input type="file" name="photo" accept="image/*" capture="environment" 
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                        <div class="text-slate-400">
                            <span class="text-2xl block">📷</span>
                            <span class="text-xs">Cliquez pour choisir ou prendre une photo</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Que souhaitez-vous faire ?</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="type_action" value="vente" class="peer hidden" checked>
                            <div class="text-center p-3 rounded-xl border border-slate-200 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 transition font-medium">
                                Vendre
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type_action" value="reparation" class="peer hidden">
                            <div class="text-center p-3 rounded-xl border border-slate-200 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition font-medium">
                                Réparer
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-2xl font-bold shadow-lg shadow-green-200 transition-all transform active:scale-95">
                    🚀 Publier l'annonce
                </button>
            </form>
        </div>
    </main>
</body>
</html>