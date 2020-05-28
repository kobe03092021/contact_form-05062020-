<?php

// 定数クラス
require_once __DIR__."/app/ConstantClass.php";
// バリデーション用クラス
require_once __DIR__."/app/LoginValidateClass.php";
// データベース用クラス
require_once __DIR__."/app/LoginDBClass.php";
// ページへのアクセスの処理用クラス
require_once(__DIR__.'/app/RedirectClass.php');
// リダイレクト処理
$page = new Redirect();
// ページアクセスの直打ち防止
$page->redirectToRegistration();

// POSTされた値の格納
$posted = [];
$posted = $_POST;

// バリデーション用クラスのインスタンス化
$validate = new LoginValidate($posted);
// エラー判定時の戻り値を変数格納
$registration_errors = $validate->initialRegistrationErrors();
var_dump($registration_errors);

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

		<!--　確認画面　-->
			<h3>新規ユーザ登録確認</h3>
			<form method="POST">

				<section>
					<p>以下の内容でよろしいでしょうか？</p>

					<div>
						<label>■ ユーザ名</label><br>
						<?= $validate->escape('user_name'); ?>
						<input type="hidden" name="user_name" value="<?= $validate->escape('user_name'); ?>">
					</div>

					<div>
						<label>■ パスワード</label><br>
						<?= $validate->escape('user_password'); ?>
						<input type="hidden" name="user_password" value="<?= $validate->escape('user_password'); ?>">
					</div>

					<div>
						<label>■ 確認用パスワード</label><br>
						<?= $validate->escape('confirm_user_password'); ?>
						<input type="hidden" name="confirm_user_password" value="<?= $validate->escape('confirm_user_password'); ?>">
					</div>
				</section>

						<button type="submit" name="registration" value="back" formaction="registration.php">戻る</button>
						<button type="submit" name="registration" value="insert_new" formaction="registration_upload.php">登録</button>
			</form>
	</body>
</html>