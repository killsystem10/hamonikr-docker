# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Docker containerization project for the hamonikr.org website, a legacy PHP 7.0 application running on Ubuntu 16.04 with Apache and MySQL 5.7. The project packages the entire legacy stack into a single container for easy deployment and migration.

## Key Components

- **PHP Application**: A XE (XpressEngine) based PHP website located in `site/hamonikr.org/`
- **Apache Web Server**: Configured with SSL, GeoIP, and rewrite rules
- **MySQL Database**: MySQL 5.7 with automated database import from `db/hamonikr.sql.gz`
- **SSL Certificates**: Let's Encrypt certificates stored in `certs/hamonikr/`

## Common Commands

### Build the Docker Image
```bash
docker build -t <user>/hamonikr:latest .
```

### Build with custom MySQL root password
```bash
docker build --build-arg MYSQL_ROOT_PASSWORD='new-password' -t <user>/hamonikr:latest .
```

### Run the container
```bash
docker run -d \
  --name hamonikr \
  --restart unless-stopped \
  -p 80:80 -p 443:443 -p 3306:3306 \
  -v /opt/hamonikr/mysql:/var/lib/mysql \
  -v /opt/hamonikr/logs/mysql:/var/log/mysql \
  -e MYSQL_ROOT_PASSWORD='exitem08EXITEM)*' \
  <user>/hamonikr:latest
```

### Update web files
1. Replace contents of `site/` directory with new PHP files
2. Update `files.tar.gz` if there are new media files
3. Rebuild and push the Docker image

### Update database
1. Replace `db/hamonikr.sql.gz` with fresh mysqldump export
2. Rebuild and push the Docker image

### Rotate SSL certificates
1. Replace certificates in `certs/hamonikr/` directory
2. Rebuild and push the Docker image
   - Or mount new certificates on deployment hosts

## Architecture

The container follows this startup sequence:
1. Starts MySQL server in background
2. Waits for MySQL to accept connections
3. Creates hamonikr database and imports data if not exists
4. Extracts files.tar.gz to `/var/www/hamonikr/files` if empty
5. Starts Apache in foreground

## Configuration Files

- `Dockerfile`: Builds Ubuntu 16.04 base image with PHP 7.0, Apache, MySQL 5.7
- `start-services.sh`: Entry point script managing service startup
- `apache/hamonikr.conf`: HTTP virtual host with redirects to HTTPS
- `apache/hamonikr-le-ssl.conf`: HTTPS virtual host with SSL configuration
- `mysql/mysql.conf.d/mysqld.cnf`: MySQL configuration with bind-address 0.0.0.0

## Important Notes

- The PHP application is a legacy system using XpressEngine (XE) framework
- MySQL root password defaults to `exitem08EXITEM)*` but can be customized
- The container automatically redirects HTTP to HTTPS
- Bot blocking rules are configured in Apache to block unwanted crawlers
- Database import is idempotent - skips if hamonikr database already exists
- Files archive is extracted only if the target directory is empty

## Security Considerations

- MySQL is configured to listen on all interfaces (0.0.0.0) for external access
- SSL certificates are bundled in the image
- Apache blocks specific bots and crawlers
- GeoIP module is enabled for geographic access logging