# Hackpoint
Hackpoint est un projet PHP/sqlite gratuit permettant la gestion de projets éléctroniques/codes 
ou tout autre type de projets devant être sauvegardé/partagés.

## Pré-requis
- Serveur HTTP (Apache2 conseillé)
- PHP5
- SQLITE3
- EXTENSION PHP-GD


## Installation
### En ligne de commande
- Installez les pré-requis ``sudo apt-get install git apache2 php5 php5-sqlite php-gd``
- Clonez le dépot hackpoint ``git clone https://github.com/ldleman/hackpoint.git /var/www/html/hackpoint``
- Réglez les permissions ``sudo chown -r www-data:www-data /var/www/html/hackpoint``
- Depuis un navigateur, lancez le script, le programme est installé.

### Manuelle
- Assurez vous que votre serveur comprends bien les prérequis sqlite et DG
- Téléchargez le zip de l'application et décompressez le dans votre répertoire /www
- Autorisez l'écriture sur les répertoires /data et /upload
- Depuis un navigateur, lancez le script, le programme est installé.
