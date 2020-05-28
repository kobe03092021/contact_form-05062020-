<?php
// セッションが開始でなければ、開始する
!isset($_SESSION["SSID"]) ? session_start() : "" ;
// クッキーが開始でなければ、開始する
!isset($_COOKIE["SSID"]) ? setcookie("SSID", "", time() + (24*60*60)) : session_regenerate_id(uniqid($_COOKIE["SSID"], true));
var_dump($_SESSION);
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
// エラー判定時の戻り値を変数格納（新規ユーザ作成）
$create_new_user_errors = $validate->createNewUserErrors($posted);
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
			<!--管理者用ID/PWが入力されていれば、「管理者」を明示 -->
			<?php if($_SESSION['SSID']['user_name'] === $admin['user_name'] && $_SESSION['SSID']['user_password'] === $admin['user_password'] ) : ?>
				<?php echo '<h3>管理画面</h3>'; ?>
			<?php else : ?>
			<!--管理者用ID/PW以外が入力されていれば、「アカウント設定」を明示 -->
				<?php echo '<h3>アカウント設定</h3>' ?>
			<?php endif; ?>
			<!--ログイン表示-->
			<section>
				<?= $session["SSID"]['user_name']."さん　こんにちわ。"; ?>
			</section>

			<!-- 登録済みユーザ用フォーム -->
			<form action="" method="POST">
				<section>
					<div>
						<!-- 管理者用ID/PWが入力されていれば、管理用メニューを表示 -->
						<?php if($session['SSID']['user_name'] === "admin" && $session['SSID']['user_password'] === "password" ) : ?>
							管理者メニュー<br>
							<?php echo '<button type="submit" name="choice" value="is_select_all">ユーザ検索</button>'; ?>
							<?php echo '<button type="submit" name="choice" value="is_create_new_user">新規ユーザ追加</button>' ?>
							<?php echo '<button type="submit" name="choice" value="is_delete_user">アカウント削除</button>' ?>
						<?php endif; ?>
					</div>
					<div>
						<!-- 通常メニューを表示 -->
						アカウント設定<br>
						<button type="submit" name="choice" value="is_select">現在のアカウント</button>
						<button type="submit" name="choice" value="is_update">パスワード変更</button>
					</div>
					<div>
						<!-- ログアウトを表示 -->
						<button type="submit" name="choice" value="logout" formaction="">ログアウト</button>
					</div>
				</section>
			</form>


<!-- 「現在のアカウント」が選択された場合 -->
			<?php if (isset($posted['choice']) && $posted['choice'] === "is_select") : ?>
			<form method="POST">
				<section>
					<dl>
					<dt>■ ID：</dt><dd><?= htmlspecialchars($session['SSID']['id']); ?></dd>
					<dt>■ ユーザ名：</dt><dd><?= htmlspecialchars($session['SSID']['user_name']); ?></dd>
					<dt>■ 現在のパスワード：</dt><dd><?= htmlspecialchars($session['SSID']['user_password']); ?></dd>
					</dl>
				</section>

				<button type="submit" name="is_select" value="is_select_on_back" formaction="">戻る</button>
			</form>
			<?php endif; ?>	

			<!-- 「ユーザ検索」を実行 -->
			<?php if (isset($posted['choice']) && $posted['choice'] === "is_select_all") : ?>
				<form method="POST">
					<section>
						<?php if($conn->allAccount($posted)) : ?>
							ユーザ検索が完了しました。<br>
							<button type="submit" name="is_update" value="back_on_select_all" formaction="console.php">戻る</button>
						<?php else : ?>
							ユーザ検索が完了しませんでした。<br>
							<button type="submit" name="is_update" value="back_on_failed_select_all" formaction="console.php">戻る</button>
						<?php endif; ?>
					</section>
				</form>
			<?php endif; ?>
<!-- 「新規ユーザ追加」が選択された場合 -->
			<?php if (isset($posted['choice']) && $posted['choice'] === "is_create_new_user") : ?>
				<form action="" name="" method="POST">
					<section>
						<div>
							<label>■ ユーザ名</label><br>
							<input type="text" name="user_name" value="<?= $validate->escape('user_name'); ?>">
							<div class="caution">
								<?= isset($create_new_user_errors['is_empty']['user_name']) ? $create_new_user_errors['is_empty']['user_name'] : null ?>
								<?= isset($create_new_user_errors['pregmatch']['user_name']) ? $create_new_user_errors['pregmatch']['user_name'] : null ?>
								<?= isset($create_new_user_errors['duplicatoin']['user_name']) ? $create_new_user_errors['duplicatoin']['user_name'] : null ?>
							</div>
						</div>

						<div>
							<label>■ 新規パスワード</label><br>
							<input type="text" name="user_password" value="<?= $validate->escape('user_password'); ?>">
							<div class="caution">
								<?= isset($create_new_user_errors['is_empty']['user_password']) ? $create_new_user_errors['is_empty']['user_password'] : null ?>
								<?= isset($create_new_user_errors['pregmatch']['user_password']) ? $create_new_user_errors['pregmatch']['user_password'] : null ?>
							</div>
						</div>

						<div>
							<label>■ 確認用パスワード</label><br>
							<input type="text" name="confirm_user_password" value="<?= $validate->escape('confirm_user_password'); ?>">
							<div class="caution">
								<?= isset($create_new_user_errors['is_empty']['confirm_user_password']) ? $create_new_user_errors['is_empty']['confirm_user_password'] : null ?>
								<?= isset($create_new_user_errors['pregmatch']['confirm_user_password']) ? $create_new_user_errors['pregmatch']['confirm_user_password'] : null ?>
								<?= isset($create_new_user_errors['equality']['confirm_user_password']) ? $create_new_user_errors['equality']['confirm_user_password'] : null ?>
							</div>
						</div>
					</section>
					<section>
						<button type="submit" name="is_create_new_user" value="back" formaction="">キャンセル</button>
						<button type="submit" name="is_create_new_user" value="check_create_new_user" formaction="console_check.php">確認</button>
					</section>
				</form>
			<?php endif; ?>

<!-- 「アカウント削除」が選択された場合 -->
			<?php if (isset($posted['choice']) && $posted['choice'] === "is_delete_user") : ?>
				<form action="" name="" method="POST">
					<section>
						<div>
							<label>■ ユーザ名</label><br>
							<input type="text" name="user_name" value="<?= $validate->escape('user_name'); ?>">
							<div class="caution">
								<?= isset($create_new_user_errors['is_empty']['user_name']) ? $create_new_user_errors['is_empty']['user_name'] : null ?>
								<?= isset($create_new_user_errors['pregmatch']['user_name']) ? $create_new_user_errors['pregmatch']['user_name'] : null ?>
							</div>
						</div>
					</section>
					<section>
						<button type="submit" name="is_delete_user" value="back" formaction="">キャンセル</button>
						<button type="submit" name="is_delete_user" value="confirm" formaction="console_check.php">確認</button>
						<!--入力された値が作成基準を満たしていれば、パスワードを更新 -->
					</section>
				</form>
			<?php endif; ?>

<!--  「パスワード変更」が選択された場合  開始  -->
			<?php if (isset($posted['choice']) && $posted['choice'] === "is_update") : ?>
				<!-- 入力フォーム -->
				<form method="POST">
					<section>
						<div>
							<label>■ 新規パスワード</label><br>
							<input type="text" name="user_password" value="<?= $validate->escape('user_password'); ?>">
							<div class="caution">
								<?= isset($update_password_errors['is_empty']['user_password']) ? $update_password_errors['is_empty']['user_password'] : null ?>
								<?= isset($update_password_errors['pregmatch']['user_password']) ? $update_password_errors['pregmatch']['user_password'] : null ?>
							</div>
						</div>
						<div>
							<label>■ 確認用パスワード</label><br>
							<input type="text" name="confirm_user_password" value="<?= $validate->escape('confirm_user_password'); ?>">
							<div class="caution">
								<?= isset($update_password_errors['is_empty']['confirm_user_password']) ? $update_password_errors['is_empty']['confirm_user_password'] : null ?>
								<?= isset($update_password_errors['pregmatch']['confirm_user_password']) ? $update_password_errors['pregmatch']['confirm_user_password'] : null ?>
								<?= isset($update_password_errors['equality']['confirm_user_password']) ? $update_password_errors['equality']['confirm_user_password'] : null ?>
							</div>
						</div>
						<div>
							<button type="submit" name="is_update" value="back" formaction="">キャンセル</button>
							<button type="submit" name="is_update" value="check_update_password" formaction="console_check.php">確認</button>
						</div>
					</section>
				</form>
			<?php endif; ?>

<!-- 「ログアウト」が選択された場合 開始  -->
			<?php if (isset($posted['choice']) && $posted['choice'] === "logout") : ?>
				<?php
					if(session_destroy() === true && setcookie("SSID", "", time() - (24*60*60))) {
							header("location: login.php");
							exit;
					} else {
						echo "ログアウトが正常にできませんでした。管理者にお問い合わせください";
					}
				?>
			<?php endif; ?>	
<!-- 「ログアウト」が選択された場合 終了  -->
	</body>
</html>