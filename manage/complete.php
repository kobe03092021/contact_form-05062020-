<?php

// セッションが開始でなければ、開始
!isset($_SESSION) ? session_start() : "" ;
// クッキーが開始でなければ、開始
!isset($_COOKIE["SSID"]) ? setcookie("SSID", "", time() + (24*60*60)) : session_regenerate_id(uniqid('', true));

// POST経由でなければ、リダイレクト（ディレクトリ内最初のページへ）
if ( $_SERVER['REQUEST_METHOD'] !== 'POST') {
	header("location:login.php");
	exit;
	}

// 定数クラス
require_once "../app/ConstantClass.php";
// バリデーション用クラス（管理画面用）
require_once "../app/UserValidateClass.php";
// DB処理クラス（管理画面用）
require_once "../app/UserDBClass.php";
// 管理者アカウント設定
require_once "../settings/admin_account.php";

// POSTされた値の格納
$posted = [];
$posted = $_POST;
$session = $_SESSION;

// バリデーション用クラスのインスタンス化（管理画面用）
$validate = new UserValidate($posted);
// エラーの戻り値を変数格納（パスワード更新時）
$update_password_errors = $validate->updatePasswordError($posted);

// DB共通プロパティを展開
$dbh = "";
// DBクラスをインスタンス化（管理画面用）
$login_conn = new UserDBConnect($dbh);

?>

<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="../css/common.css">
		<!--管理者用ID/PWが入力されていれば、「管理者」を明示 -->
		<?php if($_SESSION['SSID']['user_name'] === $admin['user_name'] && $_SESSION['SSID']['user_password'] === $admin['user_password'] ) : ?>
			<?php echo '<title>管理画面</title>'; ?>
		<?php else : ?>
		<!--管理者用ID/PW以外が入力されていれば、「アカウント設定」を明示 -->
			<?php echo '<title>アカウント設定</title>' ?>
		<?php endif; ?>
	</head>
	<body>
		<div>
			<!-- 管理者メニュー「新規ユーザ追加」　完了 -->
			<div>
				<?php if (isset($posted['create_new_user']) && $posted['create_new_user'] === "create_new_user") : ?>
					<form method="POST">
						<section>
							<?php if($login_conn->userCreate($posted)) : ?>
								<p>ユーザ作成が完了しました。</p><br>
								<button type="submit" name="create_new_user" value="back_on_created_user" formaction="console.php">戻る</button>
							<?php else : ?>
								<p>ユーザ作成が完了しませんでした。</p><br>
								<button type="submit" name="create_new_user" value="back_on_failed_created_user" formaction="console.php">戻る</button>
							<?php endif; ?>
						</section>
					</form>
				<?php endif; ?>
			</div>
			<!-- 管理者メニュー「アカウント削除」　完了 -->
			<div>
				<?php if (isset($posted['delete_user']) && $posted['delete_user'] === "complete") : ?>
					<form method="POST">
						<section>
							<?php if($login_conn->userDeleteAccount($posted['user_name'])) : ?>
								<p>削除が完了しました。</p>
								<button type="submit" name="delete_user" value="back_on_delete_user" formaction="console.php">戻る</button>
							<?php else : ?>
								<p>削除が完了しませんでした。</p>
								<button type="submit" name="delete_user" value="back_on_failed_delete_user" formaction="console.php">戻る</button>
							<?php endif; ?>
						</section>
					</form>
				<?php endif; ?>
			</div>
			<!-- 共通メニュー「パスワード変更」  完了 -->
			<div>
				<?php if (isset($posted['update']) && $posted['update'] === "confirm_update_password") : ?>
					<form method="POST">
						<section>
							<?php if(!empty($login_conn->userUpdatePassword($posted))) : ?>
								<p>登録が完了しました。</p><br>
								<button type="submit" name="update" value="return_after_success" formaction="console.php">戻る</button>
							<?php else : ?>
								<p>登録が完了しませんでした。</p><br>
								<button type="submit" name="update" value="return_after_failure" formaction="console.php">戻る</button>
							<?php endif; ?>
						</section>
					</form>
				<?php endif; ?>
			</div>

		</div>
	</body>
</html>