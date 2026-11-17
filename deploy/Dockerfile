# syntax=docker/dockerfile:1
# 舊站（蘋果CMS v10 2021.1000.2000 / ThinkPHP 5.0.24）本機測試用映像，由 docker-compose.yml 建置
# build context 是 cms_template 根目錄；排除的檔案在 Dockerfile.dockerignore
# PHP 用 7.4（線上版本）；ThinkPHP 5.0 會把 PHP 8.1 以後的 Deprecated 當例外丟出，8.x 跑不起來
FROM php:7.4-apache

ENV TZ=Asia/Taipei

RUN set -eux; \
    # php:7.4 是 Debian 11 bullseye，2026-08 支援結束，deb.debian.org 的 security 套件已移走（下載 404），
    # 一律改用 archive.debian.org
    sed -ri 's#https?://(deb|security)\.debian\.org#http://archive.debian.org#g' /etc/apt/sources.list; \
    apt-get -o Acquire::Check-Valid-Until=false update; \
    apt-get install -y --no-install-recommends \
        libpng-dev libjpeg-dev libfreetype6-dev libzip-dev tzdata; \
    # gd：後台驗證碼、縮圖；zip：後台線上升級；mysqli/pdo_mysql：資料庫
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" gd mysqli pdo_mysql bcmath exif zip; \
    # 後台快取設定是 redis；phpredis 5.3.7 支援 PHP 7.4
    pecl install redis-5.3.7; \
    docker-php-ext-enable redis; \
    php -m | grep -qi '^Zend OPcache$' || docker-php-ext-enable opcache; \
    rm -rf /var/lib/apt/lists/* /tmp/pear; \
    ln -snf /usr/share/zoneinfo/$TZ /etc/localtime; echo $TZ > /etc/timezone; \
    a2enmod rewrite headers; \
    { echo 'ServerName localhost'; echo 'ServerTokens Prod'; echo 'ServerSignature Off'; } >> /etc/apache2/apache2.conf; \
    cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# PHP 設定（疊加在 php.ini-production 上）
COPY <<"EOF" /usr/local/etc/php/conf.d/zz-app.ini
expose_php = Off
memory_limit = 256M
date.timezone = Asia/Taipei
default_charset = "UTF-8"
upload_max_filesize = 50M
post_max_size = 50M
max_input_vars = 5000
; 錯誤寫到 docker compose logs web，不顯示在頁面上（要顯示：compose 的 PHP_APP_DEBUG 改 true）
log_errors = On
error_log = /dev/stderr
display_errors = Off
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 10000
; 後台存設定是改寫 application/extra/*.php，一定要檢查檔案時間，否則改了不生效
opcache.validate_timestamps = 1
opcache.revalidate_freq = 0
EOF

# Apache 站台：伪静态規則和线上 Nginx（说明文档/伪静态规则/maccms.conf）相同
COPY <<"EOF" /etc/apache2/sites-available/000-default.conf
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot /var/www/html
    # 根目錄的 index.html 是舊的靜態快照，首頁要走 index.php
    DirectoryIndex index.php index.html

    <Directory /var/www/html>
        Options -Indexes +FollowSymLinks -MultiViews
        AllowOverride None
        Require all granted

        RewriteEngine On
        # 只執行 index.php / api.php / hwyayl.php，其他 .php（install.php、模板…）一律 403
        RewriteRule ^(?!(?:index|api|hwyayl)\.php(?:/|$)).*\.(?:php|phtml|phar)(?:/|$) - [F]
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^(.*)$ index.php?s=/$1 [QSA,L]
    </Directory>

    # 隱藏檔與不該對外的目錄
    <FilesMatch "^\.">
        Require all denied
    </FilesMatch>
    <DirectoryMatch "^/var/www/html/(?:\.git|sh_work|runtime|application|thinkphp|extend|vendor|说明文档)(?:/|$)">
        Require all denied
    </DirectoryMatch>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

WORKDIR /var/www/html
COPY --chown=www-data:www-data . /var/www/html/

RUN set -eux; \
    # 資料庫是匯入現成的備份，不跑安裝程式；沒有這個檔 index.php 會轉到 install.php
    touch application/data/install/install.lock; \
    # 清掉 repo 裡留下的模板編譯檔與快取
    mkdir -p runtime/cache runtime/temp runtime/log; \
    find runtime -mindepth 2 -delete; \
    chown -R www-data:www-data /var/www/html

EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD curl -fsS -o /dev/null http://127.0.0.1/robots.txt || exit 1
CMD ["apache2-foreground"]
