# WP Woo Custom Functions

[![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-8.0%20to%208.4-777BB4.svg)](https://php.net/)
[![License: GPL v2](https://img.shields.io/badge/License-GPLv2-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

**WP Woo Custom Functions** is a WordPress plugin designed to provide a safe, centralized environment for running custom PHP snippets, WooCommerce hooks, and core site logic without the need to create child themes or edit files via FTP.

Unlike standard snippet managers, it features an advanced **Sandbox Security System** that prevents fatal PHP errors and protects your site from the White Screen of Death (WSOD).

---

## Key Features

- **Sandbox Security System**: Runs a two-tier stability check (`php -l` linting + protected memory execution) before saving code. If an error is detected, execution is blocked and your site stays online.
- **Hybrid Storage Architecture (File + DB)**: Code is executed from a physical file (`custom.php`) to maximize performance (fully compatible with OPcache), while simultaneously backing up to a dedicated database table.
- **Zero Database Overhead**: On frontend and public page requests, the plugin bypasses the database completely and uses a direct `include_once` on the physical file.
- **Integrated CodeMirror Editor**: Native WordPress code editor experience featuring syntax highlighting, real-time error checking, and line numbers.
- **Emergency Recovery**: Restore your last working code directly from the database with a single click if the physical file is accidentally modified or deleted.
- **Theme-Independent**: Custom functions remain active and untouched even when switching or updating themes.

---

## Important Notice: Upgrading from v1.0

> [!WARNING]
> **UPGRADING FROM VERSION 1.0:**  
> Version 2.0+ introduces a completely new security and storage architecture. When upgrading from 1.0, your legacy code will be preserved in the file system, but it will **no longer be executed automatically**.
>
> **Action Required:** Before updating, copy your existing custom code from `wp-woo-custom-functions.php`. After updating to v2.x, paste it into the new dashboard editor and click **Save Changes**.

---

## Installation

1. Upload the `wp-woo-custom-functions` folder to your `/wp-content/plugins/` directory.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Navigate to **WPWoo CF** in the admin sidebar menu.
4. Add your custom PHP code into the editor and click **Save**.

---

## Compatibility & Requirements

- **WordPress:** 5.8 or higher
- **PHP:** Fully tested and optimized up to **PHP 8.4**
- **WooCommerce:** Compatible with all modern versions

---

## Changelog

### Version 2.0.1
- **Enhanced Sandbox Security System**: Replaced the previous HTTP Loopback Test with a two-tier sandbox check (`php -l` syntax checking + protected in-memory inclusion). This eliminates false positives caused by server firewalls, SSL redirects, or host restrictions while maintaining complete protection against WSOD.

### Version 2.0.0
- **Architectural Redesign**: Introduced the Hybrid File+DB storage system.
- **CodeMirror Integration**: Replaced standard textareas with the native CodeMirror editor.
- **Emergency Recovery System**: Added database backup and one-click code recovery.

---

## License

Distributed under the GNU General Public License v2 or later (GPLv2+).
