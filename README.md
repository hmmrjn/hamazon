# hamazon

[hamazon.jp](http:hamazon.jp)

This is my website I use to study PHP as a hobby.
It is only available in Japanese for now.
Please, feel free to  Fork.
Improvement suggentions are fully welcomed.

PHPをテストするために趣味で作っているウェブサイトです。
ソースコードの改良など、変更のご提案は大歓迎です。

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
* ガバガバセキュリティを克服 (CSRF, セッションハイジャックなどの攻撃に備える)
  * gn-spawnに期待。
* グーグルさんに認めてもらう(SEO対策)

## 実行できないですけど？

このソースコードをダウンロードしてブラウザで開くだけでは実行できません。
ソースコードが表示されるだけです。
PHPはサーバサイド言語ですので、XAMPPなどのローカル開発環境で実行してあげる必要があります。
また、データベースを用意してあげる必要もあります。

### 経験者向け
* MySQLで新規データベースを用意します。
* `/create_database.sql` でテーブルの構造とデータをインポートします。
* `/config.php.default` の中にホスト名、ユーザ名、パスワード、データベース名を入力し、`config.php` に改名します。
* サーバのルートディレクトリをhamazonに設定してください。でないと相対パスが動きません。

### 初心者向け
* XAMPPをインストールしましょう。これがないとPHPは動かせません。場所はCドライブ直下`C:\`にしてください。
* インストールと設定の仕方：http://qiita.com/mpyw/items/4508dc677b11e487effc
* `C:\xampp\htdocs`の中にこのリポジトリ`hamazon`を丸ごと入れてください。
* `C:\xampp\apache\conf\httpd.conf`をテキストエディターで開き、240行目当たりの部分を以下のようにに書き換えます。
```
DocumentRoot "C:\xampp\htdocs\hamazon"
<Directory "C:\xampp\htdocs\hamazon">
```
* XAMPPを起動し、ApacheとMySQLのStartボタンを押してください。緑色になればokです。
* これで、`http://localhost/`にアクセスすると「データベース接続エラー」と表示されるはずです。
* `http://localhost/phpmyadmin/` にアクセスしてログインしてください。ユーザを設定していない場合はたぶんユーザ名`root`パスワードなしでログインできます。
* 「データベース」 > 「データベースを作成する」から、データベース名:`hamazon`と入力して作成します。
* 「インポート」 > 「インポートするファイル」から`C:\xampp\htdocs\hamazon`の中に入っている`create_database.sql`を選択して、「実行」を押します。
* `C:\xampp\htdocs\hamazon`の中の`config.php.default`をテキストエディターで開き、以下のように自分のphpmyadminのパスワードを入力し、ファイル名を`config.php` に改名します。
```
$db_host = "localhost";
$db_user = "root";
$db_pass = "phpmyadminのパスワード";
$db_name = "hamazon";
```
* これで、`http://localhost/`にアクセスするとハマゾンのホームページが表示されるはずです。お疲れさまでした（笑）
