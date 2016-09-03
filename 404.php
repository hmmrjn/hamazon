<?php
session_start();
?>
<!Doctype html>
<html lang="ja">

<head>
<meta charset="utf-8">
<title>Hamazon | 通販 - ファッション、家電から食品まで</title>
<link rel="shortcut icon" href="/images/icon.ico">
<link rel="stylesheet" href="/common/normalize.css">
<link rel="stylesheet" href="/common/style.css">
<link rel="stylesheet" href="/common/animate.css">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>
<!--

888    888
888    888
888    888
8888888888  8888b.  88888b.d88b.   8888b.  88888888  .d88b.  88888b.
888    888     "88b 888 "888 "88b     "88b    d88P  d88""88b 888 "88b
888    888 .d888888 888  888  888 .d888888   d88P   888  888 888  888
888    888 888  888 888  888  888 888  888  d88P    Y88..88P 888  888
888    888 "Y888888 888  888  888 "Y888888 88888888  "Y88P"  888  888 Ver2.1.150724 PHP

(c) Copyright 2015 Hamazon co. Ltd. Some rights reserved. LOL

-->

<body>
<div id="container">
<header>
<?php $currentPage = "404"; include 'templates/header.php'; ?>
</header>
<main>
<div class="banner">
<img src="/images/entrance.jpg">
<p class="animated fadeInRight" id="title">404: Not found</p>
</div>
<section class="animated fadeIn">
<p>誠に残念なことが起きてしまいました。お探しのページは見つかりませんでした。まだ準備中であるか、もしくはURLが間違っています。</p>
<a href="/"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;ホームへ戻る</a>
</section>
</main>
<footer class="animated fadeIn">
<?php include 'templates/footer.php'; ?>
</footer>
</div>
</body>

</html>
<?php include 'common/analyticstracking.html'; ?>
