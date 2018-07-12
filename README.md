# ProjetSymfony
Projet Symfony meilleurCoin
*****************************************************
Projet d'un site d'annonces réalisé en PHP 7.2.4
Symfony 3.4
*****************************************************
Fonctionnalités:
Gestion de la sécurité
Formulaire et collection Type
Relations ManyToMany,  OneToMany, OneToOne
CRUD
Repository ,Dql

*****************************************************
Déploiement:
1° Pré-requis
WampServer pour Windows
PHP 7.2.4
MySQL

2° Clonage du projet
dans wamp/www/
cloner le répertoire avec la commande :
git clone https://github.com/emmygrapin/ProjetSymfony.git

3° Création d'un virtual Host
Dans Wamp Server, configurer un virtualHost sur localhost
Nom du virtualHost: meilleurcoin
Chemin complet absolu du dossier VirtualHost - Exemples : C:/wamp/www/projet/public

Redémarrer les Services Wamp.

4° Configuration de la base de données:
Ouvrir le projet dans un IDE,
Dans le fichier .env
Modifier la configuration de la base de données:
DATABASE_URL=mysql://root:''@127.0.0.1:3306/meilleurCoin

5° Tester l'adresse meilleurCoin/
