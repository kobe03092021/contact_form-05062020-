<?php

// ページへのアクセスの処理用クラス
class Redirect {
  private $refferer;

  /*
  [メソッド説明]
  用途：URLの直叩き防止
  パラメータ：なし
  返り値：$return (string型)
  備考：入力画面を経ずにURLへ直アクセスした場合、強制的に入力画面へリダイレクト
  */
  public function redirectToInput() : string {
    $return = "";
    if ( $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $return .= header("location:input.php");
    $return .= exit;
    }
    return $return;
  }
  /*
  [メソッド説明]
  用途：URLの直叩き防止
  パラメータ：なし
  返り値：$return (string型)
  備考：入力画面を経ずにURLへ直アクセスした場合、強制的にユーザ登録画面へリダイレクト
  */
  public function redirectToRegistration() : string {
    $return = "";
    if ( $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $return .= header("location:registration.php");
    $return .= exit;
    }
    return $return;
  }
  /*
  [メソッド説明]
  用途：URLの直叩き防止
  パラメータ：なし
  返り値：$return (string型)
  備考：入力画面を経ずにURLへ直アクセスした場合、強制的にログイン画面へリダイレクト
  */
  public function redirectToLogin() : string {
    $return = "";
    if ( $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $return .= header("location:login.php");
    $return .= exit;
    }
    return $return;
  }

}