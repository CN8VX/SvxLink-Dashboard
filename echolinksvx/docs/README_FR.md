# 📡 echolinksvx - Interface EchoLink Logs - SvxLink

Projet développé par **CN8VX** permettant d'afficher et de gérer les **logs de connexion EchoLink** depuis un système **SvxLink** via une interface web simple et sécurisée.

---

### **Interface-EchoLink-Logs** est compatible qu'avec SvxLink version 2 (24.02) et ultérieures sous Debian12

---

## 🧩 Fonctionnalités

- 🔍 Lecture des fichiers de logs EchoLink :
  - `/var/log/svxlink` et `/var/log/svxlink.1`
  
- ⚙️ Fichier de configuration Svxlink :
  - `/etc/svxlink/svxlink.conf`

- 🖼️ Affichage personnalisé :
  - Titre : `Connection logs for EchoLink from Svxlink`
  - Logo personnalisé : `img/M.A.R.R.I_trans.png`
  - Nom du sysop qui se trouve dans footer : `CN8VX`

- 🔄 Rafraîchissement automatique de l'affichage toutes les **5 secondes**

- 🔐 Authentification utilisateur :
  - Vous puvez activation ou désactivation la connexion obligatoire
  - Affichage du nom d'utilisateur connecté
  - Système simple basé sur un tableau de paires `utilisateur => mot de passe`

---

## 🚀 Installation et utilisation

### 1. Copier les fichiers sur votre serveur web
Clonez le dépôt GitHub, dans le répertoire <code>/var/www/html</code> :

```bash
cd /var/www/html
```

```bash
git clone https://github.com/CN8VX/echolinksvx echolinksvx
```

### 2. Vérifier les permissions
Assurez-vous que le dossier <code>echolinksvx</code> a les permission necessaire :

```bash
sudo chmod 777 -R /var/www/html/echolinksvx
```

### 3. Modifier les paramètres
Editez le fichier <code>/var/www/html/echolinksvx/include/config.php</code>: 

```bash
sudo nano /var/www/html/echolinksvx/include/config.php
```

Vous pouvez personnaliser :

- Chemins des fichiers de log
- Chemins du fichier de configuration Svxlink
- Liste des identifiants et mots de passe des utilisateurs (ex : `"admin" => "admin"`)
- Logo personnalisé
- Nom du sysop qui se trouve dans footer
- Activez/désactivez la colonne d'affichage des adresses IP
- Activez/désactivez l'authentification selon vos préférences
 
---

## Exemple de configuration des identifiants utilisateurs

Dans le fichier config.php, section `login` :

```php
'users' => [
    "admin" => "admin",
    "user" => "123456",
    "user1" => "user123"
]
```

### 4. Accéder à l’interface
Ouvrez un navigateur web et rendez-vous à :

```
http://[adresse_ip]/echolinksvx
```
---

## Installation par default : 

- Le login est désactivé.
- L’affichage de la colonne des adresse IP est désactivé.

Si le login est activé, le nom d'utilisateur et le mot de passe par défaut sont :

User name : admin

Password : admin

---

## 🛡️ Sécurité

Ce système est basique et destiné à un usage privé/local. Pour un usage en production publique, il est recommandé d'ajouter :

- Un chiffrement des mots de passe
- Une protection HTTPS via un serveur web (Apache/Nginx)
- Des règles de firewall ou VPN

---

## 📬 Auteur

- Sysop : **CN8VX** cn8vx.ma@gmail.com
- Projet EchoLink basé sur **SvxLink**

---
