# 📡 echolinksvx - EchoLink Logs Interface - SvxLink

Project developed by **CN8VX** to display and manage **EchoLink connection logs** from a **SvxLink** system through a simple and secure web interface.

---
### **Interface-EchoLink-Logs** is only compatible with SvxLink version 2 (24.02) and later under Debian12
---

## 🧩 Features

- 🔍 Reads EchoLink log files:
  - `/var/log/svxlink` and `/var/log/svxlink.1`
  
- ⚙️ Loads configuration from:
  - `/etc/svxlink/svxlink.conf`

- 🖼️ Custom display:
  - Title: `Connection logs for EchoLink from Svxlink`
  - Logo: `img/M.A.R.R.I_trans.png`
  - Sysop name displayed in footer: `CN8VX`

- 🔄 Auto-refresh every **5 seconds**

- 🔐 User authentication:
  - You can enable or disable login requirement
  - Displays the currently logged-in user
  - Simple array-based `utilisateur => mot de passe` authentication

---

## 🚀 Installation & Usage

### 1. Copy the files to your web server
Clone the GitHub repository into the folder <code>/var/www/html</code>:

```bash
cd /var/www/html
```

```bash
git clone https://github.com/CN8VX/echolinksvx echolinksvx
```

### 2. Check file permissions
Ensure the <code>echolinksvx</code> folder has the proper permissions:

```bash
sudo chmod 777 -R /var/www/html/echolinksvx
```

### 3. Edit configuration
Open the file <code>/var/www/html/echolinksvx/include/config.php</code> 

```bash
sudo nano /var/www/html/echolinksvx/include/config.php
```

To customize:

- Log file paths
- SvxLink configuration file path
- User credentials (e.g. `"admin" => "admin"`)
- Logo file path
- Sysop name (footer)
- Enable/disable the IP address display column
- Enable/disable login

```bash
sudo nano /var/www/html/echolinksvx/include/config.php
```

---

## Example: user login configuration

In the `config.php` under the `login` section:

```php
'users' => [
    "admin" => "admin",
    "user" => "123456",
    "user1" => "user123"
]
```

### 4. Access the interface
Open a web browser and visit:

```
http://[your_ip_address]/echolinksvx
```
---
## Default installation:
- Login is disabled.
- The IP address column is disabled.

If login is enabled, the default username and password are:

User name : admin

Password : admin

---

## 🛡️ Security

This is a simple interface intended for local/private use. For public or production use, consider adding:

- Password encryption
- HTTPS protection (Apache/Nginx)
- Firewall rules or VPN access

---

## 📬 Author

- Sysop: **CN8VX** cn8vx.ma@gmail.com
- EchoLink project based on **SvxLink**

---
