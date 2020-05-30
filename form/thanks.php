<?php

// POST経由でなければ、リダイレクト（ディレクトリ内最初のページへ）
if ( $_SERVER['REQUEST_METHOD'] !== 'POST') {
	header("location:input.php");
	exit;
	}

// DBクラス（問い合わせフォーム用）
require_once '../app/FormDBClass.php';
// メール処理クラス
require_once '../app/SendMailClass.php';

// POSTされた値の格納
$posted = $_POST;

// DB共通プロパティの展開
$dbh = "";
// DBクラスのインスタンス化（問い合わせフォーム用）
$form_conn = new FormDBConnect($dbh);
// テーブルにPOSTされた値を書き込み
$form_conn->formInsert($posted);

// メール処理クラスのインスタンス化
$mail = new SendMailClass($posted);

?>

<!-- メール送信処理の実行が完了すれば、本文を表示 -->
<?php if($mail->send_to_user() === true && $mail->send_to_admin() === true) : ?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../css/common.css">
    <title>お問い合わせ</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
  </head>
  <body>
    <!--パンくず開始-->
    <div class="">
      <ul class="">
        <li><a href="input.php">入力画面</a></li>
        <li>お問い合わせ</li>
      </ul>
    </div>
    <!--パンくず終了-->
    <main id="">
      <section>
        <div class="">
          <p class ="">
          お問い合わせありがとうございました。<br>
          今後ともテスト用お問い合わせフォームをよろしくお願いいたします。
          </p>
        </div>
      </section>
    </main>
  </body>
</html>

<?php else : ?>
  <?php echo
  "送信が正常に完了しませんでした。<br>
  システム管理者にお問い合わせください。"
  ?>
<?php endif ; ?>