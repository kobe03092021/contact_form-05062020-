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

// バリデーションをインスタンス化（管理画面用）
$validate = new UserValidate($posted);
// エラーの戻り値を変数格納（新規ユーザ作成）
$create_new_user_errors = $validate->createNewUserErrors($posted);
// エラーの戻り値を変数格納（パスワード更新）
$update_password_errors = $validate->updatePasswordError($posted);

// DB共通プロパティを展開
$dbh = "";
// DBクラスをインスタンス化（管理画面用）
$login_conn = new UserDBConnect($dbh);

// ユーザ名の重複チェック　(エラー出力用の配列はメソッド内)
if(isset($posted['create_new_user']) && $posted['create_new_user'] === "check") {
	!empty($login_conn->userDuplication($posted)) ? $create_new_user_errors['duplication']['user_name'] = "入力されたユーザ名はすでに登録されています"."</br>" : null;
}

?>

<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="../css/common.css">

	</head>
	<body>
		<div>
			<!-- 管理者メニュー「新規ユーザ追加」　値チェック -->
			<div>
				<?php if (isset($posted['create_new_user']) && $posted['create_new_user'] === "check") : ?>
					<form method="POST">
						<section>
							<?php foreach($posted as $key => $val) : ?>
								<input type="hidden" name="<?=$key?>" value="<?=$val?>">
							<?php endforeach; ?>
							<div>
								<label>■ ユーザ名</label><br>
								<?= $validate->escape('user_name'); ?>
								<div class="caution">
									<?= isset($create_new_user_errors['empty']['user_name']) ? $create_new_user_errors['empty']['user_name'] : null ?>
									<?= isset($create_new_user_errors['pregmatch']['user_name']) ? $create_new_user_errors['pregmatch']['user_name'] : null ?>
									<?= isset($create_new_user_errors['duplication']['user_name']) ? $create_new_user_errors['duplication']['user_name'] : null ?>
								</div>
							</div>
							<div>
								<label>■ 新規パスワード</label><br>
								<?= $validate->escape('user_password'); ?>
								<div class="caution">
									<?= isset($create_new_user_errors['empty']['user_password']) ? $create_new_user_errors['empty']['user_password'] : null ?>
									<?= isset($create_new_user_errors['pregmatch']['user_password']) ? $create_new_user_errors['pregmatch']['user_password'] : null ?>
								</div>
							</div>
							<div>
								<label>■ 確認用パスワード</label><br>
								<?= $validate->escape('confirm_user_password'); ?>
								<div class="caution">
									<?= isset($create_new_user_errors['empty']['confirm_user_password']) ? $create_new_user_errors['empty']['confirm_user_password'] : null ?>
									<?= isset($create_new_user_errors['pregmatch']['confirm_user_password']) ? $create_new_user_errors['pregmatch']['confirm_user_password'] : null ?>
									<?= isset($create_new_user_errors['equality']['confirm_user_password']) ? $create_new_user_errors['equality']['confirm_user_password'] : null ?>
								</div>
							</div>
						</section>
						<section>
							<button type="submit" name="create_new_user" value="back" formaction="console.php">戻る</button>
							<!--  入力値が基準を満たしていれば、パスワードを更新  -->
							<?php if(isset($posted["create_new_user"]) && empty(array_filter($create_new_user_errors))) : ?>
								<button type="submit" name="create_new_user" value="create_new_user" formaction="complete.php">作成する</button>
							<?php endif; ?>
						</section>
					</form>
				<?php endif; ?>
			</div>
			<!-- 管理者メニュー「アカウント削除」　値チェック -->
			<div>
				<?php if (isset($posted['delete_user']) && $posted['delete_user'] === "confirm") : ?>
					<form action="" name="" method="POST">
						<section>
							<div>
								<?php
										$display = $login_conn->displayUser($posted['user_name']);
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
								<button type="submit" name="delete_user" value="back" formaction="console.php">キャンセル</button>
								<button type="submit" name="delete_user" value="complete" formaction="complete.php">削除する</button>
							</div>
						</section>
					</form>
				<?php endif; ?>
			</div>
			<!--  共通メニュー「パスワード変更」  値チェック -->
			<div>
				<?php if(isset($posted['update']) && $posted['update'] === "check") : ?>
					<?php if (isset($posted['update']) && empty(array_filter($update_password_errors))) : ?>
						<?php echo "このパスワードでよろしいですか？"."<br>"; ?>
					<?php endif;?>
					<form method="POST">
						<section>
							<?php foreach($posted as $key => $val) : ?>
								<input type="hidden" name="<?=$key?>" value="<?=$val?>">
							<?php endforeach; ?>
							<div>
								<label>■ 新規パスワード<span class="require">必須</span></label><br>
								<?= $validate->escape('user_password'); ?>
								<div class="caution">
									<?= isset($update_password_errors['empty']['user_password']) ? $update_password_errors['empty']['user_password'] : null ?>
									<?= isset($update_password_errors['pregmatch']['user_password']) ? $update_password_errors['pregmatch']['user_password'] : null ?>
								</div>
							</div>
							<div>
								<label>■ 確認用パスワード<span class="require">必須</span></label><br>
								<?= $validate->escape('confirm_user_password'); ?>
								<div class="caution">
									<?= isset($update_password_errors['empty']['confirm_user_password']) ? $update_password_errors['empty']['confirm_user_password'] : null ?>
									<?= isset($update_password_errors['pregmatch']['confirm_user_password']) ? $update_password_errors['pregmatch']['confirm_user_password'] : null ?>
									<?= isset($update_password_errors['equality']['confirm_user_password']) ? $update_password_errors['equality']['confirm_user_password'] : null ?>
								</div>
							</div>
							<div>
								<button type="submit" name="update" value="back" formaction="console.php">戻る</button>
								<?php if (isset($posted['update']) && empty(array_filter($update_password_errors))) : ?>
									<button type="submit" name="update" value="confirm_update_password" formaction="complete.php">登録</button>
								<?php endif;?>
							</div>
						</section>
					</form>
				<?php endif; ?>
			</div>

		</div>
	</body>
</html>