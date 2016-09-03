<meta charset="utf-8">
<?php
//ログアウト処理
if( isset($_GET['do']) && $_GET['do'] == "logout" ){
	$_SESSION['user_id'] = "";
	$_SESSION['user_name'] = "";
	header('location: /');
	exit();
}

?>
<div class="logo">
<a href="/"><img src="/images/logo.png" height="40px"></a>
</div>
<div class="searchBox">
<form action="/search/" method="GET">
<input type="text" name="word" placeholder="商品名を検索"<?php if(isset($_GET['word'])) print("value=\"{$_GET['word']}\""); ?>/>
<input type="submit" value="検索" />
</form>
</div>
<nav>
<ul>
<?php if( isset($_SESSION['user_id']) && $_SESSION['user_id'] != "" ){ ?>
<li<?php if($currentPage=='cart') print ' class="selected"'; ?>><a href="/cart/"><div><i class="fa fa-shopping-cart"></i>&nbsp;カート</div></a></li>
<li><a href="/account/"><div><?php print $_SESSION['user_id'];?></div></a></li>
<li><a href="?do=logout"><div>ログアウト</div></a></li>
<?php } else { ?>
<li<?php if($currentPage=='cart') print ' class="selected"'; ?>><a href="/cart/"><div><i class="fa fa-shopping-cart"></i>&nbsp;カート</div></a></li>
<li><a href="/register/"><div>アカウント登録</div></a></li>
<li><a href="/login/"><div>ログイン</div></a></li>
<?php } ?>
</ul>
</nav>