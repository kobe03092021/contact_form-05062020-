<?php

// 定数クラス
require_once __DIR__."/app/ConstantClass.php";
// バリデーション用クラス
require_once __DIR__."/app/LoginValidateClass.php";
// データベース用クラス
require_once __DIR__."/app/LoginDBClass.php";

// POSTされた値の格納
$posted = [];
$posted = $_POST;

// バリデーション用クラスのインスタンス化
$validate = new LoginValidate($posted);
// エラー判定時の戻り値を変数格納
$registration_errors = $validate->initialRegistrationErrors();
?>

<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="css/caution.css">
		<title>管理画面（新規ユーザ登録）</title>
	</head>
	<body>
	<!--ログイン画面から飛ばされてきた場合、以下の順でDB登録-->
			<!--　DB登録　-->
				<h3>新規ユーザ登録</h3>
			<?php
			// DB用クラスの変数定義 
			$dbh = "";
			// DB用クラスのインスタンス化
			$connect = new LoginDBConnect($dbh);
			// テーブルにPOSTされた値を書き込み
			$connect->initialRegistration($posted);
			?>
			<script>
			setTimeout("header()", 5000);
			function header() {
				alert("登録が完了しました。ログイン画面へ戻ります")
				location.href="login.php";
			}
			</script>
			
	</body>
</html>