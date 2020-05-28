<?php
// ページへのアクセスの処理用クラス
require_once(__DIR__.'/app/RedirectClass.php');
// データベース用クラス
require_once(__DIR__.'/app/FormDBClass.php');
// メール用クラス
require_once(__DIR__.'/app/SendMailClass.php');

// ページアクセスの処理用クラスのインスタンス化
$page = new Redirect();
// ページアクセスの直打ち防止を設置
$page->redirectToInput();
// POSTされた値の格納
$posted = $_POST;
// // DB用クラスの変数定義
$dbh = "";
// DB用クラスのインスタンス化
$connect = new FormDBConnect($dbh);
// test用テーブルにPOSTされた値を書き込み
$connect->formInsert($posted);
// メール用クラスのインスタンス化
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
		<link rel="stylesheet" href="css/caution.css">
    <title>お問い合わせ（送信完了）</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
  </head>
  <body>
    <!--パンくず開始-->
    <div class="breadcrumb">
      <ul class="inner">
        <li><a href="input.php">入力画面</a></li>
        <li>お問い合わせ（送信完了）</li>
      </ul>
    </div>
    <!--パンくず終了-->
    <main id="main">
      <section>
        <div class="inner">
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