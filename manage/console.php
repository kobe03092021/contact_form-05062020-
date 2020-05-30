<?php

// セッションが開始でなければ、開始
!isset($_SESSION["SSID"]) ? session_start() : "" ;
// クッキーが開始でなければ、開始
!isset($_COOKIE["SSID"]) ? setcookie("SSID", "", time() + (24*60*60)) : session_regenerate_id(uniqid($_COOKIE["SSID"], true));

// POST経由でなければ、リダイレクト（ディレクトリ内最初のページへ）
if ( $_SERVER['REQUEST_METHOD'] !== 'POST') {
	header("location:login.php");
	exit;
	}

// 定数クラス
require_once '../app/ConstantClass.php';
// バリデーション用クラス（管理画面用）
require_once '../app/UserValidateClass.php';
// データベース用クラス（管理画面用）
require_once '../app/UserDBClass.php';
// 管理者アカウント設定
require_once '../settings/admin_account.php';

// POSTされた値の格納
$posted = [];
$posted = $_POST;
$session = $_SESSION;

// バリデーション用クラスのインスタンス化（管理画面用）
$validate = new UserValidate($posted);
// エラーの戻り値を変数格納（新規ユーザ作成）
$create_new_user_errors = $validate->createNewUserErrors($posted);
// エラーの戻り値を変数格納（パスワード更新時）
$update_password_errors = $validate->updatePasswordError($posted);

// DB共通のプロパティ展開
$dbh = "";
// DBクラスのインスタンス化（管理画面用）
$login_conn = new UserDBConnect($dbh);
// DBクラスのインスタンス化（問い合わせフォーム用）
$form_conn = new FormDBConnect($dbh);

?>

<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="../css/common.css">
		<!--管理者用ID/PWが入力されていれば、「管理者」を表示 -->
		<?php if($_SESSION['SSID']['user_name'] === $admin['user_name'] && $_SESSION['SSID']['user_password'] === $admin['user_password'] ) : ?>
			<?php echo '<title>管理画面</title>'; ?>
		<?php else : ?>
		<!--管理者用ID/PW以外が入力されていれば、「アカウント設定」を表示 -->
			<?php echo '<title>アカウント設定</title>' ?>
		<?php endif; ?>
	</head>
	<body>
			<!--管理者用ID/PWが入力されていれば、「管理者」を表示 -->
			<?php if($_SESSION['SSID']['user_name'] === $admin['user_name'] && $_SESSION['SSID']['user_password'] === $admin['user_password'] ) : ?>
				<?php echo '<h3>管理画面</h3>'; ?>
			<?php else : ?>
			<!--管理者用ID/PW以外が入力されていれば、「アカウント設定」を表示 -->
				<?php echo '<h3>アカウント設定</h3>' ?>
			<?php endif; ?>
			<!--ログイン表示-->
			<section>
				<?= $session["SSID"]['user_name']."さん　こんにちわ。"; ?>
			</section>

			<form action="" method="POST">
				<!-- 管理用メニュー　＊但し、指定の管理者用ID/PWに一致した場合のみ表示 -->
				<section>
					<div>
						<?php if($session['SSID']['user_name'] === $admin['user_name'] && $session['SSID']['user_password'] === $admin['user_password'] ) : ?>
							管理者メニュー<br>
							<?php echo '<button type="submit" name="choice" value="select_all_user">ユーザ一覧</button>'; ?>
							<?php echo '<button type="submit" name="choice" value="create_new_user">新規ユーザ追加</button>' ?>
							<?php echo '<button type="submit" name="choice" value="delete_user">アカウント削除</button>' ?>
						<?php endif; ?>
					</div>
				</section>
				<!-- 共通メニュー -->
				<section>
					<div>
						アカウント設定<br>
						<button type="submit" name="choice" value="select">アカウント情報</button>
						<button type="submit" name="choice" value="update">パスワード変更</button>
						<button type="submit" name="choice" value="select_all_form">問い合わせ一覧</button>
						<button type="submit" name="choice" value="search_form">問い合わせ検索</button>
					</div>
				</section>
				<!-- ログアウトを表示 -->
				<section>
					<div>
						<button type="submit" name="choice" value="logout" formaction="">ログアウト</button>
					</div>
				</section>
			</form>

			<div>
				<!-- 管理者メニュー「ユーザ一覧」  開始 -->
				<?php if (isset($posted['choice']) && $posted['choice'] === "select_all_user") : ?>
					<form method="POST">
						<h5>ユーザ一覧</h5>
						<section>
							<?php if($login_conn->allAccount($posted)) : ?>
								<p>ユーザ一覧を表示しました。</p><br>
								<button type="submit" name="update" value="return_after_success" formaction="console.php">戻る</button>
							<?php else : ?>
								<p>ユーザ一覧が取得できませんでした。</p><br>
								<button type="submit" name="update" value="return_after_failure" formaction="console.php">戻る</button>
							<?php endif; ?>
						</section>
					</form>
				<?php endif; ?>

				<!-- 管理者メニュー「新規ユーザ追加」　開始 -->
				<?php if (isset($posted['choice']) && $posted['choice'] === "create_new_user") : ?>
					<form method="POST">
						<h5>新規ユーザ追加</h5>
						<section>
							<div>
								<label>■ ユーザ名<span class="require">必須</span></label><br>
								<input type="text" name="user_name" value="<?= $validate->escape('user_name'); ?>">
								<div class="caution">
									<?= isset($create_new_user_errors['empty']['user_name']) ? $create_new_user_errors['empty']['user_name'] : null ?>
									<?= isset($create_new_user_errors['pregmatch']['user_name']) ? $create_new_user_errors['pregmatch']['user_name'] : null ?>
									<?= isset($create_new_user_errors['duplicatoin']['user_name']) ? $create_new_user_errors['duplicatoin']['user_name'] : null ?>
								</div>
							</div>

							<div>
								<label>■ 新規パスワード<span class="require">必須</span></label><br>
								<input type="text" name="user_password" value="<?= $validate->escape('user_password'); ?>">
								<div class="caution">
									<?= isset($create_new_user_errors['empty']['user_password']) ? $create_new_user_errors['empty']['user_password'] : null ?>
									<?= isset($create_new_user_errors['pregmatch']['user_password']) ? $create_new_user_errors['pregmatch']['user_password'] : null ?>
								</div>
							</div>

							<div>
								<label>■ 確認用パスワード<span class="require">必須</span></label><br>
								<input type="passowrd" name="confirm_user_password" value="<?= $validate->escape('confirm_user_password'); ?>">
								<div class="caution">
									<?= isset($create_new_user_errors['empty']['confirm_user_password']) ? $create_new_user_errors['empty']['confirm_user_password'] : null ?>
									<?= isset($create_new_user_errors['pregmatch']['confirm_user_password']) ? $create_new_user_errors['pregmatch']['confirm_user_password'] : null ?>
									<?= isset($create_new_user_errors['equality']['confirm_user_password']) ? $create_new_user_errors['equality']['confirm_user_password'] : null ?>
								</div>
							</div>
						</section>
						<section>
							<button type="submit" name="create_new_user" value="back" formaction="">キャンセル</button>
							<button type="submit" name="create_new_user" value="check" formaction="check.php">確認</button>
						</section>
					</form>
				<?php endif; ?>

				<!--  管理者メニュー「アカウント削除」　開始 -->
				<?php if (isset($posted['choice']) && $posted['choice'] === "delete_user") : ?>
					<form action="" name="" method="POST">
						<h5>アカウント削除</h5>
						<section>
							<div>
								<label>■ ユーザ名</label><br>
								<input type="text" name="user_name" value="<?= $validate->escape('user_name'); ?>">
								<div class="caution">
									<?= isset($create_new_user_errors['empty']['user_name']) ? $create_new_user_errors['empty']['user_name'] : null ?>
									<?= isset($create_new_user_errors['pregmatch']['user_name']) ? $create_new_user_errors['pregmatch']['user_name'] : null ?>
								</div>
							</div>
						</section>
						<section>
							<button type="submit" name="delete_user" value="cancel" formaction="">キャンセル</button>
							<button type="submit" name="delete_user" value="confirm" formaction="check.php">確認</button>
							<!--  入力された値が作成基準を満たしていれば、パスワードを更新 -->
						</section>
					</form>
				<?php endif; ?>
			</div>

			<div>
				<!-- 共通メニュー「アカウント情報」　開始 -->
				<div>
					<?php if (isset($posted['choice']) && $posted['choice'] === "select") : ?>
					<form method="POST">
						<h5>アカウント情報</h5>
						<section>
							<dl>
							<dt>■ ID：</dt><dd><?= htmlspecialchars($session['SSID']['id']); ?></dd>
							<dt>■ ユーザ名：</dt><dd><?= htmlspecialchars($session['SSID']['user_name']); ?></dd>
							<dt>■ 現在のパスワード：</dt><dd><?= htmlspecialchars($session['SSID']['user_password']); ?></dd>
							</dl>
						</section>

						<button type="submit" name="select" value="back" formaction="">戻る</button>
					</form>
					<?php endif; ?>
				</div>	

				<!--  共通メニュー「パスワード変更」  開始  -->
				<div>
					<?php if (isset($posted['choice']) && $posted['choice'] === "update") : ?>
						<form method="POST">
							<h5>パスワード変更</h5>
							<section>
								<div>
									<label>■ 新規パスワード<span class="require">必須</span></label><br>
									<input type="text" name="user_password" value="<?= $validate->escape('user_password'); ?>">
									<div class="caution">
										<?= isset($update_password_errors['empty']['user_password']) ? $update_password_errors['empty']['user_password'] : null ?>
										<?= isset($update_password_errors['pregmatch']['user_password']) ? $update_password_errors['pregmatch']['user_password'] : null ?>
									</div>
								</div>
								<div>
									<label>■ 確認用パスワード<span class="require">必須</span></label><br>
									<input type="password" name="confirm_user_password" value="<?= $validate->escape('confirm_user_password'); ?>">
									<div class="caution">
										<?= isset($update_password_errors['empty']['confirm_user_password']) ? $update_password_errors['empty']['confirm_user_password'] : null ?>
										<?= isset($update_password_errors['pregmatch']['confirm_user_password']) ? $update_password_errors['pregmatch']['confirm_user_password'] : null ?>
										<?= isset($update_password_errors['equality']['confirm_user_password']) ? $update_password_errors['equality']['confirm_user_password'] : null ?>
									</div>
								</div>
								<div>
									<button type="submit" name="update" value="back" formaction="">キャンセル</button>
									<button type="submit" name="update" value="check" formaction="check.php">確認</button>
								</div>
							</section>
						</form>
					<?php endif; ?>
				</div>

				<!-- 共通メニュー「お問い合わせ一覧」　開始 -->
				<div>
					<?php if (isset($posted['choice']) && $posted['choice'] === "select_all_form") : ?>
						<form method="POST">
							<section>
								<h5>お問い合わせ一覧</h5>
								<?php if($form_conn->allForm()) : ?>
									<p>お問い合わせ一覧を表示しました。</p><br>
									<button type="submit" name="select_all_form" value="return_after_success" formaction="console.php">戻る</button>
								<?php else : ?>
									<p>お問い合わせ一覧を表示できませんでした。</p><br>
									<button type="submit" name="select_all_form" value="return_after_failure" formaction="console.php">戻る</button>
								<?php endif; ?>
							</section>
						</form>
					<?php endif; ?>
				</div>

				<!--  共通メニュー「問い合わせ検索」　開始 -->
				<div>
					<?php if (isset($posted['choice']) && $posted['choice'] === "search_form") : ?>
						<form method="POST">
							<section>
								<h5>項目検索</h5>
								<p>問合わせ者のID、または氏名（カタカナ）、電話番号、メールアドレスのいずれかの項目を入力してください。</p>
								<div>
									<label>■ ID<span class="optional">任意</span></label><br>
									<input type="text" name="id" value="<?= $validate->escape('id'); ?>">
								</div>
								<div>
									<label>■ 氏名<span class="require">※カタカナ</span><span class="optional">任意</span></label><br>
									<input type="text" name="last_kana"  value="<?= $validate->escape('last_kana'); ?>">
									<input type="text" name="first_kana" value="<?= $validate->escape('first_kana'); ?>">
								</div>
								<div>
									<label>■ 登録済み電話番号<span class="optional">任意</span></label><br>
									<input type="text" name="phone" value="<?= $validate->escape('phone'); ?>">
								</div>
								<div>
									<label>■ 登録済みメールアドレス<span class="optional">任意</span></label><br>
									<input type="text" name="mail" value="<?= $validate->escape('mail'); ?>">
								</div>
							</section>
							<section>
								<button type="submit" name="search_form" value="back" formaction="">キャンセル</button>
								<button type="submit" name="search_form" value="do" formaction="">確認</button>
							</section>
						</form>
					<?php endif; ?>
				</div>
				<div>
					<?php if (isset($posted['search_form']) && $posted['search_form'] === "do") : ?>
						<form method="POST">
							<section>
								<?php if(!empty($form_conn->searchForm($posted))) : ?>
									<p>検索が完了しました。</p><br>
									<button type="submit" name="search_form" value="return_after_success" formaction="console.php">戻る</button>
								<?php else : ?>
									<p>検索が完了しませんでした。</p><br>
									<button type="submit" name="search_form" value="return_after_failure" formaction="console.php">戻る</button>
								<?php endif; ?>
							</section>
						</form>
					<?php endif; ?>
				</div>

				<!-- 共通メニュー「ログアウト」 開始  -->
				<div>
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
					<!-- 共通メニュー「ログアウト」 終了  -->
				</div>

			</div>

	</body>
</html>