<?php
session_start();

//データベース接続
include '../config.php';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) die("データベースの接続エラー");

//会員をデータベースに登録
$error_message = "";
if ( isset($_POST['do']) && $_POST['do'] == "regist" ){
	$esc_id   = $mysqli->real_escape_string($_POST['id']);   //SQLI対策
	$esc_name = $mysqli->real_escape_string($_POST['name']); //SQLI対策
	$esc_mail = $mysqli->real_escape_string($_POST['mail']); //SQLI対策
	$md5_pass = md5($_POST['pass']);						 //パスワードを暗号化
	if( $_POST['id'] == "" || $_POST['name'] == "" || $_POST['mail'] == "" || $_POST['pass'] == "" ){
		$error_message = "未入力のところがあります。";
	} else {
		//ログインIDの既存確認
		$sql = "SELECT * FROM users WHERE user_id = '{$esc_id}'" ;
		$id_res = $mysqli->query( $sql );
		//メールの既存確認
		$sql = "SELECT * FROM users WHERE mail = '{$esc_mail}'" ;
		$mail_res = $mysqli->query( $sql );
		//入力に問題があるか確認
		if   ($id_res->num_rows > 0){
			$error_message .= "この「ログインID」は既に使われています。<br/>";
		} if ($mail_res->num_rows > 0){
			$error_message .= "この「メールアドレス」は既に使われています。<br/>";
		} if (!preg_match('/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,5}$/', $_POST['mail'])) {
			$error_message .= "この「メールアドレス」は無効です。<br/>";
		} if (strlen($_POST['id']) > 20) {
			$error_message .= "「ログインID」は20文字以内にしてください。<br/>";
		} if (strlen($_POST['id']) < 6 ) {
			$error_message .= "「ログインID」は6文字以上にしてください。<br/>";
		} if (strlen($_POST['pass']) > 20) {
			$error_message .= "「パスワード」は20文字以内にしてください。<br/>";
		} if (strlen($_POST['pass']) < 6 ) {
			$error_message .= "「パスワード」は6文字以上にしてください。<br/>";
		} if (strpos($_POST['id'],' ') !== false || 
			strpos($_POST['id'],'　'/*全角空白*/) !== false || 
			strpos($_POST['pass'],' ') !== false) {
			$error_message .= "「ログインID」と「パスワード」で空白を使わないでください。";
		}
		//問題がなければDBに保存
		if( $error_message == "" ) {
			$sql = "INSERT INTO users (user_id, name, mail, password, date, active)";
			$sql .= "VALUES ('{$esc_id}', '{$esc_name}', '{$esc_mail}', '{$md5_pass}', now(), 0 ) ";
			$res = $mysqli->query( $sql );
			if( empty( $mysqli->error ) ){
				$success_message = $_POST['mail']."に登録完了用メールを送信しました。";
				//登録完了用メールの送信
				$subject = "$site_name 登録確認メール";
				$headers = "From: $support_mail";
				$key  = md5($magic_code . $_POST['id']);
$body = <<< _EOT_
${_POST['name']} 様

この度は $site_name へのご登録ありがとうございます。
メールアドレス確認のために、下記URLをクリックしてください。

$site_url/login/?do=activate&id=${_POST['id']}&key=$key

ログインID： ${_POST['id']}

お問い合わせは $support_mail までよろしくお願いします。
--------------------
$site_name
$site_url
_EOT_;
				mail($_POST['mail'], $subject, $body, $headers);
			} else {
				$error_message = "<b>システムエラー</b><br/>予期せぬエラーが発生しました。<br/>運営までお問い合わせください。(エラー内容：" . $mysqli->error . ")";
			}
		}
	}
}
?>
<!Doctype html>
<html lang="ja">
<head>
<?php include "../templates/head.php"; ?>
</head>
<!--

888    888
888    888
888    888
8888888888  8888b.  88888b.d88b.   8888b.  88888888  .d88b.  88888b.
888    888     "88b 888 "888 "88b     "88b    d88P  d88""88b 888 "88b
888    888 .d888888 888  888  888 .d888888   d88P   888  888 888  888
888    888 888  888 888  888  888 888  888  d88P    Y88..88P 888  888
888    888 "Y888888 888  888  888 "Y888888 88888888  "Y88P"  888  888 4beta.2.160329

-->
<body>
<header>
<div class="container">
<?php $currentPage='home'; include '../templates/header.php' ?>
</div>
</header>
<div id="container">
<div class="banner">
<p class="animated bounce" id="title">アカウント登録</p>
</div>
<main class="animated fadeIn">
<section>
<?php if( isset($success_message) ) { ?>
<div class="notifybox success"><?= $success_message ?></div>
<?php } if( isset($error_message) && $error_message !="" ) { ?>
<div class="notifybox error"><?= $error_message ?></div>
<?php } ?>
<form action="" method="post">
<table class="t1">
<tr><td>氏名：</td><td><input type="text" name="name" value="<?php if (isset($_POST['do'])) print $_POST['name'];?>"/></td></tr>
<tr><td>メール：</td><td><input type="text" name="mail" value="<?php if (isset($_POST['do'])) print $_POST['mail'];?>"/></td></tr>				
<tr><td>ログインID：</td><td><input type="text" name="id" value="<?php if (isset($_POST['do'])) print $_POST['id'];?>" placeholder="英数字 6-20文字"/></td></tr>
<tr><td>パスワード：</td><td><input type="password" name="pass" value="<?php if (isset($_POST['do'])) print $_POST['pass'];?>" placeholder="英数字 6-20文字"/></td></tr>
</table>
<input type="hidden" name ="do" value="regist" />
<input type="submit" value="アカウントを作る" />
</form>
</section>
<p>Hamazonではユーザ様のパスワードを暗号化してから保存しています。</p>
</main>
<footer>
<?php include '../templates/footer.php'; ?>
</footer>
</div>
</body>
</html>
<?php include '../common/analyticstracking.html'; ?>
