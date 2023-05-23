# FabLab_Saint-Gely-du-Fesc

## Pré-requis:

* Symfony : Avoir la version 5.4 de Symfony (Lien pour la télécharger : https://symfony.com/doc/5.4/setup.html)
* OpenLdap : Avoir la version 2.6.4 de OpenLdap (Lien pour la télécharger : https://www.openldap.org/software/download/ )
* Dolibarr :  Avoir la version 16.0.3 de Dolibarr (Lien pour la télécharger: https://www.dolibarr.org/dolibarr-erp-crm-v1603-maintenance-release-for-branch-160-is-available.php?lang=fr&l=fr )
* Serveur Linux distribution Fedora version 37.
* Composer : sudo dnf install composer.

## Consigne :
### Sur votre PC :
+ 1: ouvrir une invite de  commande à l'emplacement désiré ou s'y déplacer.
+ 2: git clone https://github.com/monkey3d/FabLab_Saint-Gely-du-Fesc.git
+ 3: Vous pouvez Développer.
+ 4: Quand vous avez fini, git branch Nom_de_la_branche (ex : G0R0C0)
+ 5: Puis git checkout Nom_de_la_branche créée précédemment.
+ 6: git add le dossier src 
+ 7: git commit -m 'Commentaire pour le commit (ex: Version G0R0C0)'
+ 8: git remote add origin https://github.com/monkey3d/FabLab_Saint-Gely-du-Fesc.git.
+ 9: git push -u origin Nom_de_la_branche

### Sur github :

Normalement sur github une demande de pull est apparue et une branche a été créée avec votre code.

* 10: Faire la demande de push
* 11: Puis merge la branche créée avec la branche main
* 12: Créer une release sur la branche créée pour afficher la dernière version du code.

## Post-requis:

* 14: Paramétrer les variables du fichier .env avec vos informations.
---
    Liste des variables à renseigner :

    APP_SECRET :

    DATABASE_URL : "information de connexion a la base de donnée"
    DATABASE_DOLIBARR_URL : URL de la BD dolibarr

    Remplir les informations si dessous avec les informations pour Symfony.

    APP_NAME="Portail"
    APP_ORGANIZATION="FabLab"
    APP_PATH_ROOT="fablab_portal/public"
    APP_SITE="le nom de votre site"
    APP_FACEBOOK : url de votre compte facebook
    APP_INSTAGRAM : url de votre compte instagram
    APP_YOUTUBE : url de votre compte youtube

    Remplir les informations si dessous avec les informations pour l'openldap.

    LDAP_HOST="votre host"
    LDAP_PORT="le port d'écoute - par défaut 389"
    LDAP_ENCRYPTION="votre type d'encryptage"
    LDAP_BASEDN="dc=votre domaine,dc=com"
    LDAP_SEARCHDN="cn=xxxx,dc=votre domaine,dc=com"
    LDAP_SEARCHPASSWORD="votre password pour le ldap"
    LDAP_URL="ldap://ldap:port d'écoute"
---
* 15: Créer la base de donnée pour le portail.
  * installer Doctrine : (Exécuter ses deux commandes dans votre projet symfony) 
    - composer require symfony/orm-pack 
    - composer require --dev symfony/maker-bundle
    Doc :(https://symfony.com/doc/current/doctrine.html#installing-doctrine) 
  * Créer la base de donnée : (Exécuter la commande dans votre projet symfony)
    - doctrine:database:create
---
* 16: Migrer les données dans la base donnée ou remplir avec vos données.
  Executé la commande : 
    - php bin/console doctrine:schema:update --complete --force
---

### A cette étape la base de donnée pour symfony est créée.Configuration du OpenLdap

* 17:Démarer le service Openldap:
    - sudo systemctl start slapd
    - sudo systemctl enable slapd
* 18:Configuration de l'openldap:
