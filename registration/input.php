<?php

// 定数クラス
require_once '../app/ConstantClass.php';
// バリデーション用クラス（DBユーザ登録画面）
require_once '../app/UserValidateClass.php';
// データベース用クラス（DBユーザ登録画面）
require_once '../app/UserDBClass.php';

// POSTされた値の格納
$posted = [];
$posted = $_POST;

// バリデーションのインスタンス化（DBユーザ登録画面）
$registration_validate = new UserValidate($posted);
// エラーの戻り値を変数格納（DBユーザ登録画面）
$registration_errors = $registration_validate->initialRegistrationErrors($posted);

// DB共通プロパティを展開
$dbh = "";
// DBクラスをインスタンス化（DBユーザ登録画面）
$login_conn = new UserDBConnect($dbh);

// ユーザ名の重複チェック　(エラー出力用の配列はメソッド内)
if(isset($posted['registration']) ) {
	!empty($login_conn->userDuplication($posted)) ? $registration_errors['duplication']['user_name'] = "入力されたユーザ名はすでに登録されています"."</br>" : null;
}

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
		<div>
			<!--　入力画面　-->
			<h3>新規ユーザ入力</h3>
			<form method="POST">
				<div>
					<section>
						<div>
							<label>■ ユーザ名<span class="require">必須</span></label><br>
							<input type="text" name="user_name" value="<?= $registration_validate->escape('user_name'); ?>">
							<div class="caution">
								<?= isset($registration_errors['empty']['user_name']) ? $registration_errors['empty']['user_name'] : null ?>
								<?= isset($registration_errors['pregmatch']['user_name']) ? $registration_errors['pregmatch']['user_name'] : null ?>
								<?= isset($registration_errors['duplication']['user_name']) ? $registration_errors['duplication']['user_name'] : null ?>
							</div>
						</div>
						<div>
							<label>■ パスワード<span class="require">必須</span></label><br>
							<input type="text" name="user_password" value="<?= $registration_validate->escape('user_password'); ?>">
							<div class="caution">
								<?= isset($registration_errors['empty']['user_password']) ? $registration_errors['empty']['user_password'] : null ?>
								<?= isset($registration_errors['pregmatch']['user_password']) ? $registration_errors['pregmatch']['user_password'] : null ?>
							</div>
						</div>
						<div>
							<label>■ 確認用パスワード<span class="require">必須</span></label><br>
							<input type="password" name="confirm_user_password" value="<?= $registration_validate->escape('confirm_user_password'); ?>">
							<div class="caution">
								<?= isset($registration_errors['empty']['confirm_user_password']) ? $registration_errors['empty']['confirm_user_password'] : null ?>
								<?= isset($registration_errors['pregmatch']['confirm_user_password']) ? $registration_errors['pregmatch']['confirm_user_password'] : null ?>
								<?= isset($registration_errors['equality']['confirm_user_password']) ? $registration_errors['equality']['confirm_user_password'] : null ?>
							</div>
						</div>
					</section>
				</div>
				<div>
					<button type="submit" name="registration" value="reload" formaction="#reload">確認</button>
					<?php if(isset($_POST["registration"]) && empty(array_filter($registration_errors))) : ?>
						<button type="submit" name="registration" value="check_new" formaction="confirm.php">進む</button>
					<?php endif; ?>
				</div>

			</form>
		</div>

	</body>
</html>