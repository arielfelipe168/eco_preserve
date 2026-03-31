# 🌿 EcoPreserve - Économie Circulaire & Seconde Vie au Bénin

**EcoPreserve** est une plateforme numérique innovante dédiée à la promotion de l'économie circulaire au Bénin. Notre mission est de transformer la gestion des déchets électroniques en opportunités économiques et sociales.



## 🎯 Le Problème
Le Bénin fait face à deux défis majeurs :
1. **Pollution numérique :** Accumulation de déchets électroniques non traités.
2. **Secteur informel invisible :** Des milliers de réparateurs talentueux manquent de visibilité.

## ✨ Notre Solution
EcoPreserve connecte les détenteurs d'appareils en panne avec des experts de la réparation via un tiers de confiance local.

### Fonctionnalités clés :
*   **Mise en relation intelligente :** Publication de pannes et propositions de services par des réparateurs certifiés.
*   **Espace Reconditionné :** Vente d'appareils remis à neuf pour une consommation durable.
*   **Système de Confiance :** Notations, commentaires et modération active pour sécuriser les transactions.
*   **Paiement Intégré :** Solutions de paiement locales sécurisées via **FedaPay**.

## 🛠️ Stack Technique
L'application repose sur une architecture moderne, fluide et adaptée à la connectivité locale :
*   **Langage :** PHP
*   **Styling :** Tailwind CSS
*   **Paiements :** API FedaPay
*   **Architecture :** Optimisée pour le Web Mobile

## 🌍 Impact visé
*   **Environnemental :** Réduction de l'empreinte carbone en prolongeant la vie des équipements.
*   **Social :** Professionnalisation et création d'emplois pour les réparateurs locaux.
*   **Économique :** Gain de pouvoir d'achat pour les ménages béninois.

## 🚀 Installation locale
Pour tester le projet sur votre machine :

1. Clonez le dépôt :
   ```bash
   git clone (https://github.com/arielfelipe168/eco_preserve.git)

2. **Installer les dépendances PHP :**
   Assurez-vous d'avoir [Composer](https://getcomposer.org/) installé, puis lancez :
   ```bash
   composer install

## 🗄️ Configuration de la Base de Données

Pour faire fonctionner l'application, vous devez importer le schéma SQL :

1.  **Créer la base de données :** 
    Lancez MySQL (via XAMPP/WAMP) et créez une base de données nommée `eco_preserve_db`.
2.  **Importer le schéma :**
    Importez le fichier situé dans `/databases/eco_preserve_db.sql` dans votre nouvelle base de données via PHPMyAdmin ou en ligne de commande :
    ```bash
    mysql -u root -p eco_preserve_db < databases/eco_preserve_db.sql
    ```
3.  **Configurer la connexion :**
    Ouvrez le fichier `db_config.php` et vérifiez que les paramètres correspondent à votre environnement local :
    ```php
    $host = 'localhost';
    $dbname = 'eco_preserve_db';
    $username = 'root';
    $password = ''; // Par défaut vide sur XAMPP (mettez-y votre mot de passe)
    ```
