<?php

// 定数用クラス読み込み
require_once(__DIR__.'/ConstantClass.php');
// require_once(__DIR__.'/FormValidateClass.php');

  /*
  [クラス説明]
  用途：メール送信処理
  備考：ユーザ向け、管理者向けに処理を分割
  */
class SendMailClass {

  public $now                = "";
  public $addresss_for_user  = "";
  public $addresss_for_admin = "";
  public $option_for_user    = "";
  public $option_for_admin   = "";
  public $title_for_user     = "";
  public $title_for_admin    = "";
  public $body_for_user      = "";
  public $body_for_admin     = "";
  public $sender_name        = "";
  public $posted             = [];

  /*
  [コンストラクタ説明]
  パラメータ：postされた値 (配列)
  初期化後：postされた値、定数値、各メソッドの戻り値のオブジェクト型
  備考：$postedの中身は項目の入力画面にて$_POSTより取得
  */
  public function __construct(array $posted) {

    mb_language("ja");
    mb_internal_encoding('utf-8');

    $this->posted                   = $posted;
    $this->now                      = date('Y-m-d H:i:s');
    $this->addresss_for_user        = $this->posted["mail"];
    $this->addresss_for_admin       = Constant::ADDRESS_FOR_ADMIN["test"];
    $this->option_for_user          = $this->optionForUser();
    $this->option_for_admin         = $this->optionForAdmin();
    $this->sender_name              = mb_encode_mimeheader(Constant::ADDRESS_FOR_USER["test"],"ISO-2022-JP-MS");
    $this->body_for_user            = $this->userBody();
    $this->body_for_admin           = $this->adminBody();
    $this->title_for_user           = Constant::TITLE_FOR_USER["test"];
    $this->title_for_admin          = Constant::TITLE_FOR_ADMIN["test"];
  }

  /*
  [メソッド説明]
  用途：送信処理
  パラメータ：
  返り値：$mail_form (string型)
  備考：ユーザ向けメール送信
  */
  public function send_to_user () : bool {

    $mail_form = mb_send_mail
    (
      $this->addresss_for_user,
      $this->title_for_user,
      $this->body_for_user,
      $this->option_for_user
    );
    return $mail_form;
    }

  /*
  [メソッド説明]
  用途：送信処理
  パラメータ：
  返り値：$mail_form (string型)
  備考：管理者向けメール送信
  */
  public function send_to_admin () : bool {

    $mail_form = mb_send_mail
    (
      $this->addresss_for_admin,
      $this->title_for_admin,
      $this->body_for_admin,
      $this->option_for_admin
    );
    return $mail_form;
    }

  /*
  [メソッド説明]
  用途：メール本文（共通）
  パラメータ：
  返り値：$body (string型)
  備考：
  */
  public function commonBody() : string {

    $body = "";
    $body .= "\n"
    ."■ 送信時間：" .$this->now ."\n"
    ."■ 名前：" .$this->posted["last"] .$this->posted["first"] ."\n"
    ."■ フリガナ：" .$this->posted["last_kana"] .$this->posted["first_kana"] ."\n"
    ."■ 性別：" .$this->posted["sex"] ."\n"
    ."■ 住所：" .$this->posted["zip"] ."\n"
    ."■ 都道府県：" .$this->posted["pref"] ."\n"
    ."■ 市区町村：" .$this->posted["city"] ."\n"
    ."■ 番地：" .$this->posted["street"] ."\n"
    ."■ 建物名：" .$this->posted["building"] ."\n"
    ."■ 電話番号：" .$this->posted["phone"] ."\n"
    ."■ メールアドレス：" .$this->posted["mail"] ."\n"
    ."■ ご相談種別：" .$this->posted["consultation_type"] ."\n"
    ."■ お問い合わせ内容：" .$this->posted["dtl"] ."\n";

    return $body;
  }

  /*
  [メソッド説明]
  用途：ユーザ向け本文
  パラメータ：
  返り値：$body (string型)
  備考：実行時に共通本文を呼び出し
  */
  public function userBody() : string {
    $body = "";
    $body .= "\n"
    ."-------------------------------------------------------------"."\n"
    ."本メールは自動配信となります。" . "\n"
    ."-------------------------------------------------------------"."\n"
    ."\n"
    .$this->posted["last"] .$this->posted["first"] ."　様" ."\n"
    ."\n"
    ."お問い合わせありがとうございました。"."\n"
    ."担当より確認後、折返し返答をさせていただきます。" ."\n"
    ."\n"
    ."頂いたお問い合わせは以下のとおりです。" ."\n"
    ."\n";
    $body .= $this->commonBody();
    $body .= "\n"
    ."※当メールに心当たりのない場合は" ."\n"
    ."お手数ですが以下のメールアドレスへご返信願います。" ."\n"
    ."\n"
    ."--------------------------------------------" ."\n"
    ."株式会社OXOXOXOXOX" ."\n"
    ."URL https://www.OXOX.co.jp/" ."\n"
    ."e-mail OXOX@OXOX.co.jp" ."\n"
    ."\n"
    ."TEL 0120-123-1234" ."\n"
    ."FAX 0120-123-1234" ."\n"
    ."--------------------------------------------" ."\n";

    return $body;
  }

  /*
  [メソッド説明]
  用途：管理者向け本文
  パラメータ：
  返り値：$body (string型)
  備考：実行時に共通本文を呼び出し
  */
  public function adminBody() : string {
    $body = "";
    $body .= "\n"
    ."-------------------------------------------------------------"."\n"
    ."本メールは自動配信となります。" . "\n"
    ."-------------------------------------------------------------"."\n"
    ."\n"
    ."担当　様" ."\n"
    ."\n"
    ."以下の方より、お問い合わせがありました。"."\n"
    ."\n";
    $body .= $this->commonBody();
    $body .= "\n"
    ."\n"
    ."--------------------------------------------" ."\n"
    ."送信時間：" .$this->now ."\n"
    ."送信者のIP：" .$_SERVER["REMOTE_ADDR"]."\n"
    ."送信者のアドレス：" .gethostbyaddr($_SERVER["REMOTE_ADDR"])."\n"
    ."送信者のブラウザ：" .$_SERVER["HTTP_USER_AGENT"]."\n"
    ."ホスト名：" .$_SERVER["SERVER_NAME"]."\n"
    ."--------------------------------------------" ."\n";

    return $body;
  }

  /*
  [メソッド説明]
  用途：オプション値の追加
  パラメータ：
  返り値：$option (string型)
  備考：ユーザ向け
  */
  public function optionForUser() : string {
    $option  = "";
    $option .= "Content-Type: text/plain" ."\r\n";
    $option .= "Return-Path: " .$this->sender_name ."\r\n";
    $option .= "From: " .$this->sender_name ."\r\n";
    $option .= "Sender: " .$this->sender_name ."\r\n";
    $option .= "X-Sender: " .$this->sender_name ."\r\n";
    $option .= "X-Priority: 3" ."\r\n";

    return $option;
  }

  /*
  [メソッド説明]
  用途：オプション値の追加
  パラメータ：
  返り値：$option (string型)
  備考：管理者向け
  */
  public function optionForAdmin() : string {
    $option = "";
    $option = "Content-Type: text/plain \r\n"
    . "Return-Path: " .$this->sender_name ."\r\n"
    . "From: " .$this->sender_name ."\r\n"
    . "Sender: " .$this->sender_name ."\r\n"
    . "X-Sender: " .$this->sender_name ."\r\n"
    . "X-Priority: 3 \r\n";

    return $option;
  }

}