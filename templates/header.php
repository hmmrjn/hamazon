<meta charset="utf-8">
<?php
//ログアウト処理
if( isset($_GET['do']) && $_GET['do'] == "logout" ){
	$_SESSION['user_id'] = "";
	$_SESSION['user_name'] = "";
	header('location: /');
	exit();
}

//カートの中の商品の数を取得
$items_number = (isset($_SESSION['cart']))? count($_SESSION['cart']) : 0;

?>

<div class="flexbox-wrapper">
<div class="header-logo">
<a href="/">
<div class="logo">
<img src="/images/new_logo.png" height="48px">
</div>
<div id="textlogo" style="float: left; background-color:none; color: gray; line-height: 60px; font-size: 20px;">Hamazon</div>
</a>
</div>
<div class="header-search">
<div class="searchBox">
<form action="/search/" method="GET">
<input type="text" name="word" placeholder="商品名を検索" <?php if(isset($_GET['word'])) print("value=\"{$_GET['word']}\""); ?> />
<button type="submit"><i class="fa fa-search"></i></button>
</form>
</div>
</div>
<div class="header-menu">
<nav>
<ul>
<li><a href="/cart/"><div>
<i class="fa fa-shopping-cart"></i> カート<div class="items-number"><?= $items_number ?>
</div></div></a></li>
<?php if( isset($_SESSION['user_id']) && $_SESSION['user_id'] != "" ){ ?>
<li><a href="#"><div><?= $_SESSION['user_id'] ?></div>
<li id="nav-register"><a href="?do=logout"><div>ログアウト</div></a></li>
<?php } else { ?>
<li id="nav-register"><a href="/register/"><div>アカウント登録</div></a></li>
<li><a href="/login/"><div>ログイン</div></a></li>
<?php } ?>
</ul>
</nav>
</div>
</div>