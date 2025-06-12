# SvxLink-Dashboard by CN8VX

## 📝 Description

SvxLink-Dashboard by CN8VX est conçu pour les <b><u>répéteurs</u></b> et <b><u>hotspots SvxLink</u></b>.

SvxLink-Dashboard by CN8VX est basé sur le Dashboard de SP2ONG & SP0DZ. Réécrit et reconçu par CN8VX, ce Dashboard est destiné à être utilisé avec les nouvelles versions des répéteurs SvxLink.

SvxLink-Dashboard by CN8VX est compatible avec SvxLink version 2 (24.02) et ultérieures sous <b><u>Debian 11 et 12</u></b> ainsi que <b><u>raspios-bookworm-arm64-lite</u></b>.

SvxLink-Dashboard permet la configuration et l'affichage automatique de l'indicatif depuis le fichier de configuration SVXLink `/etc/svxlink/svxlink.conf`.

## 📡 Améliorations de la partie EchoLink 

Lorsque le module **EchoLink** est correctement configuré et activé, un bouton s’ajoute automatiquement au tableau de bord. Ce bouton permet d’accéder à la page "**NODE EchoLink Information**", qui affiche les paramètres définis dans la section `[ModuleEchoLink]`, ainsi que diverses informations liées à votre nœud :

- `Node Callsign` : Affiche votre indicatif EchoLink, suivi de -L ou -R.

- `Node Sysop` : Affiche le nom renseigné dans SYSOPNAME.

- `Node Location` : Affiche la localisation indiquée dans LOCATION.

- `Node Language` : Affiche la langue définie dans DEFAULT_LANG.

- `Nodes Connected` : Liste les indicatifs actuellement connectés à votre nœud.

- `Number of Connections` : Indique le nombre total de connexions en temps réel.

De plus, un lien supplémentaire <b>EchoLink Log</b> est disponible sur cette page. Il vous redirige vers <b>echolinksvx</b> qui est une interface affichant l’historique des connexions et déconnexions des indicatifs ayant accédé à votre nœud EchoLink.

📌 <b><u>echolinksvx</u></b> peut également être téléchargé directement et indépendamment depuis le dépôt GitHub suivant : [Téléchargement de l'interface echolinksvx](https://github.com/CN8VX/Interface-EchoLinkSvx-Logs).

## ⚙️ Configuration

### Fichier de configuration principal

Pour effectuer vos modifications, éditez le fichier `include/config.php`.

### Paramètres de configuration

#### 🕒 1. Fuseau Horaire

Définissez le fuseau horaire de votre région ou pays. Vous trouverez la [liste complète des fuseaux horaires disponibles](https://www.php.net/manual/en/timezones.php).

- Par défaut, le fuseau horaire est `Africa/Casablanca`.
- `date_default_timezone_set('Africa/Casablanca');`

- Vous trouverez quelques exemples des fuseaux horaires courants en bas.

#### 2. Informations Header

Configurez les informations de votre station dans la partie `Header lines for information` :

- `Analog-FM Repeater` : Pour nommer votre répéteur
- `Your_City` : Remplacez par votre ville
- `145.250 MHz` : Remplacez par la fréquence du répéteur
- `Your_CALL` : Remplacez par votre indicatif ou l'indicatif du répéteur
- `Sysop:Your_CALL` : Mettez l’indicatif du ou des Sysop

#### 3. Chemin et nom de fichier de configuration:

- `/etc/svxlink` : C'est le chemin par défaut du fichier de configuration
- `svxlink.conf` : C'est le nom par défaut du fichier de configuration

#### 4. Chemin et nom de fichier du journal

- `/var/log` : C'est le chemin par défaut du fichier du journal
- `svxlink` : C'est le nom par défaut du fichier du journal

#### 5. Compensation température CPU

- Pour afficher correctement la TEMPÉRATURE DU CPU:

- `define("CPU_TEMP_OFFSET","0");`

**Note importante :** Pour Orange Pi Zero LTS, utilisez la valeur `"30"` au lieu de `"0"`.

---

## 🚀 Installation

✅ 1. Assurez-vous d'être dans le répertoire `/var/www`. Puis supprimez le répertoire `html`

```bash
cd /var/www
```

```bash
sudo rm -rf html
```

✅ 2. Ensuite, utilisez la commande suivante pour télécharger et copier tout le contenu dans le répertoire `/var/www` :

```bash
git clone https://github.com/CN8VX/SvxLink-Dashboard html
```

```bash
sudo chmod 777 -R /var/www/html
```

✅ 3. Éditez le fichier `include/config.php` avec vos paramètres de personnalisations. Assurez-vous que les chemins vers les fichiers SVXLink sont corrects.

```bash
sudo nano /var/www/html/include/config.php
```

## 🧩 Prérequis

- SVXLink installé et configuré, vous pouvez suivre le [tutoriel sur le site DMR-Maroc](https://www.dmr-maroc.com/repeaters_simplex_svxlink.php)
- Serveur web (Apache, Nginx, etc.)
- PHP avec support des fuseaux horaires
- Accès en lecture aux fichiers de configuration et logs SVXLink

## 🕒 Fuseaux horaires courants

Quelques exemples de fuseaux horaires :
- `Africa/Casablanca` - Maroc
- `Europe/Paris` - France
- `Europe/London` - Royaume-Uni
- `America/New_York` - États-Unis (Est)
- `Asia/Tokyo` - Japon

Pour la liste complète : [liste complète des fuseaux horaires disponibles](https://www.php.net/manual/en/timezones.php)

## 🛠️ Support

Pour toute question ou problème de configuration, vérifiez :
- Les permissions d'accès aux fichiers de configuration
- La syntaxe PHP dans le fichier `config.php`
- Les logs SVXLink pour diagnostiquer les problèmes

Je vous encourage à m'envoyer vos commentaires et suggestions ou des corrections à l'adresse suivante : [cn8vx.ma@gmail.com](mailto\:cn8vx.ma@gmail.com)

73 de CN8VX, SYSOP du SERVEUR DMR-MAROC.
