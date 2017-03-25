<?php
session_start();
//データベース接続
include '../config.php';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) die("データベース接続エラー");

//ログインしている場合はメアドを取得
$name = "";
$mail = "";
$user_id = "未ログイン";
if(isset($_SESSION['user_id'])){
	$user_id = $_SESSION['user_id'];
	$sql = "SELECT * FROM users WHERE user_id = '{$_SESSION['user_id']}'";
	$res = $mysqli->query($sql);
	if ($user = $res->fetch_array()){
		$name = $user['name'];
		$mail = $user['mail'];
	}
}

//メール送信
if(isset($_POST['content'])){
	if($_POST['content'] != ""){
		$subject = "Hamazon Beta お問い合わせ";
		$headers = "From: ${_POST['mail']}";
		$body = <<< _EOT_
ログインID: $user_id
名前：${_POST['name']} / $name (DB)
メールアドレス：${_POST['mail']} / $mail (DB)

内容：
${_POST['content']}

以上
_EOT_;
		mail("15fi103@ms.dendai.ac.jp", $subject, $body, $headers);
		header( 'Location: /contact/?st=sent' );
	} else {
		$message = "内容が空です。";
	}
}

if(isset($_GET['st']) and $_GET['st'] == "sent"){
	$message = "お問い合わせを受け付けました。"	;
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
888    888 "Y888888 888  888  888 "Y888888 88888888  "Y88P"  888  888 4beta.2.160701

-->
<body>
<header>
<div class="container">
<?php $currentPage='home'; include '../templates/header.php' ?>
</div>
</header>
<div id="container">
<div class="banner">
<p class="animated fadeInRight" id="title">お問い合わせ</p>
</div>
<main class="animated fadeIn">
<section>
<?php if( isset($message) ) { ?>
<div class="notifybox"><?= $message ?></div>
<br/><br/>
<?php } ?>
<form method="post" action="">
<table>
<tr>
<td>お名前</td>
<td><input type="text" name="name" value="<?= $name ?>"></td>
</tr>
<tr>
<td>メールアドレス</td>
<td><input type="text" name="mail" value="<?= $mail ?>"></td>
</tr>
<tr>
<td>内容 (必須)</td>
<td><textarea name="content"></textarea></td>
</tr>
<tr>
<td></td>
<td><input type="submit" value="送信"></td>
</tr>
</table>
</section>
</main>
<footer>
<?php include '../templates/footer.php'; ?>
</footer>
</div>
<style>
td {
text-align: left;
padding: 6px;
}
input[type="text"]{
width: 200px;
}
textarea {
width: 400px;
height: 200px;
}
</style>
</body>
</html>
<?php include '../common/analyticstracking.html'; ?>