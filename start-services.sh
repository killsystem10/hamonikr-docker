#!/bin/bash
set -euo pipefail

readonly MYSQL_ROOT_PASSWORD="${MYSQL_ROOT_PASSWORD:-exitem08EXITEM)*}"
readonly MYSQL_SOCKET="/var/run/mysqld/mysqld.sock"
readonly MYSQL_DATADIR="/var/lib/mysql"
readonly MYSQL_IMPORT_FLAG="${MYSQL_DATADIR}/.hamonikr-imported"
readonly MYSQL_SQL_ARCHIVE="/opt/hamonikr-migration/db/hamonikr.sql.gz"
readonly FILES_ARCHIVE="/opt/hamonikr-migration/files.tar.gz"
readonly WWW_FILES_DIR="/var/www/hamonikr/files"
readonly MYSQL_DB_NAME="hamonikr"

mkdir -p /var/run/mysqld /var/log/mysql
chown -R mysql:mysql /var/run/mysqld /var/log/mysql "$MYSQL_DATADIR"

mysql_pid=""

function start_mysql() {
    /usr/bin/mysqld_safe --datadir="$MYSQL_DATADIR" --socket="$MYSQL_SOCKET" --pid-file="/var/run/mysqld/mysqld.pid" &
    mysql_pid="$!"

    for attempt in {1..60}; do
        if mysqladmin --protocol=socket --socket="$MYSQL_SOCKET" -uroot --password="$MYSQL_ROOT_PASSWORD" ping >/dev/null 2>&1; then
            echo "MySQL ready after ${attempt} second(s)"
            return
        fi
        sleep 1
    done

    echo "MySQL did not become ready in time" >&2
    exit 1
}

function expand_files_archive() {
    if [[ -f "$FILES_ARCHIVE" ]]; then
        if [[ -z "$(ls -A "$WWW_FILES_DIR" 2>/dev/null)" ]]; then
            echo "Restoring files directory from archive"
            mkdir -p "$WWW_FILES_DIR"
            tar -xzf "$FILES_ARCHIVE" -C /var/www/hamonikr
            rm -f "$FILES_ARCHIVE"
        fi
    fi
}

function stop_mysql() {
    if [[ -n "${mysql_pid:-}" ]]; then
        mysqladmin --protocol=socket --socket="$MYSQL_SOCKET" -uroot --password="$MYSQL_ROOT_PASSWORD" shutdown >/dev/null 2>&1 || true
        wait "$mysql_pid" || true
    fi
}

trap stop_mysql EXIT

start_mysql

mysql --protocol=socket --socket="$MYSQL_SOCKET" -uroot --password="$MYSQL_ROOT_PASSWORD" -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}' WITH GRANT OPTION; FLUSH PRIVILEGES;" >/dev/null

if [[ -f "$MYSQL_SQL_ARCHIVE" ]]; then
    if mysql --protocol=socket --socket="$MYSQL_SOCKET" -uroot --password="$MYSQL_ROOT_PASSWORD" -e "SHOW DATABASES LIKE '$MYSQL_DB_NAME'" | grep -q "$MYSQL_DB_NAME"; then
        echo "Database ${MYSQL_DB_NAME} already exists; skipping import"
    else
        echo "Importing ${MYSQL_DB_NAME} database"
        mysql --protocol=socket --socket="$MYSQL_SOCKET" -uroot --password="$MYSQL_ROOT_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS \`$MYSQL_DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
        gunzip -c "$MYSQL_SQL_ARCHIVE" | mysql --protocol=socket --socket="$MYSQL_SOCKET" -uroot --password="$MYSQL_ROOT_PASSWORD" "$MYSQL_DB_NAME"
        touch "$MYSQL_IMPORT_FLAG"
    fi
fi

expand_files_archive

apache2ctl -D FOREGROUND
