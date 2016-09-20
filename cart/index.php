<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
//データベース接続
include '../config.php';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) die("データベース接続エラー");
//商品をカートに入れる準備
if ( !isset($_SESSION['cart']) ){
	$_SESSION['cart'] = array();
}
//商品をカートに加える
if( $_GET['do'] == "add" ){
	//すでに入っている場合
	$already_exists  = false;
	for( $i = 0 ; $i < count( $_SESSION['cart'] ); $i++ ){
		if( $_SESSION['cart'][$i]['id'] == $_GET['p'] ){
			$_SESSION['cart'][$i]['quantity'] += $_GET['q'];
			$already_exists = true;
		}
	}
	//すでに入ってない場合
	if( !$already_exists ){
		$sql = "SELECT * FROM items WHERE id = " . $_GET['p'];
		$res = $mysqli->query($sql);
		if( $db_record = $res->fetch_array() ){
			$temp['id'] = $_GET['p'];
			$temp['quantity'] = $_GET['q'];
			$temp['name'] = $db_record['name'];
			$temp['price'] = $db_record['price'];
			array_push( $_SESSION['cart'], $temp );
		}
		$res->free_result();
	}
	header('location:/cart/');
	exit();
}
//商品をカートから取り除く
if( $_GET['do'] == "remove"){
	for( $i = 0 ; $i < count($_SESSION['cart']); $i++ ){
		if( $_SESSION['cart'][$i]['id'] == $_GET['p'] ){
			unset( $_SESSION['cart'][$i] );
		}
	}
	$_SESSION['cart'] = array_merge($_SESSION['cart']);
}
//小計金額を計算
$subtotal_price = 0; 
foreach ( $_SESSION['cart'] as $cart_item ){ 
	$subtotal_price += $cart_item['price'] * $cart_item['quantity']; 
}
//消費税を計算
$tax_price = round($subtotal_price * 0.08);
//注文を記録する
if( $_GET['do'] == "order" && !cart_empty() ) {
	$sql = "INSERT INTO orders (user_id, date, total_price)";
	$sql .= "VALUES ('".$_SESSION['user_id']."', now(), '".$subtotal_price."' ) ";
	$mysqli->query($sql);
	$order_id =  $mysqli->insert_id;
	foreach ($_SESSION['cart'] as $cart_item) {
		$sql = "INSERT INTO order_details (order_id, item_id, priced, quantity) ";
		$sql .= "VALUES ('".$order_id."', '".$cart_item['id']."', '".$cart_item['price']."', '".$cart_item['quantity']."' ) ";
		$mysqli->query($sql);
	}
	unset( $_SESSION['cart'] );
	$_SESSION['cart'] = array();
	$message = "注文を受け付けました。（注文番号：".$order_id."）";
}
function cart_empty(){
	return $_SESSION['cart'][0]['id'] == "";
}
?>
<!Doctype html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>カート - Hamazon | 通販 - ファッション、家電から食品まで</title>
	<link rel="shortcut icon" href="/images/icon.ico">
	<link rel="stylesheet" href="/common/normalize.css">
	<link rel="stylesheet" href="/common/animate.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/common/style.css">
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
			<?php $currentPage='cart'; include '../templates/header.php' ?>
		</div>
	</header>
	<div id="container">
		<main class="animated fadeIn">
		<div class="box leftbox">
			<h2><i class="fa fa-shopping-cart"></i>&nbsp;カート</h2>
<?php
if(isset($message)) print $message;
if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
?>
			<table class="t1">
				<tr>
					<th>商品</th>
					<th>金額</th>
					<th>数量</th>
					<th>削除</th>
				</tr>
<?php
foreach ( $_SESSION['cart'] as $cart_item ){ 
$price =  number_format( $cart_item['price'] );
?>
				<tr>
					<td><?= $cart_item['name'] ."&nbsp" ?></td>
					<td>&yen;<?= $price ."&nbsp" ?></td>
					<td><?= $cart_item['quantity'] ."個" ?></td>
					<td><a href="/cart/?do=remove&p=<?= $cart_item['id'] ?>">削除</a></td>
				</tr>
				<?php } ?>
			</table>
<?php } else { ?>
			ただいまカートは空です。
<?php } ?>
		</div>
		<div class="box rightbox">
				<h2>支払金額</h2>
				<table class="t2">
					<tr>
						<td>商品：</td>
						<td>&yen;&nbsp;<?= number_format($subtotal_price) ?></td>
					</tr>
					<tr>
						<td>消費税：</td>
						<td>&yen;&nbsp;<?= number_format($tax_price) ?></td>
					</tr>
					<tr>
						<td>送料：</td>
						<td>&yen;&nbsp;0</td>
					</tr>
					<tr>
						<td>手数料：</td>
						<td>&yen;&nbsp;0</td>
					</tr>
					<tr>
						<td colspan="2">
							<hr />
						</td>
					</tr>
					<tr>
						<td class="t2"><b>合計：</b></td>
						<td class="t2"><b>&yen;&nbsp;<?= number_format($subtotal_price+$tax_price) ?></b></td>
					</tr>
				</table>
				<form action="" method="get" style="text-align: center;">
					<input type="hidden" name="do" value="order" />
					<?php if( $_SESSION['user_id'] == "" ){ ?>
					<div class="cntbtn active" onclick="location.href='/login/'">ログイン</div>
					<br/>ログインしてください。
					<?php } else if( cart_empty() ) { ?>
					<input disabled type="submit" value="注文する" />
					<?php } else { ?>
					<input type="submit" value="注文する" />
					<?php } ?>
				</form>
			</a>
		</div>
		<section>
			<p>商品価格や在庫状況は変更される場合があります。カートに追加した時と在庫状況や価格が異なることがあります。カート内で表示されている価格は最新の価格となります。</p>
		</section>
	</main>
	<footer>
		<?php include '../templates/footer.php'; ?>
	</footer>
</div>
</body>
</html>
<?php include '../common/analyticstracking.html'; ?>
