<?php

// 定数クラス
require_once __DIR__.'/ConstantClass.php';
// バリデーション用クラス(親クラス)
require_once __DIR__.'/FormValidateClass.php';

/*
--------------------------------------------------------------------------
クラス説明
--------------------------------------------------------------------------
用途：バリデーション処理（ログイン画面、管理画面）
備考：エスケープ処理を含む
*/
class UserValidate extends FormValidate {
  protected $posted = [];
  protected $hash   = [];

  /*
  パラメータ：postされた値 (配列)
  初期化後：postされた値 (オブジェクト型)
  備考：$postedの中身は項目の入力画面にて$_POSTより取得
  */
  public function __construct(array $posted) {
    $this->posted = $posted;
    }

  /*
  用途：パスワードのハッシュ化
  パラメータ：$key
  返り値：$posted[$key]
  状態：現状、未使用
  備考：指定のキー名を取得し、ハッシュ化後の値を出力
  */
  public function hash (string $key) :? string {
    return !empty($this->posted[$key]) ? password_hash($this->posted[$key],PASSWORD_DEFAULT) : null;
  }

  /*
  用途：パスワード（ハッシュ化）の照合
  パラメータ：$key
  返り値：$posted[$key]
  状態：現状、未使用
  備考：指定のキー名を取得し、ハッシュ化後の値を照合
  */
  public function hashCheck (string $key) :? string {
    return !empty($this->posted[$key]) ? password_verify($this->posted[$key],PASSWORD_DEFAULT) : null;
  }

  /*
  用途：パスワード変更時のバリデーション処理
  パラメータ：$posted
  返り値：$errors (配列)
  返り値の配列構造：$errors['empty']、$errors['pregmatch']、$errors['eqaulity']
  備考：①空判定 を判定し、結果を配列に出力。戻り値を１つにまとめたい都合上、このメソッドで一括して処理。
  */
  public function updatePasswordError (array $posted) :? array {

    // 各エラー文言の格納用
    $errors['empty']   = [];
    $errors['pregmatch']  = [];
    $errors['eqaulity']   = [];

    // 「入力制限の判定」で使用　判定パターン
    $pattern = [];
    $pattern['user_password'] = "/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/";
    $pattern['confirm_user_password'] = "/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/";

    // 「入力制限の判定」で使用　応答パターン
    $answer = [];
    $answer['user_password'] = 'は数字・文字を各1文字以上含む、8～12文字で入力してください。<br>次のカッコ内にある記号も使用可能です。「 !@#$% 」'. '</br>';
    $answer['confirm_user_password'] = 'は数字・文字を各1文字以上含む、8～12文字で入力してください。<br>次のカッコ内にある記号も使用可能です。「 !@#$% 」'. '</br>';


  // 値の空判定
    if(isset($posted['update']) && $posted['update'] === "check") :
      foreach($this->updatePasswordEmpty() as $key) :
        empty($this->posted[$key]) ? $errors['empty'][$key] = Constant::REQUIRED[$key]."は必須入力です。"."<br>" : null;
      endforeach;
    endif;

  // 入力制限の判定
    if(isset($posted['update']) && $posted['update'] === "check") :
      foreach($this->updatePasswordPregmatch() as $key) :
        !preg_match($pattern[$key], $this->posted[$key]) ? $errors['pregmatch'][$key] = Constant::REQUIRED[$key]."{$answer[$key]}" : null;
      endforeach;
    endif;

  // 値の同一チェック
    if(isset($posted['update']) && $posted['update'] === "check") :
      foreach($this->updatePasswordEquality() as $key) :
        $this->posted[$key] !== $this->posted['user_password'] ? $errors['equality'][$key] = "入力された".Constant::REQUIRED[$key]."は一致しません"."</br>" : null;
      endforeach;
    endif;

    return $errors;
  }

  /*
  用途：管理画面ログイン時 (login.php)
  パラメータ：なし
  返り値：$errors (配列)
  備考：
  */
  public function loginErrors () :? array {
    // 各エラー文言の格納用
    $errors['empty']   = [];
    // 値の空判定
    if(isset($posted['login']) && $posted['login'] === "check") {
      foreach($this->loginItemEmpty() as $key) :
        empty($this->posted[$key]) ? $errors['empty'][$key] = Constant::REQUIRED[$key]."は必須入力です。"."<br>" : null;
      endforeach;
    }

    return $errors;
  }

  /*
  用途：バリデーション処理（管理画面、新規ユーザ作成時）
  パラメータ：$posted
  返り値：$errors (配列)
  返り値の配列構造：$errors['empty']、$errors['pregmatch']、$errors['eqaulity']
  備考：①空判定 ②入力値の判定 ③値の同一性 を判定し、結果を配列に出力。戻り値を１つにまとめたい都合上、このメソッドで一括して処理。
  */
  public function createNewUserErrors (array $posted) :? array {

    // 各エラー文言の格納用
    $errors['empty']   = [];
    $errors['pregmatch']  = [];
    $errors['eqaulity']   = [];
    $errors['duplication']   = [];

    // 「入力制限の判定」で使用　判定パターン
    $pattern = [];
    $pattern['user_name'] = "/^[a-zA-Z]{4,8}+$/";
    $pattern['user_password'] = "/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/";
    $pattern['confirm_user_password'] = "/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/";

    // 「入力制限の判定」で使用　応答パターン
    $answer = [];
    $answer['user_name'] = 'は文字のみ、4～8文字で入力してください。'. '</br>';
    $answer['user_password'] = 'は数字・文字を各1文字以上含む、8～12文字で入力してください。<br>次のカッコ内にある記号も使用可能です。「 !@#$% 」'. '</br>';
    $answer['confirm_user_password'] = 'は数字・文字を各1文字以上含む、8～12文字で入力してください。<br>次のカッコ内にある記号も使用可能です。「 !@#$% 」'. '</br>';


  // 値の空判定
    if(isset($posted['create_new_user']) && $posted['create_new_user'] === "check") :
      foreach($this->fieldItemEmpty() as $key) :
        empty($this->posted[$key]) ? $errors['empty'][$key] = Constant::REQUIRED[$key]."は必須入力です。"."<br>" : null;
      endforeach;
    endif;

  // 入力制限の判定
    if(isset($posted['create_new_user']) && $posted['create_new_user'] === "check") :
      foreach($this->fieldItemPregmatch() as $key) :
        !preg_match($pattern[$key], $this->posted[$key]) ? $errors['pregmatch'][$key] = Constant::REQUIRED[$key]."{$answer[$key]}" : null;
      endforeach;
    endif;

  // 値の同一チェック
    if(isset($posted['create_new_user']) && $posted['create_new_user'] === "check") :
      foreach($this->fieldItemEquality() as $key) :
        $this->posted[$key] !== $this->posted['user_password'] ? $errors['equality'][$key] = "入力された".Constant::REQUIRED[$key]."は一致しません"."</br>" : null;
      endforeach;
    endif;

    return $errors;
  }
  /*
  []
  用途：バリデーション処理(registration.php、ユーザ新規作成)
  パラメータ：なし
  返り値：$errors (配列)
  返り値の配列構造：$errors['empty']、$errors['pregmatch']、$errors['eqaulity']
  備考：①空判定 ②入力値の判定 ③値の同一性 を判定し、結果を配列に出力。戻り値を１つにまとめたい都合上、このメソッドで一括して処理。
  */
  public function initialRegistrationErrors (array $posted) :? array {

    // 各エラー文言の格納用
    $errors['empty']   = [];
    $errors['pregmatch']  = [];
    $errors['eqaulity']   = [];
    $errors['duplication']   = [];

    // 「入力制限の判定」で使用　判定パターン
    $pattern = [];
    $pattern['user_name'] = "/^[a-zA-Z]{4,8}+$/";
    $pattern['user_password'] = "/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/";
    $pattern['confirm_user_password'] = "/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/";

    // 「入力制限の判定」で使用　応答パターン
    $answer = [];
    $answer['user_name'] = 'は文字のみ、4～8文字で入力してください。'. '</br>';
    $answer['user_password'] = 'は数字・文字を各1文字以上含む、8～12文字で入力してください。<br>次のカッコ内にある記号も使用可能です。「 !@#$% 」'. '</br>';
    $answer['confirm_user_password'] = 'は数字・文字を各1文字以上含む、8～12文字で入力してください。<br>次のカッコ内にある記号も使用可能です。「 !@#$% 」'. '</br>';


  // 値の空判定
    if(isset($posted['registration'])) :
      foreach($this->fieldItemEmpty() as $key) :
        empty($this->posted[$key]) ? $errors['empty'][$key] = Constant::REQUIRED[$key]."は必須入力です。"."<br>" : null;
      endforeach;
    endif;

  // 入力制限の判定
    if(isset($posted['registration'])) :
      foreach($this->fieldItemPregmatch() as $key) :
        !preg_match($pattern[$key], $this->posted[$key]) ? $errors['pregmatch'][$key] = Constant::REQUIRED[$key]."{$answer[$key]}" : null;
      endforeach;
    endif;

  // 値の同一チェック
    if(isset($posted['registration'])) :
      foreach($this->fieldItemEquality() as $key) :
        $this->posted[$key] !== $this->posted['user_password'] ? $errors['equality'][$key] = "入力された".Constant::REQUIRED[$key]."は一致しません"."</br>" : null;
      endforeach;
    endif;

    return $errors;
  }

  /*
  用途：POSTデータから必須項目のみを取得
  パラメータ：なし
  返り値：必須項目のnameタグ (配列)
  備考：現在は未使用
  */
  // public function mandatoryField() : array {
  //   $field_items = $this->fieldItemEmpty();
  //   $mandatory_field = [];

  //   foreach($this->posted as $key => $val) {
  //     if(in_array($key, $field_items)) {
  //       $mandatory_field += [$key => $val]; 
  //     }
  //   }
  //   return $mandatory_field;
  // }

  /* 
  【項目追加時は必要に応じて、以下メソッドを追記すること。】
  */

  /*
  用途：「値の空判定」で使用する項目
  パラメータ：なし
  返り値：項目のnameタグ (配列)
  備考：管理画面のログイン時に使用
  */
  public function loginItemEmpty() : array {
    return
    [
      "user_name",
      "user_password",
    ];
  }

  /*
  用途：「値の空判定」で使用する項目
  パラメータ：なし
  返り値：項目のnameタグ (配列)
  備考：管理画面のパスワード変更で使用
  */
  public function updatePasswordEmpty() : array {
    return
    [
      "user_password",
      "confirm_user_password",
    ];
  }

  /*
  用途：「入力制限の判定」で使用する項目
  パラメータ：なし
  返り値：項目のnameタグ (配列)
  備考：管理画面のパスワード変更で使用
  */
  public function updatePasswordPregmatch() : array {
    return
    [
      "user_password",
      "confirm_user_password",
    ];
  }

  /*
  用途：「値の同一チェック」で使用する項目
  パラメータ：なし
  返り値：項目のnameタグ (配列)
  備考：管理画面のパスワード変更で使用
  */
  public function updatePasswordEquality() : array {
    return
    [
      "confirm_user_password",
    ];
  }

  /*
  用途：「値の空判定」で使用する項目
  パラメータ：なし
  返り値：項目のnameタグ (配列)
  備考：
  */
  public function fieldItemEmpty() : array {
    return
    [
      "user_name",
      "user_password",
      "confirm_user_password",
    ];
  }

  /*
  用途：「入力制限の判定」で使用する項目
  パラメータ：なし
  返り値：項目のnameタグ (配列)
  備考：
  */
  public function fieldItemPregmatch() : array {
    return
    [
      "user_name",
      "user_password",
      "confirm_user_password",
    ];
  }

  /*
  用途：「値の同一チェック」で使用する項目
  パラメータ：なし
  返り値：項目のnameタグ (配列)
  備考：
  */
  public function fieldItemEquality() : array {
    return
    [
      "confirm_user_password",
    ];
  }

}