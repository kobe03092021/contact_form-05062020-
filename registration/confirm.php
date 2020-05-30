<?php

// POST経由でなければ、リダイレクト（ディレクトリ内最初のページへ）
if ( $_SERVER['REQUEST_METHOD'] !== 'POST') {
	header("location:input.php");
	exit;
	}

// 定数クラス
require_once "../app/ConstantClass.php";
// バリデーション用クラス（DBユーザ登録画面）
require_once "../app/UserValidateClass.php";
// データベース用クラス（DBユーザ登録画面）
require_once "../app/UserDBClass.php";

// POSTされた値の格納
$posted = [];
$posted = $_POST;

// バリデーション用クラスのインスタンス化（DBユーザ登録画面）
$registration_validate = new UserValidate($posted);
// エラーの戻り値を変数格納（DBユーザ登録画面）
$registration_errors = $registration_validate->initialRegistrationErrors($posted);

?>

<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="../css/common.css">
		<title>管理画面　新規ユーザ登録</title>
	</head>
	<body>
		<!--ログイン画面から飛ばされてきた場合、以下の順でDB登録-->
			<h3>新規ユーザ登録確認</h3>
			<form method="POST">

				<section>
					<p>以下の内容でよろしいでしょうか？</p>

					<div>
						<label>■ ユーザ名</label><br>
						<?= $registration_validate->escape('user_name'); ?>
						<input type="hidden" name="user_name" value="<?= $registration_validate->escape('user_name'); ?>">
					</div>
					<div>
						<label>■ パスワード</label><br>
						<?= $registration_validate->escape('user_password'); ?>
						<input type="hidden" name="user_password" value="<?= $registration_validate->escape('user_password'); ?>">
					</div>
					<div>
						<label>■ 確認用パスワード</label><br>
						<?= $registration_validate->escape('confirm_user_password'); ?>
						<input type="hidden" name="confirm_user_password" value="<?= $registration_validate->escape('confirm_user_password'); ?>">
					</div>
				</section>

						<button type="submit" name="registration" value="back" formaction="input.php">戻る</button>
						<button type="submit" name="registration" value="insert_new" formaction="upload.php">登録</button>
			</form>
	</body>
</html>