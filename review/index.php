<?php
session_start();

//XSS対策
function h($s){
	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

//データベース接続
include '../config.php';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) die("データベース接続エラー");

//保存が押された場合
if (isset($_POST['do']) && $_POST['do'] == "save") {
	$esc_title   = $mysqli->real_escape_string($_POST['title']);   //SQLI対策
	$esc_content = $mysqli->real_escape_string($_POST['content']); //SQLI対策
	$esc_r_id    = $mysqli->real_escape_string($_GET['r_id']);     //SQLI対策
	$sql = "UPDATE reviews SET rate = {$_POST['rate']}, 
	title = '{$esc_title}', 
	content = '{$esc_content}'  
	WHERE review_id = '{$esc_r_id}'
	AND user_id = '{$_SESSION['user_id']}'";
	if( $mysqli->query($sql) ){
		$message = "レビューを更新しました。";
	} else {
		$message = "<b>システムエラー</b><br/>更新できませんでした。運営までお問い合わせ下さい。";
	}
}

//変数初期化
$r_exists  = false;
$r_user_id = "";
$r_rate    = "";
$r_title   = "";
$r_content = "";
$correct_user = false;

//レビューデータの読み込み
$esc_r_id = $mysqli->real_escape_string($_GET['r_id']); //SQLI対策
$sql = "SELECT * FROM reviews WHERE review_id='{$esc_r_id}'";
$res = $mysqli->query($sql);

//レビューIDが存在する場合
if($review = $res->fetch_array()){
	$r_exists  = true;
	$r_user_id = $review['user_id'];
	$r_rate    = $review['rate'];
	$r_title   = $review['title'];
	$r_content = $review['content'];
	//投稿者本人の場合
	if( isset($_SESSION['user_id']) && $_SESSION['user_id'] == $r_user_id ){
		$correct_user = true;
	}
//レビューIDが存在しない場合
} else {
	$message = "無効なレビューIDです。";
}

//編集モードの場合
if( isset($_GET['do']) && $_GET['do']=="edit" && $r_exists ){
	if( !isset($_SESSION['user_id']) || $_SESSION['user_id']=="" ) {
		$message = "レビューを編集するには<a href=\"/login/\">ログイン</a>してある必要があります。";
	} elseif (!$correct_user) {
		//..投稿者本人でない場合
		$message = "他人のレビューを編集することはできません。";
	}
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
888    888 "Y888888 888  888  888 "Y888888 88888888  "Y88P"  888  888 4beta.160801

-->
<body>
<header>
<div class="container">
<?php $currentPage='home'; include '../templates/header.php' ?>
</div>
</header>
<div id="container">
<main class="animated fadeIn">
<?php if( isset($message) ) { ?>
<div class="notifybox"><?= $message ?></div>
<br/><br/>
<?php } 
if( isset($_GET['do']) && $_GET['do']=="edit" && $correct_user){
?>
<h2>レビューを編集</h2>
<form action="/review/?r_id=<?= $_GET['r_id'] ?>" method="post">
<div id="rating" value="<?= $r_rate ?>"></div>
<input id="rate" name="rate" type="hidden">
<input type="text" name="title" class="rev-title" placeholder=" 見出し" value="<?= $r_title ?>"/><br>
<textarea name="content" placeholder=" 本文"　rows="4" cols="40"><?= $r_content ?></textarea><br>
<input type="hidden" name="do" value="save"/>
<input type="submit" value="保存"/>
</form>
<?php } elseif($r_exists) { ?>
<span class="rev-rate" data-score="<?= $r_rate ?>"></span>
<b><?= h($r_title) ?></b><br/>
<?= h($r_content) ?><br/>
<?php if($r_user_id=='') $user_id = "(未ログインユーザ)";
else $user_id = h($r_user_id);
print( $user_id . "&nbsp&nbsp" . $review['date']);
if($correct_user) {
print "<br/><br/><a href=\"/review/?do=edit&r_id={$_GET['r_id']}\" class=\"cntbtn active\">編集</a>";
}
?>
<?php } ?>
</main>
<footer>
<?php include '../templates/footer.php'; ?>
</footer>
</div>
</body>
<style>
.rev-title {
margin: 10px 0;
}
.rev-rate{
margin-right: 10px;
}
</style>
<script>
$('.rev-rate').raty({
readOnly: true,
half:  true,
path: '/images/',
score: function() {
return $(this).attr('data-score');
}
});
$('#rating').raty({
path: '/images/',
target : "[name='rate']",
targetType: 'score',
targetKeep : true,
score: function() {
return $(this).attr('value');
}
});
</script>
</html>
<?php include '../common/analyticstracking.html'; ?>