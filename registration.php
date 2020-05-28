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
$page->redirectToLogin();

// POSTされた値の格納
$posted = [];
$posted = $_POST;

// バリデーション用クラスのインスタンス化
$validate = new LoginValidate($posted);
// エラー判定時の戻り値を変数格納
$registration_errors = $validate->initialRegistrationErrors();
// DB用クラスのインスタンス化、プロパティ展開
$dbh = "";
$conn = new LoginDBConnect($dbh);
// ユーザ名の重複チェック　(エラー出力用の配列はメソッド内で宣言)
if(isset($posted['registration']) ) {
	!empty($conn->userDuplication($posted)) ? $registration_errors['duplication']['user_name'] = "入力されたユーザ名はすでに登録されています"."</br>" : null;
}
'<pre>';
print_r($registration_errors)."<br>";
'</pre>';

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

		<!--　入力画面　-->
			<h3>新規ユーザ入力</h3>
			<form action="" method="POST">

				<section>
					<div>
						<label>■ ユーザ名</label><br>
						<input type="text" name="user_name" value="<?= $validate->escape('user_name'); ?>">
						<div class="caution">
							<?= isset($registration_errors['is_empty']['user_name']) ? $registration_errors['is_empty']['user_name'] : null ?>
							<?= isset($registration_errors['pregmatch']['user_name']) ? $registration_errors['pregmatch']['user_name'] : null ?>
							<?= isset($registration_errors['duplication']['user_name']) ? $registration_errors['duplication']['user_name'] : null ?>
						</div>
					</div>

					<div>
						<label>■ パスワード</label><br>
						<input type="text" name="user_password" value="<?= $validate->escape('user_password'); ?>">
						<div class="caution">
							<?= isset($registration_errors['is_empty']['user_password']) ? $registration_errors['is_empty']['user_password'] : null ?>
							<?= isset($registration_errors['pregmatch']['user_password']) ? $registration_errors['pregmatch']['user_password'] : null ?>
						</div>
					</div>

					<div>
						<label>■ 確認用パスワード</label><br>
						<input type="text" name="confirm_user_password" value="<?= $validate->escape('confirm_user_password'); ?>">
						<div class="caution">
							<?= isset($registration_errors['is_empty']['confirm_user_password']) ? $registration_errors['is_empty']['confirm_user_password'] : null ?>
							<?= isset($registration_errors['pregmatch']['confirm_user_password']) ? $registration_errors['pregmatch']['confirm_user_password'] : null ?>
							<?= isset($registration_errors['equality']['confirm_user_password']) ? $registration_errors['equality']['confirm_user_password'] : null ?>
						</div>
					</div>
				</section>

						<button type="submit" name="registration" value="reload" formaction="#reload">確認</button>
					<?php if(isset($_POST["registration"]) && empty(array_filter($registration_errors))) : ?>
						<button type="submit" name="registration" value="check_new" formaction="registration_confirm.php">進む</button>
					<?php endif; ?>
			</form>

	</body>
</html>