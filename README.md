Application web développée avec Symfony pour gérer des clients, livraisons et factures.
Chaque utilisateur est rattaché à une entreprise, peut créer des livraisons et générer des factures PDF automatiquement avec calcul de TVA.
L’application inclut aussi l’upload de documents (CMR) liés aux livraisons.

🚀 Fonctionnalités

Gestion des clients (CRUD complet).

Gestion des livraisons (non facturées / facturées).

Génération de factures PDF (via Dompdf).

Calcul automatique des montants HT / TVA / TTC.

Upload et gestion des CMR (documents de transport) via VichUploaderBundle.

Sécurisation des fichiers CMR (stockage privé + téléchargement via route sécurisée).

Authentification des utilisateurs et rattachement à une entreprise.

🛠️ Stack technique

ORM : Doctrine

Base de données : MySQL

Front : Twig

Upload : VichUploaderBundle

PDF : Dompdf via Nucleos bundle

Mail : Mailer
