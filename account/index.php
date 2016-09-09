<?php
session_start();

//データベース接続
include '../config.php';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) die("データベース接続エラー");

//編集された情報を保存する
if( isset($_POST['save']) && $_POST['save'] == "info" ){
	if( $_POST['name'] == "" || $_POST['mail'] == "" ){
		header('location: ?edit=info&err=null');
		exit();
	} else {
		$esc_name = $mysqli->real_escape_string($_POST['name']); //SQLI対策
		$esc_mail = $mysqli->real_escape_string($_POST['mail']); //SQLI対策
		$sql  = " UPDATE users SET ";
		$sql .= " name = '{$esc_name}',";
		$sql .= " mail = '{$esc_mail}' ";
		$sql .= " WHERE user_id =  '{$_SESSION['user_id']}' ";
		$mysqli->query($sql);
		$_SESSION['user_name'] = $_POST['name'];
	}
}

//変更されたパスワードを保存する
if( isset($_POST['save']) && $_POST['save'] == "pass" ){
	if( $_POST['actual_pass'] == "" || $_POST['new_pass'] == "" ){
		header('location: ?edit=pass&err=null');
		exit();
	} else {
		$md5_actual_pass = md5($_POST['actual_pass']);	//暗号化
		$md5_new_pass    = md5($_POST['new_pass']);		//暗号化
		$sql = "SELECT * FROM users 
				WHERE user_id = '{$_SESSION['user_id']}' AND password = '{$md5_actual_pass}'";
		$res = $mysqli->query($sql);
		if($res->num_rows > 0){
			$sql  = " UPDATE users SET password = '{$md5_new_pass}' 
					WHERE user_id = '{$_SESSION['user_id']}' ";
			$mysqli->query($sql);
			$message = "パスワードを変更しました";
		} else {
			$message = "現在のパスワードが間違っています";
		}
	}
}

//会員の情報を取得
$user_sql = "SELECT name, mail, user_id, date FROM users 
			 WHERE user_id = '{$_SESSION['user_id']}'" ;
$user_res = $mysqli->query($user_sql);

//会員のレビューを取得
$reviews_sql = "SELECT * FROM reviews INNER JOIN items 
				ON reviews.item_id = items.id WHERE user_id = '{$_SESSION['user_id']}'" ;
$reviews_res = $mysqli->query($reviews_sql);

//会員の注文履歴を取得
$orders_sql = "SELECT * FROM orders 
				WHERE user_id = '{$_SESSION['user_id']}' ORDER BY order_id DESC";
$orders_res = $mysqli->query($orders_sql);
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
<link rel="stylesheet" href="/common/style.css">
<link rel="stylesheet" href="stylesheet.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="/common/jquery.raty.js"></script>
</head>
<!--

888    888
888    888
888    888
8888888888  8888b.  88888b.d88b.   8888b.  88888888  .d88b.  88888b.
888    888     "88b 888 "888 "88b     "88b    d88P  d88""88b 888 "88b
888    888 .d888888 888  888  888 .d888888   d88P   888  888 888  888
888    888 888  888 888  888  888 888  888  d88P    Y88..88P 888  888
888    888 "Y888888 888  888  888 "Y888888 88888888  "Y88P"  888  888 4beta.160830

-->
<body>
<div id="container">
<header>
<?php $currentPage='none'; include '../templates/header.php' ?>
</header>
<main class="animated fadeIn">
<div class="banner">
<img src="/images/entrance.jpg">
<p class="animated fadeInRight" id="title">Hamazonアカウント</p>
</div>
<section>
<?php if( isset($message) ) { ?>
<div class="notifybox"><?=  $message; ?></div>
<br/><br/>
<?php } ?>
<h2>基本情報</h2>
<?php
if(isset($_GET['err']) && $_GET['err'] == "null"){
	print "<div class=\"notifybox\">未入力の箇所があります。</div>";
}
if( $user = $user_res->fetch_array() ){
	$reg_date = date_create($user['date']);

	//基本情報の編集フォーム
	if (isset($_GET['edit']) && $_GET['edit'] == "info"){
?>
<form action="/account/" method="post">
<table>
<tr><td>氏名：</td><td><input type="text" name="name" value="<?= $user['name'] ?>"/></td></tr>
<tr><td>メール：</td><td><input type="text" name="mail" value="<?= $user['mail'] ?>"/></td></tr>
</table>
<input type="hidden" name ="save" value="info" />
<input type="submit" value="保存" />
</form>
<?php
	
	//パスワードの編集フォーム
	} elseif(isset($_GET['edit']) && $_GET['edit'] == "pass") {
?>
<form action="/account/" method="post">
<table>
<tr><td>現在のパスワード：</td><td><input type="password" name="actual_pass" /></td></tr>
<tr><td>新しいパスワード：</td><td><input type="password" name="new_pass" /></td></tr>
</table>
<input type="hidden" name ="save" value="pass" />
<input type="submit" value="保存" />
</form>
<?php
	
	//基本情報の表示テーブル
	} else {
?>
<table>
<tr><th>氏名：</th><td><?= $user['name'] ?></td></tr>
<tr><th>メール：</th><td><?= $user['mail'] ?></td></tr>				
<tr><th>ログインID：</th><td><?= $user['user_id'] ?></th></tr>
<tr><th>登録日：</th><td><?= date_format($reg_date, 'Y年 m月 d日') ?></td></tr>
</table>
<a href="?edit=info" class="cntbtn active">アカウント情報を編集</a>
<a href="?edit=pass" class="cntbtn active">パスワードを変更</a>
<?php
	}
}
?>
</section>
<h2>注文履歴</h2>
<?php

?>
<table class="orders">
<tr>
<th>注文番号</th><th>日付</th><th>金額</th><th>詳細</th></tr>
<?php while ( $order = $orders_res->fetch_array() ){ 
$date = date_create($order['date']); ?>
<tr><td><?= $order['order_id'] ?></td>
<td><?= date_format($date, 'Y年 m月 d日') ?></td>
<td>&yen;<?= number_format($order['total_price']) ?></td>
<td><a href="/order_details/?id=<?= $order['order_id'] ?>">詳細を見る</a></td>
</tr>
<?php }
if($orders_res->num_rows == 0){ ?>
<td colspan="4" style="text-align:left">まだ注文がありません。さっそく欲しい商品を注文しよう！</td>
<?php } ?>
</table>
<h2>投稿したレビュー</h2>
<table><tr><th>商品</th>	<th>評価</th><th>編集</th></tr>
<?php while($review = $reviews_res->fetch_array()){ ?>
<tr>
<td><a href="/product/?id=<?= $review['id'] ?>"><?= $review['name'] ?></a></td>
<td><span class="rev_rate" data-score="<?= $review['rate'] ?>"></span></td>
<td><a href="/review/?do=edit&r_id=<?= $review['review_id'] ?>">編集</a></td>
</tr>
<?php } 
if($reviews_res->num_rows == 0){ ?><td colspan="4" style="text-align:left">まだレビューがありません。気に入った商品はレビュー投稿しよう！</td><?php } ?>
</table>
</main>
<footer>
<?php include '../templates/footer.php'; ?>
</footer>
</div>
</body>
<script>
$('.rev_rate').raty({
readOnly: true,
half: true,
path: '/images/',
score: function() {
return $(this).attr('data-score');
}
});
</script>
</html>
<?php include '../common/analyticstracking.html'; ?>