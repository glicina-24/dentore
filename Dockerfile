# 電話当番通知アプリ用イメージ(CakePHP版)
# nginx+php-fpm構成のため、このイメージはPHP実行(php-fpm)のみを担う。
# 静的ファイル配信・リクエストの振り分けはnginxコンテナ側が行う。
# app(php-fpm)とcron(定期実行)は同一イメージを使い、docker-compose側の
# commandで起動方法を切り替える。

FROM php:8.4-fpm

# CakePHPが必要とするPHP拡張機能をインストールする。
# intl拡張のビルドには libicu-dev が必要。
# cron定期実行用に cron パッケージも入れておく(appコンテナでは未使用)。
RUN apt-get update && apt-get install -y --no-install-recommends \
        libicu-dev \
        default-mysql-client \
        unzip \
        git \
        cron \
    && docker-php-ext-install pdo pdo_mysql intl \
    && rm -rf /var/lib/apt/lists/*

# cronの発火時刻(平日8:55)がJSTになるようタイムゾーンを固定する
ENV TZ=Asia/Tokyo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Composer(PHPの依存関係管理ツール、npmのPHP版)本体を、
# Composer公式イメージからコピーしてくる定番のやり方
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 依存関係の定義ファイルだけ先にコピーしてインストールする(レイヤーキャッシュ活用のため)
COPY composer.json composer.lock ./
RUN composer install --no-interaction --optimize-autoloader

# アプリ本体をコピー
COPY . .

# CakePHPがログやキャッシュを書き込むディレクトリの権限を、
# php-fpmの実行ユーザー(www-data)に合わせる。
# logs/は.dockerignoreで除外しているためCOPYでは作られず、ここで作成する。
RUN mkdir -p /var/www/html/logs \
    && chown -R www-data:www-data /var/www/html/tmp /var/www/html/logs

# 平日8:55(JST)に daily_notify を実行するcron定義。
# cronコンテナでのみ有効化される(docker-compose側でcrontabを読み込むcommandを指定)。
COPY docker/cron/daily-notify /etc/cron.d/daily-notify
RUN chmod 0644 /etc/cron.d/daily-notify

EXPOSE 9000
