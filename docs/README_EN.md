# SvxLink-Dashboard by CN8VX

## 📝 Description

SvxLink-Dashboard by CN8VX is designed for SvxLink <b><u>repeaters</u></b> and <b><u>hotspots</u></b>.

Based on the original dashboard by SP2ONG & SP0DZ, this version has been rewritten and redesigned by CN8VX to support the latest versions of SvxLink repeaters.

SvxLink-Dashboard by CN8VX is compatible with SvxLink version 2 (24.02) and later, running on <b><u>Debian 11 and 12</u></b>, as well as <b><u>raspios-bookworm-arm64-lite</u></b>.

SvxLink-Dashboard automatically reads and displays your callsign from the SVXLink configuration file `/etc/svxlink/svxlink.conf`.

## 📡 EchoLink Module Enhancements

When the **EchoLink** module is correctly configured and enabled, a button will automatically appear on the dashboard. This button leads to the "**NODE EchoLink Information**" page, which displays the parameters defined in the `[ModuleEchoLink]` section, along with various details about your node:

- `Node Callsign`: Displays your EchoLink callsign, followed by -L or -R.
- `Node Sysop`: Displays the name entered in SYSOPNAME.
- `Node Location`: Displays the location set in LOCATION.
- `Node Language`: Displays the language defined in DEFAULT\_LANG.
- `Nodes Connected`: Lists the callsigns currently connected to your node.
- `Number of Connections`: Shows the total number of real-time connections.

Additionally, an <b>EchoLink Log link</b> is available on this page. It redirects you to <b>echolinksvx</b>, an interface that shows the connection and disconnection history of callsigns that accessed your EchoLink node.

📌 <b><u>echolinksvx</u></b> can also be downloaded directly and independently from the following GitHub repository: [Download echolinksvx interface](https://github.com/CN8VX/Interface-EchoLinkSvx-Logs).

## ⚙️ Configuration

### Main Configuration File

To make changes, edit the file `include/config.php`.

### Configuration Parameters

#### 🕒 1. Time Zone

Set your region or country's time zone. You can find the [complete list of available time zones here](https://www.php.net/manual/en/timezones.php).

- Default time zone: `Africa/Casablanca`
- `date_default_timezone_set('Africa/Casablanca');`

See the bottom of this section for common examples.

#### 2. Header Information

Configure your station information in the `Header lines for information` section:

- `Analog-FM Repeater`: Name of your repeater
- `Your_City`: Replace with your city
- `145.250 MHz`: Replace with your repeater frequency
- `Your_CALL`: Replace with your callsign or the repeater callsign
- `Sysop:Your_CALL`: Enter the callsign(s) of the Sysop(s)

#### 3. Configuration File Path and Name:

- `/etc/svxlink`: Default configuration directory
- `svxlink.conf`: Default configuration file name

#### 4. Log File Path and Name

- `/var/log`: Default log directory
- `svxlink`: Default log file name

#### 5. CPU Temperature Offset

To correctly display CPU TEMPERATURE:

- `define("CPU_TEMP_OFFSET","0");`

**Important Note:** For Orange Pi Zero LTS, use the value `"30"` instead of `"0"`.

---

## 🚀 Installation

✅ 1. Make sure you're in the `/var/www` directory. Then delete the `html` directory:

```bash
cd /var/www
```

```bash
sudo rm -rf html
```

✅ 2. Use the following command to download and copy the content into `/var/www`:

```bash
sudo git clone https://github.com/CN8VX/SvxLink-Dashboard html
```

```bash
sudo chmod 777 -R /var/www/html
```

✅ 3. Edit the `include/config.php` file with your custom settings. Ensure the paths to the SVXLink files are correct:

```bash
sudo nano /var/www/html/include/config.php
```

## 🧩 Prerequisites

- SVXLink installed and configured — follow the [tutorial on DMR-Maroc](https://www.dmr-maroc.com/repeaters_simplex_svxlink.php)
- Web server (Apache, Nginx, etc.)
- PHP with timezone support
- Read access to SVXLink config and log files

## 🕒 Common Time Zones

Examples of common time zones:

- `Africa/Casablanca` - Morocco
- `Europe/Paris` - France
- `Europe/London` - United Kingdom
- `America/New_York` - USA (Eastern)
- `Asia/Tokyo` - Japan

Full list: [complete list of available time zones](https://www.php.net/manual/en/timezones.php)

## 🛠️ Support

For any questions or configuration issues, check:

- File access permissions
- PHP syntax in `config.php`
- SVXLink logs for troubleshooting

Feel free to send feedback, suggestions, or corrections to: [cn8vx.ma@gmail.com](mailto\:cn8vx.ma@gmail.com)

73 de CN8VX, SYSOP of the DMR-MAROC SERVER.

