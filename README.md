# Documentation Snowtricks

## Introduction
**Snowtricks** représente le projet 6 du parcours *développeur Web Symfony*. Il demande la création d'un site complet en utilisant le framework.
    - Application du modèle MVC
    - Injection de dépendances et compréhension du système axé sur les services
    - Gestion des entités
    - Création et chargement de fixtures (données de présentation)
    - Utilisation des formBuilders pour gérer les formulaires

## Installation

  1. Récupérer une copie du projet
      - Aller sur (https://github.com/Cyprinodon/clean-snowtricks)
      - Cliquer sur le bouton contextuel **Code** dans la barre de navigation de fichier
      - Dans la liste déroulante, sélectionner **Download ZIP**
      - Sauvegarder le fichier dans l'emplacement de votre choix
      - Faire un clic droit sur l'archive et choisissez **Extraire vers...**
      - Choisir l'emplacement qui vous convient
      
  2. Installer la base de données
      - Ouvrir la console en ligne de commande de votre choix et déplacez-vous jusque dans le dossier racine du projet *(commande `cd <chemin>` sur windows)* 
      - Créer la base de données en entrant la commande `bin/console doctrine:database:create`
      - Mettre à jour la structure de la base de données en entrant la commande `bin/console doctrine:migrations:migrate`
      - Charger le jeu de fausses données dans la base de données en entrant la commande `bin/console doctrine:fixtures:load`
      
  3. Configurer les variables d'environnement
      - Dans le fichier .env, ajouter/modifier les variables suivantes:
          - EMAIL_SENDER => L'adresse affichée par le site lorsqu'un mail est envoyé aux utilisateurs
          - MAILER_URL => L'url du fournisseur smtp: `username` corresponds au nom d'utilisateur du compte gmail à utiliser, `password` corresponds au mot de passe
          - DATABASE_URL => les identifiants de la base de données: `mysql://user:password@127.0.0.1:3306/db_name?serverVersion=5.7` où `user` corresponds au nom du compte ayant accès à la base de données, `password` corresponds au mot de passe et `db_name` au nom de la base de données
          - APP_ENV => mettre la valeur `prod`
          
  4. L'application est déployée