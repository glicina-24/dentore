# dentore(電話当番通知アプリ)

電話当番の当日確認の手間をなくすため、当日朝に自動で当番者をチーム全員へメール通知するアプリです。CakePHP学習を目的とした個人開発リポジトリです。詳しい要件は [docs/requirements.md](docs/requirements.md) を参照してください。

## 前提条件

- Docker / Docker Compose がインストールされていること(ホストにPHPやComposerを個別に入れる必要はありません)

## セットアップ手順

```bash
# 1. 環境変数ファイルを準備(.envはgit管理対象外)
cp .env.example .env
# .env を開いて DB_PASSWORD, MYSQL_ROOT_PASSWORD, SECURITY_SALT, SMTP_* などの値を埋める

# 2. 開発用の上書き設定を用意(ソースコードをコンテナにマウントする設定)
cp docker-compose.override.yml.example docker-compose.override.yml

# 3. CakePHPのローカル設定を用意(値は.envから読み込まれるので、基本的に編集不要)
cp config/app_local.example.php config/app_local.php

# 4. コンテナをビルドして起動(nginx / app / cron / mysql の4コンテナが立ち上がる)
docker compose up -d --build

# 5. (初回のみ)DBマイグレーションを実行
docker compose exec app bin/cake migrations migrate
```

起動後、ブラウザで [http://localhost:8081](http://localhost:8081) を開いてください。CakePHPのウェルカムページが表示され、「CakePHP is able to connect to the database.」と出ていればセットアップ成功です。

## よく使うコマンド

```bash
# ログを見る
docker compose logs -f app

# appコンテナのシェルに入る
docker compose exec app bash

# 当番通知バッチ(daily_notify)を手動実行して動作確認する
# (本番では平日8:55にcronコンテナが自動実行する)
docker compose exec app bin/cake daily_notify

# コンテナを停止する
docker compose down
```

## 構成

- `nginx`: Webサーバー(静的ファイル配信、`.php`へのリクエストをappへ転送)
- `app`: CakePHP本体(php-fpmで実行)
- `cron`: appと同じコードを使い、平日8:55(JST)に`daily_notify`を実行する専用コンテナ
- `mysql`: DB(データは名前付きボリューム`mysql-data`に永続化)

各コンテナの詳細は [Dockerfile](Dockerfile) と [docker-compose.yml](docker-compose.yml) を参照してください。
