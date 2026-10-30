# Local WordPress setup — step-by-step

This runbook turns a fresh clone of this repository into a running WordPress site.
It is the exact sequence of commands that was used to stand up the instance at
http://localhost:8080 (MariaDB + WordPress on Docker), and it can be repeated from
scratch. For a quick reference, see the "Run locally with Docker" section of
[`README.md`](README.md).

## Prerequisites

Docker Desktop with a working WSL2 backend. Nothing else — WordPress core, PHP 8.3,
and MariaDB all come from the containers, so no local PHP, MySQL, or WP-CLI install
is required.

```powershell
docker info --format '{{.ServerVersion}}'
```

If that errors, start Docker Desktop and wait for the engine. If its backend keeps
exiting, enable the "Virtual Machine Platform" and "Windows Subsystem for Linux"
Windows features and reboot.

## 1. Create `docker-compose.yml`

Save this at the repository root (the repo already contains it):

```yaml
services:
  db:
    image: mariadb:11
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
      MYSQL_ROOT_PASSWORD: rootpassword
    volumes:
      - db_data:/var/lib/mysql
    healthcheck:
      test: ["CMD", "healthcheck.sh", "--connect", "--innodb_initialized"]
      interval: 5s
      timeout: 5s
      retries: 20

  wp:
    image: wordpress:php8.3-apache
    restart: unless-stopped
    depends_on:
      db:
        condition: service_healthy
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: wordpress
    ports:
      - "8080:80"
    volumes:
      - .:/var/www/html

volumes:
  db_data:
```

Key points: the repo root is mounted at `/var/www/html`, so this directory *is* the
WordPress root. The `WORDPRESS_*` environment variables generate `wp-config.php`;
the `db_data` volume persists the database across restarts.

## 2. Start the stack

```powershell
docker compose up -d
```

The first run downloads `mariadb:11` and `wordpress:php8.3-apache`, creates the
volume and network, then starts both containers. Because the mounted directory has
no WordPress core yet, the `wordpress` image copies core into it and writes
`wp-config.php` from the environment variables (both gitignored). Wait for the copy
to finish:

```powershell
docker compose logs wp
# look for:  Complete! WordPress has been successfully copied to /var/www/html
```

## 3. Get WP-CLI (throwaway)

The `wordpress` image does not bundle WP-CLI. Download the phar into this directory
(it is mounted at `/var/www/html` inside the container) and run it through the
container's PHP:

```powershell
curl.exe -L -o wp-cli.phar https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
```

## 4. Install the site and activate the boilerplate

```powershell
docker compose exec wp php wp-cli.phar core install --url=http://localhost:8080 --title="WP Initializer" --admin_user=admin --admin_password=admin --admin_email=admin@example.com --allow-root
docker compose exec wp php wp-cli.phar plugin activate wp-initializer --allow-root
docker compose exec wp php wp-cli.phar theme activate wp-initializer-theme --allow-root
```

This creates the database tables, the `admin` user, and activates the plugin and
theme. To use the browser instead, open http://localhost:8080, complete the
installer, then activate **WP Initializer** under Plugins and under Appearance →
Themes — then skip step 5.

## 5. Correct the site URL (WP-CLI installs only)

`wp core install` can store the container path in the URL, producing
`http://localhost:8080/var/www/html` and broken front-page links. Reset the two
options:

```powershell
docker compose exec wp php wp-cli.phar option update home http://localhost:8080 --allow-root
docker compose exec wp php wp-cli.phar option update siteurl http://localhost:8080 --allow-root
```

## 6. Verify

```powershell
# Front page: HTTP 200, correct <title>, skip-link, and the "Hello world!" post
curl.exe -s http://localhost:8080/ | Select-String 'WP Initializer|Hello world|skip-link'

# Admin redirects to the login screen
curl.exe -s -o NUL -w "HTTP %{http_code}" http://localhost:8080/wp-admin/
# expected: HTTP 302

# The plugin shortcode renders
docker compose exec wp php wp-cli.phar eval 'echo do_shortcode("[wp_initializer message=\"Hello\"]");' --allow-root
# expected: <p class="wp-initializer">Hello</p>

# The admin user exists
docker compose exec db sh -c 'mariadb -uwordpress -pwordpress wordpress -N -e "SELECT user_login FROM wp_users;"'
# expected: admin
```

Log in at http://localhost:8080/wp-admin with **admin / admin**.

## 7. Clean up

```powershell
Remove-Item wp-cli.phar
```

`git status` should show only `docker-compose.yml` as new — WordPress core,
`wp-config.php`, and the bundled themes/plugins are all gitignored.

## Day-to-day

```powershell
docker compose down      # stop, keep the database
docker compose up -d     # start again
docker compose down -v   # stop and delete the database (start over from step 2)
```

## Troubleshooting

- **`docker info` fails** — Docker Desktop isn't running, or its WSL2 backend is broken.
- **`wp: executable file not found`** — you ran bare `wp`; the image has no WP-CLI, use `php wp-cli.phar ...` (step 3).
- **Front-page links show `/var/www/html/`** — run step 5.
- **Port 8080 in use** — change `8080:80` in `docker-compose.yml` and use the new port in steps 4–6.
