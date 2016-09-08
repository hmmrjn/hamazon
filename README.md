# hamazon

ウェブページ： [hamazon.jp](http:hamazon.jp)

PHPの練習をするために作っているウェブサイトです。
ソースコードの改良など、変更のご提案は**大歓迎**です。よろしければプルリクエストを送ってください。



## ローカル開発環境で実行するには

このソースコードをダウンロードしてブラウザで開くだけでは実行できません。
ソースコードが表示されるだけです。
PHPはサーバサイド言語ですので、XAMPPなどのローカル開発環境で実行してあげる必要があります。
また、データベースを用意してあげる必要もあります。ここでは初心者向けに初期設定の方法をstep by stepで説明をします。

### 初期設定
* まずは[XAMPP](https://www.apachefriends.org/jp/index.html)をインストールしましょう。これがないとPHPは動かせません。(は全部デフォルトのままで結構です。)
* 次に、`C:\xampp\apache\conf\httpd.conf` (Macの場合は`/Applications/XAMPP/xamppfiles/htdocs`) をテキストエディターで開き、`DocumentRoot`と書かれている箇所を以下のようにに書き換えます。
```
DocumentRoot "hamazonリポジトリへのパス(例：C:\User\Username\Documents\GitHub\hamazon)"
<Directory "hamazonリポジトリへのパス">
```
* そして、XAMPPを起動し、「Apache」と「MySQL」の「Start」ボタンを押してください。赤から緑色に変わればokです。
* これで試しに`http://localhost/`にアクセスすると「データベース接続エラー」と表示されるはずです。まだデータベースを用意していないからです。
* そこで、`http://localhost/phpmyadmin/` にアクセスし、「データベース」タブから、名前が`hamazon`のデータベースを作ります。
* このままではデータベースが空です。「インポート」 > 「インポートするファイル」から、リポジトリの中に入っている`create_database.sql`を選択して、そのまま一番下の「実行」を押します。これでデータベースに必要な中身を入れました。
* 最後に、リポジトリの中の`config.php.default`をテキストエディターで開き、以下のように自分のMySQLのパスワード(phpMyAdminのログインに使うもの)を入力し、ファイル名を`config.php` に改名します。MySQLのパスワードを設定していない場合は`config.php`に改名のみしてください。
```
$db_pass = "phpmyadminのパスワード";
```
* これで、`http://localhost/`にアクセスするとハマゾンのホームページが表示されるはずです。



# hamazonでできること

### 今できること

* アカウント登録
* レビューの投稿
* 注文履歴の確認
* 商品の検索（無能）
など

### 今できないこと

* 有能な商品検索
* カテゴリー絞りや表示の並び替え
* ユーザーによる商品の投稿
などなど

### 今後の課題

* モバイルへの対応
  * 新しいUIの方は[tanabota889/hamazon-UI](https://github.com/tanabota889/hamazon-UI)で進めています。
* ガバガバセキュリティを克服 (CSRF, セッションハイジャックなどの攻撃に備える)。
* グーグルさんに認めてもらう(SEO対策)

# About

This is my website I use to study PHP as a hobby.
It is only available in Japanese for now.
Please, feel free to  Fork.
Improvement suggentions are fully welcomed.
