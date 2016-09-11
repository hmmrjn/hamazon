<?php
session_start();

//データベース接続
require "config.php";
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) die("データベース接続エラー");

//SQL ベストセラー
$sql_top3 ="SELECT * FROM (SELECT * FROM (SELECT item_id,SUM(quantity) AS sum FROM order_details GROUP BY item_id) aaa ORDER BY sum DESC LIMIT 0, 3) bbb INNER JOIN items ON bbb.item_id = items.id INNER JOIN (SELECT item_id, CAST(AVG(rate) AS DECIMAL(10,1)) AS avg FROM reviews GROUP BY item_id) ccc ON bbb.item_id = ccc.item_id ORDER BY sum DESC";

//SQL 評価が高い商品
$sql_best3 = "SELECT avg, id, name, price FROM (SELECT item_id, CAST(AVG(rate) AS DECIMAL(10,1)) AS avg FROM reviews GROUP BY item_id) aaa INNER JOIN items ON aaa.item_id = items.id ORDER BY avg DESC LIMIT 0, 3";

//SQL 新着商品
$sql_new3 = "SELECT date, id, name, price FROM items ORDER BY date DESC LIMIT 0, 3";

?>
<!Doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<title>Hamazon | 通販 - ファッション、家電から食品まで</title>
<link rel="shortcut icon" href="images/icon.ico">
<link rel="stylesheet" href="common/normalize.css">
<link rel="stylesheet" href="common/style.css">
<link rel="stylesheet" href="common/animate.css">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
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
888    888 "Y888888 888  888  888 "Y888888 88888888  "Y88P"  888  888 Ver4.0.160727beta PHP

(c) Copyright 2016 Hamazon co. Ltd. Some rights reserved.

-->
<body>
<header>
<div class="container">
<?php $currentPage='home'; include 'templates/header.php' ?>
</div>
</header>
<div id="container">
<div class="large-banner">
<div id="large-banner-content">
<p class="animated fadeInUp">Hamazonでは通販をリアリスティックに疑似体験できます。<br/>
実際にアカウント登録したり、レビューを投稿できるよ。</p>
<a href="/all_products/" class="cntbtn active" id="all-products-btn"><i class="fa fa-th-list"></i>商品一覧</a>
</div>
</div>
<main class="animated fadeIn">
<section>
<h2>ベストセラー</h2>
<div class="items-container">
<?php
//ベストセラー
$res_top3 = $mysqli->query($sql_top3);
$item_index = 1;
while( $item = $res_top3->fetch_array() ) {
$price =  number_format( $item['price'] );
?>

<div class="item">
<div class="item-image">
<a href="product/?id=<?= $item['id'] ?>"><img src="images/product<?= $item['id'] ?>.jpg" alt="<?= $item['name'] ?>の画像" height="160px"></a>
</div>
<div class="item-name">
<?= $item_index ?>. <a href="product/?id=<?= $item['id'] ?>"><?= $item['name'] ?></a>
</div>
<div class="item-rate">
<span class="rev-rate" value="<?= $item['avg'] ?>"></span> (<?= $item['avg'] ?>)
</div>
<div class="item-price">
<font color="darkred">&yen;<?= $price ?></font>
</div>
</div>
<?php
$item_index++;
}
$res_top3->free_result();
?>
</div>
<h2>評価の高い商品</h2>
<div class="items-container">
<?php
//評価の高い商品
$res_best3 = $mysqli->query($sql_best3);
$item_index = 1;
while( $item = $res_best3->fetch_array() ) {
$price =  number_format( $item['price'] );
?>

<div class="item">
<div class="item-image">
<a href="product/?id=<?= $item['id'] ?>"><img src="images/product<?= $item['id'] ?>.jpg" alt="<?= $item['name'] ?>の画像" height="160px"></a>
</div>
<div class="item-name">
<?= $item_index ?>. <a href="product/?id=<?= $item['id'] ?>"><?= $item['name'] ?></a>
</div>
<div class="item-rate">
<span class="rev-rate" value="<?= $item['avg'] ?>"></span> (<?= $item['avg'] ?>)
</div>
<div class="item-price">
<font color="darkred">&yen;<?= $price ?></font>
</div>
</div>
<?php
$item_index++;
}
$res_best3->free_result();
?>
</div>
<h2>新着商品</h2>
<div class="items-container">
<?php
//新着商品
$res_new3 = $mysqli->query($sql_new3);
$item_index = 1;
while( $item = $res_new3->fetch_array() ) {
$price =  number_format( $item['price'] );
//各新着商品の評価を取得
$new3_eachrate_sql = "SELECT IFNULL( CAST(AVG(rate) AS DECIMAL(10,1)) , 0) AS avg FROM reviews WHERE item_id = {$item['id']}";
$res_new3_eachrate = $mysqli->query($new3_eachrate_sql);
$rate = $res_new3_eachrate->fetch_array();
$reg_date = date_create($item['date']);
$reg_date = date_format($reg_date, 'Y年 m月 d日');
?>

<div class="item">
<div class="item-image">
<a href="product/?id=<?= $item['id'] ?>"><img src="images/product<?= $item['id'] ?>.jpg" alt="<?= $item['name'] ?>の画像" height="160px"></a>
</div>
<div class="item-name">
<?= $item_index ?>. <a href="product/?id=<?= $item['id'] ?>"><?= $item['name'] ?></a>
</div>
<div class="item-rate">
<span class="rev-rate" value="<?= $rate['avg'] ?>"></span> (<?= $rate['avg'] ?>)
</div>
<div class="item-price">
<font color="darkred">&yen;<?= $price ?></font><br/>
</div>
<div class="item-date">登録日：<?= $reg_date ?></div>
</div>
<?php
$item_index++;
}
$res_new3->free_result();
$res_new3_eachrate->free_result();
?>
</div>
<div id="all-products-btn-wrapper">
<a href="/all_products/" class="cntbtn active" id="all-products-btn"><i class="fa fa-th-list"></i>商品一覧</a>
</div>
<h2>更新情報</h2>
<?php include 'templates/updates.html'; ?>
<a href="http://www.mlab.im.dendai.ac.jp/~15fi103/"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;15fi103へ戻る</a>
</section>
</main>
<footer class="animated fadeIn">
<?php include 'templates/footer.php'; ?>
</footer>
</div>
</body>
<script>
$('.rev-rate').raty({
readOnly: true,
half:  true,
path: '/images/',
score: function() {
return $(this).attr('value');
}
});
</script>
</html>
<?php include 'common/analyticstracking.html'; ?>