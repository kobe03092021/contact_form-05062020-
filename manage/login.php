<?php

// セッションが開始でなければ、開始
!isset($_SESSION) ? session_start() : "" ;
// クッキーが開始でなければ、開始
!isset($_COOKIE["SSID"]) ? setcookie("SSID", "", time() + (24*60*60)) : session_regenerate_id(uniqid('', true));

// 定数クラス
require_once '../app/ConstantClass.php';
// バリデーション用クラス（管理画面用）
require_once '../app/UserValidateClass.php';
// DB用クラス（管理画面用）
require_once '../app/UserDBClass.php';

// POSTされた値の格納
$posted = [];
$posted = $_POST;

// バリデーション用クラスのインスタンス化（管理画面用）
$validate = new UserValidate($posted);
// エラーの戻り値を変数格納（管理画面用）
$login_errors = $validate->loginErrors();

// DB共通プロパティを展開
$dbh = "";
// DBクラスをインスタンス化（管理画面用）
$login_conn = new UserDBConnect($dbh);

// ログインボタンをクリック後、DB上のデータ確認用のメソッドを呼び出し、比較
$table = !empty($posted['login']) ? $login_conn->login($posted) : "" ;

// ログイン項目の入力値をDBと照合
if (isset($posted['login'])) {
	if (empty($table['user_name']) || empty($table['user_password'])) {
		$login_errors['db_match'] = "<p>DB上のユーザ名またはパスワードと一致しません</p>";
		} else {
			echo "<p>DB上のユーザ名と一致しました</p>";
			$_SESSION["SSID"]['id']            = $table['id'];
			$_SESSION["SSID"]['user_name']     = $table['user_name'];
			$_SESSION["SSID"]['user_password'] = $table['user_password'];
		}
	}

?>

<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="../css/common.css">
		<script src="../js/jquery-3.5.0.min.js" type="text/javascript"></script>
		<title>管理画面　ログイン</title>
	</head>
	<body>
		<h3>管理画面　ログイン</h3>
			<!--登録済みユーザ用フォーム-->
			<div>
				<form method="POST">
					<section>
						<div class="caution">
							<?= isset($login_errors['db_match']) ? $login_errors['db_match'] : "" ?>
						</div>
					</section>

					<section>
						<div>
							<label>■ ログイン情報</label><br>
								<input type="text" name="user_name" value="<?= $validate->escape('user_name'); ?>">
								<div class="caution">
									<?= isset($login_errors['empty']['user_name']) ? $login_errors['empty']['user_name'] : "" ?>
								</div>

								<input type="password" name="user_password" value="<?= $validate->escape('user_password'); ?>">
								<div class="caution">
									<?= isset($login_errors['empty']['user_password']) ? $login_errors['empty']['user_password'] : "" ?>
								</div>
							</div>
						<div>
							<?php echo '<button type="submit" name="login" id="login" value="check" formaction="">登録確認</button>'; ?>
							<!-- エラーがなければ、ログイン表示 -->
							<?php if(isset($posted['login']) && empty(array_filter($login_errors))) : ?>
								<?php echo '<button type="submit" name="login" id="login" value="found" formaction="console.php">ログイン</button>' ?>
							<?php endif; ?>
						</div>
					</section>
				</form>

				<!--新規ユーザ登録用フォーム-->
				<section>
					<div>
						<form method="POST">
							<button type="submit" name="login" value="not_found" formaction="../registration/input.php">新規登録</button>
						</form>
					</div>
				</section>
			</div>

	</body>
</html>

<!-- <script>
// 送信ボタン無効化
$(function() {
	//ログインボタンに初期値を設定
	$('#login').prop("disabled", true);
// requiredの入力欄に必須クラスを付加
	$('input:required').each(function () {
		$(this).prev("label").addClass("required");
	});

//　inputフォームが空の場合にログインボタンを不許可
	$('input:required').change( function () {
		$('input:required').each( function(e) {
			if($('input:required').eq(e).val() === "") {
				$('#login').prop("disabled",false);
				}
		});
	});
});
</script> -->