<?php
// バリデーション用クラス読み込み
require_once(__DIR__.'/app/ValidateClass.php');

// ページアクセスの処理用クラスのインスタンス化
$page = new PageAccess();
// ページアクセスの直打ち防止
$page->redirect();
// POSTされた値の格納
$posted = $_POST;
// バリデーション用クラスのインスタンス化
$validate = new Validate($posted);

// メール送信内容を表示
echo('<pre>');
var_dump($posted)."<br/>";
echo('</pre>');
require_once(__DIR__.'/app/db_connect.php');

// 送信処理呼び出し
  mb_language("japanese");
  mb_encode_mimeheader("UTF-8");

  $send_to = $validate->purify('mail');
  $subject = 'お問い合わせの件';
  $sender = "kawanishi@sinq.co.jp";

  $user_message  = "<br/>";
  $user_message  .= '名前（姓）' . $validate->purify('last') ."<br/>";
  $user_message  .= '名前（名）'. $validate->purify('first') ."<br/>";
  $user_message  .= 'フリガナ（セイ）' . $validate->purify('last_kana') ."<br/>";
  $user_message  .= 'フリガナ（メイ）' . $validate->purify('first_kana') ."<br/>";
  $user_message  .= '性別' . $validate->purify('sex') ."<br/>";
  $user_message  .= '住所' . $validate->purify('zip') ."<br/>";
  $user_message  .= '都道府県' . $validate->purify('pref') ."<br/>";
  $user_message  .= '市区町村' . $validate->purify('city') ."<br/>";
  $user_message  .= '番地' . $validate->purify('street') ."<br/>";
  $user_message  .= '建物名' . $validate->purify('building') ."<br/>";
  $user_message  .= '電話番号' . $validate->purify('phone') ."<br/>";
  $user_message  .= 'メールアドレス' . $validate->purify('mail') ."<br/>";
  $user_message  .= 'ご相談種別' . $validate->purify('consultation_type') ."<br/>";
  $user_message  .= 'お問い合わせ内容' . $validate->purify('dtl') ."<br/>";
  $header = 'From: . $sender';
  $header .= '  Return-Path: . $sender';

  if(mb_send_mail($send_to, $subject, $user_message, $header) === true) {
  echo 'お問い合わせありがとうございました';
  }else{
    echo '送信できませんでした';
  }

?>