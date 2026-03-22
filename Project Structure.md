
## English

```text
3dvenue/
├── index.php              # Top page
├── about.php              # About page
├── login.php              # User login
├── acount.php             # Account management
├── expo.php               # Expo list
├── header.php             # Shared header
├── footer.php             # Shared footer
├── config.php             # Configuration
├── dbaccess.php           # DB connection & table initialization
├── README.md
├── LICENSE
│
├── common/
│   ├── css/               # Common styles
│   └── js/                # Common JS (jQuery etc.)
│
├── admin/                 # Admin panel
│   ├── index.php          # Admin dashboard
│   ├── login.php          # Admin login
│   ├── logout.php
│   ├── auth.php           # Authentication
│   ├── organizer.php      # Organizer management
│   ├── company.php        # Company management
│   ├── expo.php           # Expo management
│   ├── 3dvenue.php        # Main admin logic
│   └── css/               # Admin styles
│
├── expo/                  # Expo frontend
│   ├── index.php          # Expo top
│   ├── venue.php          # Venue viewer
│   ├── access.php         # Access logging
│   ├── click.php          # Click tracking
│   ├── css/
│   └── 1/                 # Log directory
│
└── .htaccess              # Security settings
```

## Core
- dbaccess.php  
  → Database connection and automatic table creation (on first access)

- config.php  
  → Global configuration

## Frontend
- index.php  
  → Landing page

- expo.php  
  → Expo listing

- expo/venue.php  
  → 3D venue viewer

- expo/access.php / click.php  
  → Access and click tracking

## Authentication
- login.php  
  → User login

- admin/auth.php  
  → Admin authentication

## Admin
- admin/*  
  → CRUD management for organizers, companies, and expos

## Assets
- common/css  
- admin/css  
  → Stylesheets

- common/js/jquery.js  
  → Frontend behavior



## 日本語
```text
3dvenue/
├── index.php              # トップページ
├── about.php              # サービス説明ページ
├── login.php              # ログイン画面
├── acount.php             # アカウント管理
├── expo.php               # 展示会一覧
├── header.php             # 共通ヘッダー
├── footer.php             # 共通フッター
├── config.php             # 設定ファイル
├── dbaccess.php           # DB接続＆初期テーブル生成
├── README.md
├── LICENSE
│
├── common/
│   ├── css/               # 共通スタイル
│   └── js/                # 共通JS（jQueryなど）
│
├── admin/                 # 管理画面
│   ├── index.php          # 管理トップ
│   ├── login.php          # 管理ログイン
│   ├── logout.php
│   ├── auth.php           # 認証処理
│   ├── organizer.php      # 主催者管理
│   ├── company.php        # 出展企業管理
│   ├── expo.php           # 展示会管理
│   ├── 3dvenue.php        # メイン管理処理
│   └── css/               # 管理画面CSS
│
├── expo/                  # 展示会フロント
│   ├── index.php          # 展示トップ
│   ├── venue.php          # 会場表示
│   ├── access.php         # アクセス記録
│   ├── click.php          # クリック計測
│   ├── css/
│   └── 1/                 # ログディレクトリ
│
└── .htaccess              # セキュリティ設定
```

## Core
- dbaccess.php  
  → DB接続 + 初期テーブル生成（初回アクセス時にCREATE）

- config.php  
  → 共通設定

## Front
- index.php  
  → トップページ

- expo.php  
  → 展示会一覧表示

- expo/venue.php  
  → 3D会場表示

- expo/access.php / click.php  
  → アクセス・クリックログ管理

## Auth
- login.php  
  → ユーザーログイン

- admin/auth.php  
  → 管理者認証

## Admin
- admin/*
  → 主催者・企業・展示会のCRUD管理

## Assets
- common/css  
- admin/css  
  → スタイル

- common/js/jquery.js  
  → フロント制御
  
