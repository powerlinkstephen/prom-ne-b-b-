# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A LocalWP-managed WordPress site named `promene-bebe` (Serbian: "change baby"). The repository tracks the **entire Local site directory**, not just a theme or plugin — it contains the WordPress core, the LocalWP nginx/php-fpm/mysql config templates, and the runtime logs.

As of this writing the install is **stock WordPress** (only bundled `twentytwentytwo`–`twentytwentyfive` themes, no custom plugins beyond WP's `index.php` placeholder). Custom code, when added, lives under `promene-bebe/app/public/wp-content/`.

## Layout

```
promene-bebe/
  app/public/           # WordPress webroot (ABSPATH). Edit themes/plugins here.
  conf/                 # LocalWP service configs — see warning below.
    nginx/*.hbs         # nginx vhost + includes (Handlebars templates)
    php/*.hbs           # php.ini + php-fpm.conf templates
    mysql/my.cnf.hbs
  logs/                 # nginx/php/mysql/mailpit runtime logs
```

## Running it

The site is **not started from the CLI in this repo**. It is run by the LocalWP desktop app, which renders the `.hbs` templates in `conf/` into real configs and starts nginx, php-fpm, mysql, and mailpit. To work on the site:

- Start/stop: LocalWP app → site `promene-bebe`.
- Open WP shell (gives you `wp` WP-CLI and the correct PHP): LocalWP → right-click site → **Open site shell**. Run `wp` commands from there, not from the host shell.
- DB GUI: LocalWP → **Database** → Adminer.
- Mail capture: Mailpit (LocalWP → Tools).

DB credentials (from `wp-config.php`) are the LocalWP defaults: `DB_NAME=local`, `DB_USER=root`, `DB_PASSWORD=root`, `DB_HOST=localhost`. The site URL is whatever LocalWP assigned (check the app); WordPress stores it in `wp_options.siteurl`/`home`.

## Editing the LocalWP configs (`conf/*.hbs`)

The files in `conf/` are **Handlebars templates** (note the `.hbs` extension and `{{...}}` placeholders like `{{port}}`, `{{root}}`, `{{#each fastcgi_servers}}`). LocalWP regenerates the real `nginx.conf`, `php.ini`, `my.cnf` from these on every site start.

Consequences:
- Do not edit the generated configs in LocalWP's runtime directory — they get overwritten. Edit the `.hbs` here.
- Keep the `{{...}}` placeholders intact when editing; removing them will break site startup.
- After editing a template, restart the site from LocalWP for it to take effect.

## WordPress-specific

- `wp-config.php` sets `WP_ENVIRONMENT_TYPE = 'local'` and `WP_DEBUG = false`. Flip `WP_DEBUG` to `true` (and consider `WP_DEBUG_LOG`) when chasing PHP errors; logs land in `logs/php/`.
- `.htaccess` is present but **nginx is the active server** under LocalWP — pretty-permalink rewrites are handled by `conf/nginx/includes/wordpress-single.conf`, not `.htaccess`. Don't waste time editing `.htaccess` to change routing.
- Table prefix is the default `wp_`.
- The salts in `wp-config.php` are committed. Fine for a local-only dev site; rotate before any deployment.
