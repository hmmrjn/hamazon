<?php
session_start();

//データベース接続
include '../config.php';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) die("データベース接続エラー");

//送信ボタンが押されたら
if( isset( $_POST['do']) && $_POST['do'] == "send" ){
	$esc_id   = $mysqli->real_escape_string($_POST['id']);   //SQLI対策
	$esc_mail = $mysqli->real_escape_string($_POST['mail']); //SQLI対策
	$sql = "SELECT user_id, name, mail, active FROM users WHERE user_id='{$esc_id}' OR mail='{$esc_mail}'";
	$res = $mysqli->query($sql);
	//アカウントが存在する場合
	if ($user = $res->fetch_array()){
		if($user['active'] == 1){
			//パスワード再設定用メールの送信
			$subject = "$site_name パスワード再設定用のメール";
			$headers = "From: $support_mail";
			$key  = md5($magic_code . $user['user_id']);
			$body = <<< _EOT_
{$user['name']} 様

$site_name のご利用ありがとうございます。
パスワード再設定のために、下記URLをクリックしてください。

$site_url/login/forgot_password.php?do=reset&id={$user['user_id']}&key=$key

ログインID： {$user['user_id']}

お問い合わせは $support_mail までよろしくお願いします。
--------------------
$site_name
$site_url
_EOT_;
			mail($user['mail'], $subject, $body, $headers);
			//example_address@mail.com → ex****@mail.com
			$hidden_mail = substr($user['mail'], 0, 2).'****';
			$hidden_mail .= substr($user['mail'], strpos($user['mail'], "@"));
			$message = "$hidden_mail に再設定用のメールを送信しました。";

		} else {
		$message = "アカウント登録が済んでいません。<br />登録完了メールでアカウントを有効化してください。";
		}
	} else {
	//ログイン失敗の場合
	$message = "ログインID、もしくはメールアドレスが間違っています。";
	}
}

//パスワード変更リンクで来られた場合
$show_reset_form = false;
if ( isset($_GET['do']) && $_GET['do'] == "reset"){
	if ( $_GET['key'] == md5($magic_code . $_GET['id']) ) {
		$show_reset_form = true;
	} else {
		$message = "<b>おっと！</b><br/>無効なリンクです。運営までお問い合わせください。";
	}
}

//新しいパスワードの保存
if ( isset($_POST['do']) && $_POST['do'] == "save"){
	if ( $_GET['key'] == md5($magic_code . $_GET['id']) ) {
		$esc_id = $mysqli->real_escape_string($_GET['id']); //SQLI対策
		$md5_new_pass = md5($_POST['new_pass']); //暗号化
		$sql = "UPDATE users SET password = '{$md5_new_pass}' WHERE user_id = '{$esc_id}'";
		$res = $mysqli->query($sql);
		$message = "新しいパスワードを保存しました。<a href=\"/login/\">ログイン</a>できます。";
	} else {
		$message = "<b>おっと！</b><br/>無効なリンクです。運営までお問い合わせください。";
	}
}

//ログインのID再入力を省く
$login_id = "";
if( isset($_POST['id']) ) $login_id = $_POST['id'];
if( isset($_GET['id']) ) $login_id = $_GET['id'];

?>
<!Doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Hamazon | 通販 - ファッション、家電から食品まで</title>
<link rel="shortcut icon" href="/images/icon.ico">
<link rel="stylesheet" href="/common/normalize.css">
<link rel="stylesheet" href="/common/animate.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="/common/style.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
</head>
<!--

888    888
888    888
888    888
8888888888  8888b.  88888b.d88b.   8888b.  88888888  .d88b.  88888b.
888    888     "88b 888 "888 "88b     "88b    d88P  d88""88b 888 "88b
888    888 .d888888 888  888  888 .d888888   d88P   888  888 888  888
888    888 888  888 888  888  888 888  888  d88P    Y88..88P 888  888
888    888 "Y888888 888  888  888 "Y888888 88888888  "Y88P"  888  888 4beta.160831

-->
<body>
<div id="container">
<header>
<?php $currentPage='none'; include '../templates/header.php' ?>
</header>
<main class="animated fadeIn">
<div class="banner">
<img src="/images/entrance.jpg">
<p class="animated fadeInRight" id="title">パスワードのリセット</p>
</div>
<section>
<?php if( isset($message) ) { ?>
<div class="notifybox"><?= $message ?></div>
<br/><br/>
<?php } ?>
<?php if($show_reset_form) { ?>
<form action="" method="post">
<table class="t1">
<tr><td>ログインID：</td><td><?= $_GET['id'] ?></td></tr>
<tr><td>新しいパスワード：</td><td><input type="text" name="new_pass" /></td></tr>
</table>
<input type="hidden" name ="do" value="save" />
<input type="submit" value="パスワードを保存" />
</form>
<?php } else { ?>
<p>パスワード再設定用のメールを送信します。登録したログインIDもしくはメールアドレスを入力して下さい。</p>
<form action="" method="post">
<table class="t1">
<tr><td>ログインID：</td><td><input type="text" name="id" value="<?= $login_id ?>"/></td></tr>
<tr><td></td><td>もしくは</td></tr>
<tr><td>メールアドレス：</td><td><input type="text" name="mail" value=""/></td></tr>
</table>
<input type="hidden" name ="do" value="send" />
<input type="submit" value="パスワード再設定用のメールを送信" />
</form>
<?php } ?>
</section>
</main>
<footer>
<?php include '../templates/footer.php'; ?>
</footer>
</div>
</body>
</html>
<?php include '../common/analyticstracking.html'; ?>
]