<?php
session_start();

//データベース接続
include '../config.php';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) die("データベースの接続エラー");

//キーワードを含むレコードを引き抜く
$word = $mysqli->real_escape_string($_GET['word']); //SQLI対策
$sql = "SELECT * FROM items WHERE name COLLATE utf8_unicode_ci LIKE '%{$word}%'";
$res = $mysqli->query($sql);

//空白を含む場合は注意
if(strpos($_GET['word'],' ') !== false || strpos($_GET['word'],'　') !== false){
	$message = "複数のキーワードには対応していません。<br/>キーワードは一つにしてください。";
}

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
888    888 "Y888888 888  888  888 "Y888888 88888888  "Y88P"  888  888 4beta.2.160329

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
<h2><i class="fa fa-chevron-right"></i> "<?php print($_GET['word']); ?>"の検索結果</h2>
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
$price =  number_format( $item['price'] );
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
	<li>複数キーワードや検索候補、カテゴリー絞り、結果の並び替えなどの機能は今後追加していきます。</li>
	<li>今のところ商品数は少なさ極まりないですが、今後増やしていきます。</li>
</ul>
</main>
<footer>
<?php include '../templates/footer.php'; ?>
</footer>
</div>
</body>
</html>
<?php include '../common/analyticstracking.html'; ?>