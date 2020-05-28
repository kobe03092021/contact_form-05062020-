<?php
// ページへのアクセスの処理用クラス
require_once(__DIR__.'/app/RedirectClass.php');
// リダイレクト処理
$page = new Redirect();
// ページアクセスの直打ち防止
$page->redirectToRegistration();
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/caution.css">
    <title>新規ユーザ登録完了</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
  </head>
  <body>
    <!--パンくず開始-->
    <div class="breadcrumb">
      <ul class="inner">
        <li><a href="login.php">ログイン画面</a></li>
        <li>新規ユーザ登録完了</li>
      </ul>
    </div>
    <!--パンくず終了-->
    <main id="main">
      <section>
        <div class="inner">
          <p class ="">
          ご登録ありがとうございました。<br>
          </p>
        </div>
      </section>
    </main>
  </body>
</html>