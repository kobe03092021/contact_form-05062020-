<?php

// POST経由でなければ、リダイレクト（ディレクトリ内最初のページへ）
if ( $_SERVER['REQUEST_METHOD'] !== 'POST') {
	header("location:input.php");
	exit;
	}

// 定数クラス
require_once '../app/ConstantClass.php';
// バリデーション用クラス（DBユーザ登録画面）
require_once '../app/UserValidateClass.php';
// DBクラス（DBユーザ登録画面）
require_once '../app/UserDBClass.php';

// POSTされた値の格納
$posted = [];
$posted = $_POST;

// バリデーション用クラスのインスタンス化（DBユーザ登録画面）
$registration_validate = new UserValidate($posted);
// エラーの戻り値を変数格納（DBユーザ登録画面）
$registration_errors = $registration_validate->initialRegistrationErrors($posted);

// DB共通プロパティを展開 
$dbh = "";
// DB用クラスのインスタンス化（ユーザ登録画面用）
$login_connect = new UserDBConnect($dbh);
// テーブルにPOSTされた値を書き込み
$login_connect->initialRegistration($posted);

?>

<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="../css/common.css">
		<title>新規ユーザ登録　完了</title>
	</head>
	<body>
		<div>
			<!--パンくず開始-->
			<div class="">
				<ul class="">
					<li><a href="../manage/login.php">ログイン画面</a></li>
					<li>新規ユーザ登録　完了</li>
				</ul>
			</div>
			<!--パンくず終了-->
			<main id="">
				<section>
					<div class="">
						<p class ="">
						ご登録ありがとうございました。<br>
						</p>
					</div>
				</section>
			</main>
		</div>
	</body>
</html>
<script>
	setTimeout("header()", 5000);
	function header() {
		alert("ログイン画面へ戻ります。")
		location.href="../manage/login.php";
		}
</script>