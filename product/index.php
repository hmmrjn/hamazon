<?php
session_start();

//商品IDの指定がない場合はリダイレクト
if(!isset($_GET['id'])) header('Location: /');

//データベース接続
include '../config.php';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) die("データベース接続エラー");

//SQL
$sql = "SELECT * FROM items WHERE id = '".$_GET['id']."' AND deleted = '0' ";
$res = $mysqli->query($sql);
$item = $res->fetch_array();
$price =  number_format( $item['price'] );
$res->free_result();

//XSS対策
function h($s){
	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

//投稿を保存
if (isset($_POST['do']) && $_POST['do'] == "save") {
	//SQLインジェクション対策
	$title = $mysqli->real_escape_string($_POST['title']);
	$content = $mysqli->real_escape_string($_POST['content']);
	$sql = "INSERT INTO reviews (rate, title, content, item_id, user_id, date) VALUES (
	'{$_POST['rate']}', 
	'{$title}', 
	'{$content}', 
	'{$_GET['id']}', 
	'{$_SESSION['user_id']}', 
	now()
	)";
	$mysqli->query($sql);
}
//平均評価の計算
$sql = "SELECT CAST(AVG(rate) AS DECIMAL(10,1)) FROM reviews WHERE item_id = '{$_GET['id']}'";
$res = $mysqli->query($sql);
$avg = $res->fetch_array();
$res->free_result();
//各評価のカウント(星5が3つとか)
$sql = "SELECT rate, COUNT(*) AS count FROM reviews GROUP BY rate, item_id HAVING item_id = {$_GET['id']}";
$res = $mysqli->query($sql);
$arc = array_fill(1, 5, null); //arc: all rates count
while ($rc = $res->fetch_array()){ //rc: rates count
	for($i = 1; $i <= 5; $i++){
		if($rc['rate'] == $i) $arc[$i] = $rc['count'];
		elseif($arc[$i] == null) $arc[$i] = 0;
	}
}
$res->free_result();
//投稿のページ分け
$posts_by_page = 4;
if (isset($_GET['page'])) $current_page = $_GET['page'];
else $current_page = 1;
$start_point = $posts_by_page * ($current_page - 1);
$sql = "SELECT COUNT(*) FROM reviews WHERE item_id = '{$_GET['id']}'";
$res = $mysqli->query($sql);
$count = $res->fetch_array();
$res->free_result();
$all_pages = ($count[0] - 1) / $posts_by_page + 1;

?>
<!Doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Hamazon | 通販</title>
<link rel="shortcut icon" href="/images/icon.ico">
<link rel="stylesheet" href="/common/normalize.css">
<link rel="stylesheet" href="/common/animate.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="/common/style.css">
<link rel="stylesheet" href="stylesheet.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
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
888    888 "Y888888 888  888  888 "Y888888 88888888  "Y88P"  888  888 4beta.2.160329
-->

<body>
<div id="container">
<header>
<?php $currentPage='none'; include '../templates/header.php' ?>
</header>
<main class="animated fadeIn">
<section>
<div class="box rightbox">
<em class="big">&yen;&nbsp;<?php print( $price ); ?></em>(税別)<br>
<em class="small">ポイント:<?php if($item['price']/100>10000) { print("100"); } else { print($item['price']/100); } ?>&nbsp;pt</em><br><br>
<form action="/cart/" method="get">
<input type="hidden" name="do" value="add"/>
<input type="hidden" name="p" value="<?php print $item['id']; ?>"/>
数量：
<select name="q">
<?php for($i=1; $i<=$item['stock']; $i++){ ?>
<option value="<?php print $i; ?>"><?php print $i; ?></option>
<?php } ?>
</select>
<input type="submit" value="カートに入れる" />
</form>
</a>
</div>
<div class="leftbox">
<div id="thumbnail-box">
<div id ="thumbnail-box-inner">
<img id="photo-opener" src="/images/product<?php print( $_GET['id'] ); ?>.jpg" width="250px"><i class="fa fa-search-plus"></i></div><em class="small">画像をクリックして拡大イメージを表示</em>
</div>
<em class="big"><b><?php print( $item['name'] ); ?></b></em><br>
<?php print( $item['date'] ); ?><br><span class="rev_rate" data-score="<?php print($avg[0]); ?>"></span><?php print($count[0]); ?>件のカスタマーレビュー<hr>
<em class="darkred"><em class="big"><b>&yen;&nbsp;<?php print( $price ); ?></b></em>(税別)</em>
<b>通常配送無料</b></em><br>
<em class="green"><b><?php print( $item['stock'] ); ?>点</b>在庫有り。</em><br>
この商品は、Hamazonが販売、発送します。<br>
ギフトラッピングは利用できません。<br>
</a>
</div>
</section>
<section>
<h2>商品の説明</h2>
<?php print( $item['details'] ); ?>
</section>
<section>
<h2>カスタマーレビュー(<?php print($count[0]); ?>件)</h2>
<div id="ratings-left-box">
<?php for($i=5; $i>=1; $i--){
print "星$i <div class=\"progressbar\" value=\"$arc[$i]\"></div> ($arc[$i])<br>\n";
} ?>
</div>
<hr/>
<?php

//投稿の表示
$sql = "SELECT * FROM reviews WHERE item_id = '{$_GET['id']}' ORDER BY review_id DESC LIMIT " . $start_point . ", $posts_by_page";
$res = $mysqli->query($sql);
while ($review = $res->fetch_array()) { ?>
<span class="rev_rate" data-score="<?php print($review['rate']); ?>"></span><b><?php
print( h($review['title']) ); ?>
</b><br/>
<?php print( h($review['content']) ); ?><br/>
<?php if($review['user_id']=='') $user_id = "(未ログインユーザ)";
else $user_id = h($review['user_id']);
print( $user_id . "&nbsp&nbsp" . $review['date']); 
if($review['user_id'] == $_SESSION['user_id']) {
print " <a href=\"/review/?do=edit&r_id={$review['review_id']}\">編集</a>";
}
?>
<hr/>
<?php
}
$res->free_result();

//ページ番号の表示
print("PAGE:");
if ($current_page != 1) {
?>
&nbsp&nbsp<a href="?id=<?php print($_GET['id']); ?>&page=<?php print($current_page - 1); ?>">&lt</a>
<?php
} else {
print("&nbsp&nbsp&nbsp&lt&nbsp");
}
for ($i = 1; $i <= $all_pages; $i++) {
if ($i == $current_page)
print("&nbsp&nbsp" . $i . "&nbsp&nbsp");
else
print("&nbsp&nbsp<a href=\"?id=" . $_GET['id'] . "&page=" . $i . "\">" . $i . "</a>&nbsp&nbsp");
}
if ($current_page <= $all_pages - 1) {
?>
&nbsp&nbsp<a href="?id=<?php print($_GET['id']); ?>&page=<?php print($current_page + 1); ?>">&gt</a>
<?php
} else {
print("&nbsp&nbsp&gt");
}
?>
<hr>
<h2>レビューを投稿</h2>
<form action="" method="post">
<div id="rating"></div>
<input id="rate" name="rate" type="hidden">
<input type="text" name="title" placeholder=" 見出し" value=""/><br>
<textarea name="content" placeholder=" 本文"　rows="4" cols="40"></textarea><br>
<input type="hidden" name="do" value="save"/>
<input type="submit" value="投稿"/>
</form>
</section>
</main>
<footer>
<?php include '../templates/footer.php'; ?>
</footer>
</div>
<div class="photo">
<img src="../images/product<?php print( $_GET['id'] ); ?>.jpg"><br>
<?php print( $item['name'] ); ?>
</div>
<script src="script.js"></script>
<script>
$(".progressbar").each(function() {
var $self = $(this);
var $selfVal = parseInt($(this).attr('value'));
$self.progressbar({
value: $selfVal,
max: <?php echo $count[0]; ?>
});
});
</script>
<style>
.rev_rate{
margin-right: 10px;
}
</style>
</body>
</html>
<?php include '../common/analyticstracking.html'; ?>
