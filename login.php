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

// POSTされた値の格納
$posted = [];
$posted = $_POST;

// バリデーション用クラスのインスタンス化
$validate = new LoginValidate($posted);
// エラー判定時の戻り値を変数格納
$login_errors = $validate->loginErrors();
// DB用クラスのインスタンス化、プロパティ展開
$dbh = "";
$conn = new LoginDBConnect($dbh);
// ログインボタンが押されたら、DB上のデータ確認用のメソッドを呼び出し
$table = !empty($posted['login']) ? $conn->login($posted) : "";

// ID名をDBと照合
if (isset($posted['login'])) {
	if (empty($table['user_name']) || empty($table['user_password'])) {
		$login_errors['db_match'] = "DB上のユーザ名またはパスワードと一致しません";
		} else {
			echo "DB上のユーザ名と一致しました";
			$_SESSION["SSID"]['id']       = $table['id'];
			$_SESSION["SSID"]['user_name'] = $table['user_name'];
			$_SESSION["SSID"]['user_password'] = $table['user_password'];
	}
}

?>

<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="css/caution.css">
		<title>管理画面（ログイン画面）</title>
	</head>
	<body>
		<h3>管理画面（ログイン画面）</h3>
		<!--登録済みユーザ用フォーム-->
			<form action="" method="POST">
				<section>
					<div class="caution">
						<?= isset($login_errors['db_match']) ? $login_errors['db_match'] : "" ?>
					</div>
				</section>

				<section>
						<label>■ ログイン情報</label><br>

						<input type="text" name="user_name" value="<?= $validate->escape('user_name'); ?>">
						<div class="caution">
							<?= isset($login_errors['is_empty']['user_name']) ? $login_errors['is_empty']['user_name'] : "" ?>
						</div>

						<input type="text" name="user_password" value="<?= $validate->escape('user_password'); ?>">
						<div class="caution">
							<?= isset($login_errors['is_empty']['user_password']) ? $login_errors['is_empty']['user_password'] : "" ?>
						</div>

					<div>
						<!-- エラーがあれば、リロード -->
						<?php if(!empty(array_filter($login_errors))) : ?>
							<?php echo '<button type="submit" name="login" id="login" value="check" formaction="">登録確認</button>'; ?>
						<!-- エラーがなければ、ログイン表示 -->
						<?php elseif(isset($_POST['login']) && empty(array_filter($login_errors))) : ?>
							<?php echo '<button type="submit" name="login" id="login" value="is_found" formaction="console.php">ログイン</button>' ?>
						<?php endif; ?>
					</div>
				</section>
			</form>

			<!--新規ユーザ用フォーム-->
			<form action="" method="POST">
					<button type="submit" name="login" value="is_not_found" formaction="registration.php">新規登録</button>
			</form>

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