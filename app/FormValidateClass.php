<?php

// 定数クラスを読み込み
require_once(__DIR__.'/ConstantClass.php');

  /*
  [クラス説明]
  用途：バリデーション処理
  備考：エスケープ処理を含む
  */
class FormValidate {
  protected $posted = [];

  /*
  [メソッド説明]
  パラメータ：postされた値 (配列)
  初期化後：postされた値 (オブジェクト型)
  備考：$postedの中身は項目の入力画面にて$_POSTより取得
  */
  public function __construct(array $posted) {
    $this->posted = $posted;
    }

  /*
  [メソッド説明]
  用途：値のエスケープ処理
  パラメータ：nameタグ (string型)
  返り値：postされた値 (string型)
  備考：項目全体をキーで取得し、エスケープした値を出力
  */
  public function escape (string $key) :? string {
      return !empty($this->posted[$key]) ? htmlspecialchars($this->posted[$key],ENT_QUOTES,'UTF-8') : null;
  }

  /*
  [メソッド説明]
  用途；checkedをつける
  パラメータ：nameタグ (string型)
  返り値：postされた値 (string型)
  備考：foreachを使用しない場合に、POST値を判別し、checkedをつける(入力された値を記憶させる)
  */
  public function checkMark (string $key, string $val) :? string {
    return $this->posted[$key] === $val ? "checked" : null;
  }


  /*
  [メソッド説明]
  用途：基本のバリデーション処理
  パラメータ：なし
  返り値：$errors (配列)
  返り値の配列構造：$errors['is_empty']、$errors['pregmatch']、$errors['eqaulity']
  備考：①空判定 ②入力値の判定 ③値の同一性 を判定し、結果を配列に出力。戻り値を１つにまとめたい都合上、このメソッドで一括して処理。
  */
  public function formErrors() :? array {

    // 各エラー文言の格納用
    $errors['is_empty'] = [];
    $errors['pregmatch'] = [];
    $errors['eqaulity'] =[];

    // 「入力制限の判定」で使用　判定パターン
    $pattern = [];
    $pattern['last_kana'] = "/^[ァ-ヾ\s　]+$/u";
    $pattern['first_kana'] = "/^[ァ-ヾ\s　]+$/u";
    $pattern['zip'] = "/^[0-9]{7}$/";
    $pattern['phone'] = "/^[0-9]{9,11}$/";
    $pattern['mail'] = "/^[a-z][a-zA-Z0-9_¥.¥-]*@[a-zA-Z0-9_¥.¥-]+$/";
    $pattern['cf_mail'] = "/^[a-z][a-zA-Z0-9_¥.¥-]*@[a-zA-Z0-9_¥.¥-]+$/";

    // 「入力制限の判定」で使用　応答パターン
    $answer = [];
    $answer['last_kana'] = 'はカタカナ(全角)ではありません'. '</br>';
    $answer['first_kana'] = 'はカタカナ(全角)ではありません'. '</br>';
    $answer['zip'] = 'は入力は-(ハイフン)抜きの半角数字7桁です'. '</br>';
    $answer['phone'] = 'は文字数は9文字以上11文字以下で入力してください'. '</br>';
    $answer['mail'] = 'は「＠」がありません。再度Emailを入力してください'.'</br>';
    $answer['cf_mail'] = 'は「＠」がありません。再度Emailを入力してください'.'</br>';

  // 値の空判定
    if(isset($_POST['submit'])) :
      foreach($this->fieldItemEmpty() as $key) :
        empty($this->posted[$key]) ? $errors['is_empty'][$key] = Constant::REQUIRED[$key]."は必須入力です"."<br>" : null;
      endforeach;
    endif;

  // 入力制限の判定
    if(isset($_POST['submit'])) :
      foreach($this->fieldItemPregmatch() as $key) :
        !preg_match($pattern[$key], $this->posted[$key]) ? $errors['pregmatch'][$key] = Constant::REQUIRED[$key]."{$answer[$key]}" : null;
      endforeach;
    endif;

  // 値の同一チェック
    if(isset($_POST['submit'])) :
      foreach($this->fieldItemEquality() as $key) :
        $this->posted[$key] !== $this->posted['mail'] ? $errors['equality'][$key] = "入力された".Constant::REQUIRED[$key]."は一致しません"."</br>" : null;
      endforeach;
    endif;

    return $errors;
  }

  /*
  [メソッド説明]
  用途：POSTデータから必須項目のみを取得
  パラメータ：なし
  返り値：必須項目のnameタグ (配列)
  備考：現在は未使用
  */
  public function mandatoryField() : array {
    $field_items = $this->fieldItemEmpty();
    $mandatory_field = [];

    foreach($this->posted as $key => $val) {
      if(in_array($key, $field_items)) {
        $mandatory_field += [$key => $val]; 
      }
    }
    return $mandatory_field;
  }

  // 【項目追加時は必要に応じて、以下メソッドの返り値にnameタグを追記すること。】 //

  /*
  [メソッド説明]
  用途：「値の空判定」で使用する項目
  パラメータ：なし
  返り値：項目のnameタグ (配列)
  備考：
  */
  public function fieldItemEmpty() : array {
    return
    [
      "last",
      "first",
      "last_kana",
      "first_kana",
      "zip",
      "pref",
      "city",
      "street",
      "phone",
      "mail",
      "cf_mail",  
      "dtl",
      "consultation_type",
      "sex",
    ];
  }

  /*
  [メソッド説明]
  用途：「入力制限の判定」で使用する項目
  パラメータ：なし
  返り値：項目のnameタグ (配列)
  備考：
  */
  public function fieldItemPregmatch() : array {
    return
    [
      "last_kana",
      "first_kana",
      "zip",
      "phone",
      "mail",
      "cf_mail",
    ];
  }

  /*
  [メソッド説明]
  用途：「値の同一チェック」で使用する項目
  パラメータ：なし
  返り値：項目のnameタグ (配列)
  備考：
  */
  public function fieldItemEquality() : array {
    return
    [
      "cf_mail",
    ];
  }

}