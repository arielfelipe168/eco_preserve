<?php
session_start();
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header('Location: login.php?error=empty_fields');
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        

        $est_bloque = $user['est_bloque'] ?? 0;
        
        if ($est_bloque == 1) {
            header('Location: login.php?error=account_blocked');
            exit();
        }

        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom']     = $user['nom'];
        $_SESSION['email']   = $user['email'];
        
        $role = (!empty($user['role'])) ? $user['role'] : 'client';
        $_SESSION['role'] = $role;

        switch ($role) {
            case 'admin':
                header('Location: admin_panel.php');
                break;
            case 'reparateur':
                header('Location: dashboard_reparateur.php');
                break;
            default:
                header('Location: index.php');
                break;
        }
        exit();

    } else {
        header('Location: login.php?error=invalid_credentials');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}