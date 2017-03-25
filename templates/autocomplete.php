<?php
//データベース接続
include '../config.php';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) die("データベースの接続エラー");

// 現在入力中の文字を取得
$term = (isset($_GET['term']) && is_string($_GET['term'])) ? $_GET['term'] : '';

//キーワードを含むレコードを引き抜く
$esc_term = $mysqli->real_escape_string($term); //SQLI対策
$sql = "SELECT name FROM items WHERE name COLLATE utf8_unicode_ci LIKE '%{$esc_term}%'";
$res = $mysqli->query($sql);
$words = array();
while( $item = $res->fetch_array() )
	$words[] = $item['name'];
$res->free_result();

header("Content-Type: application/json; charset=utf-8");
echo json_encode($words);
?>