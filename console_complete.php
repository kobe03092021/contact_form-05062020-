<?php
// セッションが開始でなければ、開始する
!isset($_SESSION) ? session_start() : "" ;
// クッキーが開始でなければ、開始する
!isset($_COOKIE["SSID"]) ? setcookie("SSID", "", time() + (24*60*60)) : session_regenerate_id(uniqid('', true));

// 定数クラス
require_once __DIR__."/app/ConstantClass.php";
// バリデーション用クラス
require_once __DIR__."/app/LoginValidateClass.php";
// データベース用クラス
require_once __DIR__."/app/LoginDBClass.php";
// 管理者用のアカウント設定
require_once(__DIR__."/account_settings.php");
// ページへのアクセスの処理用クラス
require_once(__DIR__.'/app/RedirectClass.php');
// リダイレクト処理
$page = new Redirect();
// ページアクセスの直打ち防止
$page->redirectToLogin();

// POSTされた値の格納
$posted = [];
$posted = $_POST;
$session = $_SESSION;
// var_dump($session);

// バリデーション用クラスのインスタンス化
$validate = new LoginValidate($posted);
// エラー判定時の戻り値を変数格納（パスワード更新時）
$update_password_errors = $validate->updatePasswordError($posted);
// var_dump($update_password_errors);
// DB用クラスのインスタンス化、プロパティ展開
$dbh = "";
$conn = new LoginDBConnect($dbh);
// ログインボタンが押されたら、DB上のデータ確認用のメソッドを呼び出し
$table = !empty($posted['login']) ? $conn->login($posted) : "";

?>

<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="css/caution.css">
		<!--管理者用ID/PWが入力されていれば、「管理者」を明示 -->
		<?php if($_SESSION['SSID']['user_name'] === $admin['user_name'] && $_SESSION['SSID']['user_password'] === $admin['user_password'] ) : ?>
			<?php echo '<title>管理画面</title>'; ?>
		<?php else : ?>
		<!--管理者用ID/PW以外が入力されていれば、「アカウント設定」を明示 -->
			<?php echo '<title>アカウント設定</title>' ?>
		<?php endif; ?>
	</head>
	<body>

				<!-- 「パスワード変更」を実行 -->
				<?php if (isset($posted['is_update']) && $posted['is_update'] === "confirm_update_password") : ?>
					<form method="POST">
						<section>
							<?php if($conn->userUpdatePassword($posted)) : ?>
								登録が完了しました。<br>
								<button type="submit" name="is_update" value="back_on_updated_password" formaction="console.php">戻る</button>
							<?php else : ?>
								登録が完了しませんでした。<br>
								<button type="submit" name="is_update" value="back_on_failed_update_password" formaction="console.php">戻る</button>
							<?php endif; ?>
						</section>
					</form>
				<?php endif; ?>
				<!--  「パスワード変更」が選択された場合  終了  -->
				<!-- 「新規ユーザ追加」が選択された場合 -->
				<?php if (isset($posted['is_create_new_user']) && $posted['is_create_new_user'] === "create_new_user") : ?>
					<form method="POST">
						<section>
							<?php if($conn->userCreate($posted)) : ?>
								ユーザ作成が完了しました。<br>
								<button type="submit" name="is_create_new_user" value="back_on_created_user" formaction="console.php">戻る</button>
							<?php else : ?>
								ユーザ作成が完了しませんでした。<br>
								<button type="submit" name="is_create_new_user" value="back_on_failed_created_user" formaction="console.php">戻る</button>
							<?php endif; ?>
						</section>
					</form>
				<?php endif; ?>

				<!-- 「アカウント削除」を実行 -->
				<?php if (isset($posted['is_delete_user']) && $posted['is_delete_user'] === "complete") : ?>
					<form method="POST">
						<section>
							<?php if($conn->userDeleteAccount($posted['user_name'])) : ?>
								削除が完了しました。
								<button type="submit" name="is_delete_user" value="back_on_delete_user" formaction="console.php">戻る</button>
							<?php else : ?>
								削除が完了しませんでした。
								<button type="submit" name="is_delete_user" value="back_on_failed_delete_user" formaction="console.php">戻る</button>
							<?php endif; ?>
						</section>
					</form>
				<?php endif; ?>
<!--  「アカウント削除」が選択された場合  終了  -->

	</body>
</html>