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
require_once(__DIR__."/app/RedirectClass.php");
// リダイレクト処理
$page = new Redirect();
// ページアクセスの直打ち防止
$page->redirectToLogin();

// POSTされた値の格納
$posted = [];
$posted = $_POST;
$session = $_SESSION;

// バリデーション用クラスのインスタンス化
$validate = new LoginValidate($posted);
// エラー判定時の戻り値を変数格納（新規ユーザ作成）
$create_new_user_errors = $validate->createNewUserErrors($posted);

// エラー判定時の戻り値を変数格納（パスワード更新）
$update_password_errors = $validate->updatePasswordError($posted);
// var_dump($update_password_errors);
// DB用クラスのインスタンス化、プロパティ展開
$dbh = "";
$conn = new LoginDBConnect($dbh);
// ログインボタンが押されたら、DB上のデータ確認用のメソッドを呼び出し
$table = !empty($posted['login']) ? $conn->login($posted) : "";
// ユーザ名の重複チェック　(エラー出力用の配列はcreateNewUserErrors($posted)のメソッド内)
if(isset($posted['is_create_new_user']) && $posted['is_create_new_user'] === "check_create_new_user") {
	!empty($conn->userDuplication($posted)) ? $create_new_user_errors['duplication']['user_name'] = "入力されたユーザ名はすでに登録されています"."</br>" : null;
}

?>

<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="css/caution.css">

	</head>
	<body>

				<!--パスワード作成基準を満たしているか、確認 -->
				<?php if(isset($posted['is_update']) && $posted['is_update'] === "check_update_password") : ?>
					<?php if (isset($posted['is_update']) && empty(array_filter($update_password_errors))) : ?>
						<?php echo "このパスワードでよろしいですか？"."<br>"; ?>
					<?php endif;?>

					<form method="POST">
						<section>
							<?php foreach($posted as $key => $val) : ?>
								<input type="hidden" name="<?=$key?>" value="<?=$val?>">
							<?php endforeach; ?>

							<div>
								<label>■ 新規パスワード</label><br>
								<?= $validate->escape('user_password'); ?>
								<div class="caution">
									<?= isset($update_password_errors['is_empty']['user_password']) ? $update_password_errors['is_empty']['user_password'] : null ?>
									<?= isset($update_password_errors['pregmatch']['user_password']) ? $update_password_errors['pregmatch']['user_password'] : null ?>
								</div>
							</div>
							<div>
								<label>■ 確認用パスワード</label><br>
								<?= $validate->escape('confirm_user_password'); ?>
								<div class="caution">
									<?= isset($update_password_errors['is_empty']['confirm_user_password']) ? $update_password_errors['is_empty']['confirm_user_password'] : null ?>
									<?= isset($update_password_errors['pregmatch']['confirm_user_password']) ? $update_password_errors['pregmatch']['confirm_user_password'] : null ?>
									<?= isset($update_password_errors['equality']['confirm_user_password']) ? $update_password_errors['equality']['confirm_user_password'] : null ?>
								</div>
							</div>
							<div>
								<button type="submit" name="is_update" value="back" formaction="console.php">戻る</button>
								<?php if (isset($posted['is_update']) && empty(array_filter($update_password_errors))) : ?>
									<button type="submit" name="is_update" value="confirm_update_password" formaction="console_complete.php">登録</button>
								<?php endif;?>
							</div>
						</section>
					</form>
				<?php endif; ?>


<!-- 「新規ユーザ追加」が選択された場合 -->
<?php if (isset($posted['is_create_new_user']) && $posted['is_create_new_user'] === "check_create_new_user") : ?>
				<form method="POST">
					<section>
						<?php foreach($posted as $key => $val) : ?>
							<input type="hidden" name="<?=$key?>" value="<?=$val?>">
						<?php endforeach; ?>
						<div>
							<label>■ ユーザ名</label><br>
							<?= $validate->escape('user_name'); ?>
							<div class="caution">
								<?= isset($create_new_user_errors['is_empty']['user_name']) ? $create_new_user_errors['is_empty']['user_name'] : null ?>
								<?= isset($create_new_user_errors['pregmatch']['user_name']) ? $create_new_user_errors['pregmatch']['user_name'] : null ?>
								<?= isset($create_new_user_errors['duplication']['user_name']) ? $create_new_user_errors['duplication']['user_name'] : null ?>
							</div>
						</div>

						<div>
							<label>■ 新規パスワード</label><br>
							<?= $validate->escape('user_password'); ?>
							<div class="caution">
								<?= isset($create_new_user_errors['is_empty']['user_password']) ? $create_new_user_errors['is_empty']['user_password'] : null ?>
								<?= isset($create_new_user_errors['pregmatch']['user_password']) ? $create_new_user_errors['pregmatch']['user_password'] : null ?>
							</div>
						</div>

						<div>
							<label>■ 確認用パスワード</label><br>
							<?= $validate->escape('confirm_user_password'); ?>
							<div class="caution">
								<?= isset($create_new_user_errors['is_empty']['confirm_user_password']) ? $create_new_user_errors['is_empty']['confirm_user_password'] : null ?>
								<?= isset($create_new_user_errors['pregmatch']['confirm_user_password']) ? $create_new_user_errors['pregmatch']['confirm_user_password'] : null ?>
								<?= isset($create_new_user_errors['equality']['confirm_user_password']) ? $create_new_user_errors['equality']['confirm_user_password'] : null ?>
							</div>
						</div>
					</section>
					<section>
						<button type="submit" name="is_create_new_user" value="back" formaction="console.php">戻る</button>
						<!--入力された値が作成基準を満たしていれば、パスワードを更新 -->
						<?php if(isset($posted["is_create_new_user"]) && empty(array_filter($create_new_user_errors))) : ?>
							<button type="submit" name="is_create_new_user" value="create_new_user" formaction="console_complete.php">作成する</button>
						<?php endif; ?>
					</section>
				</form>
			<?php endif; ?>

<!-- 「アカウント削除」が選択された場合 -->
<?php if (isset($posted['is_delete_user']) && $posted['is_delete_user'] === "confirm") : ?>
				<form action="" name="" method="POST">
					<section>
						<div>
							<?php
									$display = $conn->displayUser($posted['user_name']);
									// ID名をDBと照合
									if (empty($display['user_name'])) {
										$delete_user_errors['db_match'] = "DB上のユーザ名またはパスワードと一致しません";
										} else {
											echo '<p>'."DB上のユーザ名と一致しました。".'</p>'
												. '<p>'."このユーザを削除しますか？".'</p>'
												.'<table>'
												.'<tr>'
												.'<th>ID</th>'
												.'<th>ユーザ名</th>'
												.'<th>パスワード</th>'
												. '</tr>'
												. '<tr>'
												. '<td>', htmlspecialchars($display['id'])
												. '<td>', htmlspecialchars($display['user_name'])
												. '<td>', htmlspecialchars($display['user_password'])
												.'</tr>'. "</table>". "</br>";

												// ユーザ名があれば、POSTデータとして格納
												foreach($display as $key => $val) :
													echo "<input type='hidden' name='{$key}' value='{$val}'>";
												endforeach;
										}
							?>
						</div>
					</section>
					<section>
						<div>
							<button type="submit" name="is_delete_user" value="back" formaction="console.php">キャンセル</button>
							<button type="submit" name="is_delete_user" value="complete" formaction="console_complete.php">削除する</button>
						</div>
					</section>
				</form>
			<?php endif; ?>


	</body>
</html>