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
var_dump($update_password_errors);
// DB用クラスのインスタンス化、プロパティ展開
$dbh = "";
$conn = new LoginDBConnect($dbh);
// ログインボタンが押されたら、DB上のデータ確認用のメソッドを呼び出し
$table = !empty($posted['login']) ? $conn->login($posted) : "";
// 管理者用のアカウント設定
$admin = [
	'id'       => "",
	'user_name' => "admin",
	'user_password' => "password"
];

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

<!-- 「ログアウト」が選択された場合 開始  -->
			<?php if (isset($posted['choice']) && $posted['choice'] === "logout") : ?>
				<?php
				if(session_destroy() === true) {
					$dbh = null;
					setcookie("SSID", "", time() - (24*60*60));
					echo "ログアウトしました。";
					// header("location: login.php");
					// exit;
					}else {
						echo "ログアウトが正常にできませんでした。管理者にお問い合わせください";
					}
				?>
				<?php '<button type="submit" name="is_update" value="check_update_password" formaction="console_check.php">確認</button>'; ?>
			<?php endif; ?>	
<!-- 「ログアウト」が選択された場合 終了  -->
	</body>
</html>