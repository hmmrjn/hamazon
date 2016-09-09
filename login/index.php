<?php
session_start();

//データベース接続
include '../config.php';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) die("データベース接続エラー");

//ログインボタンが押されたら
if( isset( $_POST['do']) && $_POST['do'] == "login" ){
	$esc_id = $mysqli->real_escape_string($_POST['id']); //SQLI対策
	$md5_pass = md5($_POST['pass']); //暗号化
	$sql = "SELECT * FROM users WHERE user_id='{$esc_id}' AND password='{$md5_pass}'";
	$res = $mysqli->query($sql);

	//ログイン成功の場合
	if ($user = $res->fetch_array()){
		if($user['active'] == 1){
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['user_name'] = $user['name'];
			$message = "ログインしました。";

			//最終ログイン日時を記録
			$sql = "UPDATE users SET last_login = now() WHERE user_id = '{$esc_id}'";
			$mysqli->query($sql);
			header( 'Location: /account/' );
		}else{
			$message = "登録完了メールでアカウントを有効化してください。";
		}
	} else {
		//ログイン失敗の場合
		$message = "ログインID、もしくはパスワードが間違っています。";
	}
}
//アカウントの有効化
if ( isset($_GET['do']) && $_GET['do'] == "activate" && $_POST['do'] != "login"){
	if ( $_GET['key'] == md5($magic_code . $_GET['id']) ) {
		$esc_id = $mysqli->real_escape_string($_GET['id']); //SQLインジェクション対策
		$sql = "UPDATE users SET active = 1 WHERE user_id = '{$esc_id}'";
		$res = $mysqli->query($sql);
		$message = "アカウント登録が完了しました！さっそくログインしよう！";
	} else {
		$message = "<b>システムエラー</b><br/>アカウント登録を完了できませんでした。<br/>運営までお問い合わせください。";
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
888    888 "Y888888 888  888  888 "Y888888 88888888  "Y88P"  888  888 4beta.2.160329

-->
<body>
<div id="container">
<header>
<?php $currentPage='none'; include '../templates/header.php' ?>
</header>
<main class="animated fadeIn">
<div class="banner">
<img src="/images/entrance.jpg">
<p class="animated fadeInRight" id="title">ログイン</p>
</div>
<section>
<?php if( isset($message) ) { ?>
<div class="notifybox"><?= $message ?></div>
<br/><br/>
<?php } ?>
<form action="" method="post">
<table class="t1">				
<tr><td>ログインID：</td><td><input type="text" name="id" value="<?= $login_id ?>"/></td></tr>
<tr><td>パスワード：</td><td><input type="password" name="pass" value=""/></td></tr>
</table>
<input type="hidden" name ="do" value="login" />
<input type="submit" value="ログイン" />
</form>
<br/>
<a href="/register/">アカウント登録</a>
<a href="/login/forgot_password.php" style="padding-left:20px">パスワードをお忘れですか?</a>
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