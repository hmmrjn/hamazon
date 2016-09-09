<?php
session_start();

//データベース接続
include '../config.php';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) die("データベース接続エラー");

//SQL
$sql = "SELECT * FROM items WHERE id = " . $_GET['id'];
$res = $mysqli->query($sql);
$item = $res->fetch_array();

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
			<h2>注文詳細</h2>
			<?php
			$sql = "SELECT * FROM orders WHERE order_id = '".$_GET['id']."'";
			$res = $mysqli->query($sql);
			$order = $res->fetch_array();
			?>
			<ul>
				<li>注文番号：<?= $order['order_id'] ?></li>
				<li>注文日付：<?= $order['date'] ?></li>
				<li>合計金額：&yen; <?= $order['total_price'] ?></li>
			</ul>
			<?php
			$sql = "SELECT * FROM order_details INNER JOIN items ON order_details.item_id = items.id WHERE order_id = '".$_GET['id']."'";
			$res = $mysqli->query($sql);
			?>
			<table>
				<tr><th>商品名</th><th>注文時の金額</th><th>個数</th></tr>
				<?php while ( $product = $res->fetch_array() ){ ?>
					<tr><td><?= $product['name']; ?></td><td>&yen;<?= $product['priced'] ?></td><td><?= $product['quantity'] ?></td></tr>
					<?php } ?>
				</table>
			</main>
		</div>
	</body>
	</html>
	<style>
			table {
		border-collapse: collapse;
		background: white;
		margin: 10px;
		width: calc(100% - 20px);
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
		border-radius: 4px;
	}
	tr {
		border-bottom: 1px solid gainsboro;
		padding: 0 100px;
	}
	tr:hover {
		background: whitesmoke;
		transition: .6s;
	}
	tr:last-child {
		border-bottom: none;
	}
	td {
		padding: 20px;
	}
	</style>