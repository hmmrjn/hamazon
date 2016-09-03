<?php
session_start();
//データベース接続
include '../config.php';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) die("データベースの接続エラー");

//SQL
$sql = "SELECT * FROM items WHERE deleted = '0' ";
$res = $mysqli->query($sql);

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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<!--

888    888
888    888
888    888
8888888888  8888b.  88888b.d88b.   8888b.  88888888  .d88b.  88888b.
888    888     "88b 888 "888 "88b     "88b    d88P  d88""88b 888 "88b
888    888 .d888888 888  888  888 .d888888   d88P   888  888 888  888
888    888 888  888 888  888  888 888  888  d88P    Y88..88P 888  888
888    888 "Y888888 888  888  888 "Y888888 88888888  "Y88P"  888  888 4beta.160823

-->
<body>
<div id="container">
<header>
<?php $currentPage='none'; include '../templates/header.php' ?>
</header>
<main class="animated fadeIn">
<?php if( isset($message) ) { ?>
<div class="notifybox"><?php print $message; ?></div>
<?php } ?>
<h2><i class="fa fa-chevron-right"></i> 商品一覧</h2>
<table class="standard-table">
<tr>
<th>画像</th>
<th>商品名</th>
<th>在庫状況</th>
<th>価格</th>
<th>注文</th>
</tr>
<?php
if($res->num_rows == 0){
	print("<td colspan=\"5\">キーワードに一致する名前の商品は見つかりませんでした。</td>");
}
while( $item = $res->fetch_array() ) {
$price = number_format( $item['price'] );
?>
<tr>
<td><a href="/product/?id=<?php print( $item['id'] ); ?>">
<img src="/images/product<?php print( $item['id'] ); ?>.jpg" alt="<?php print( $item['name'] ); ?>の画像" width="200px"></a></td>
<td><a href="/product/?id=<?php print( $item['id'] ); ?>"><?php print( $item['name'] ); ?></a></td>
<td><font color="green"><?php print( $item['stock'] ); ?>点在庫有り</font></td>
<td><font color="darkred">&yen;<?php print( $price ); ?></font>
<br>(税別)</td>
<td><a href="/product/?id=<?php print( $item['id'] ); ?>" class="cntbtn active"><i class="fa fa-tags"></i>&nbsp;詳細を見る</a></td>
</tr>
<?php
}
$res->free_result();
$mysqli->close();
?>
</table>
<ul>
	<li>カテゴリー絞り、結果の並び替えなどの機能は今後追加していきます。</li>
	<li>商品追加のリクエストを受け付けます。<a href="https://twitter.com/hamazon103">@hamazon103</a>までリプライを送るか、<a href="/contact/">お問い合わせ</a>ください。</li>
</ul>
</main>
<footer>
<?php include '../templates/footer.php'; ?>
</footer>
</div>
</body>
</html>
<?php include '../common/analyticstracking.html'; ?>