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
<a href="/"><div class="logo" style="background-color:none;">
<img src="/images/new_logo.png" height="48px">
</div><div style="float: left; display: inline-block;background-color:none; color: gray; line-height: 60px; font-size: 20px;">Hamazon</div></a>
<div class="searchBox">
<form action="/search/" method="GET">
<input type="text" name="word" placeholder="商品名を検索"<?php if(isset($_GET['word'])) print("value=\"{$_GET['word']}\""); ?>/>
<button type="submit"><i class="fa fa-search"></i></button>
</form>
</div>
<nav>
<ul>
<?php if( isset($_SESSION['user_id']) && $_SESSION['user_id'] != "" ){ ?>
<li<?php if($currentPage=='cart') print ' class="selected"'; ?>><a href="/cart/"><div><i class="fa fa-shopping-cart"></i>&nbsp;カート<div class="items-number"><?= $items_number ?></div></div></a></li>
<li><a href="/account/"><div><?php print $_SESSION['user_id'];?></div></a></li>
<li><a href="?do=logout"><div>ログアウト</div></a></li>
<?php } else { ?>
<li<?php if($currentPage=='cart') print ' class="selected"'; ?>><a href="/cart/"><div><i class="fa fa-shopping-cart"></i>&nbsp;カート<div class="items-number"><?= $items_number ?></div></div></a></li>
<li><a href="/register/"><div>アカウント登録</div></a></li>
<li><a href="/login/"><div>ログイン</div></a></li>
<?php } ?>
</ul>
</nav>