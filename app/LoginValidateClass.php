<?php

// 定数クラス
require_once __DIR__.'/ConstantClass.php';
// バリデーション用クラス(親クラス)
require_once __DIR__.'/FormValidateClass.php';

  /*
  [クラス説明]
  用途：バリデーション処理
  備考：エスケープ処理を含む
  */
class LoginValidate extends FormValidate {
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
  備考：未使用
  */
  // public function adminEscape (string $key) :? string {
  //     return !empty($this->posted[$key]) ? htmlspecialchars($this->posted[$key],ENT_QUOTES,'UTF-8') : null;
  // }

  /*
  [メソッド説明]
  用途：値のエスケープ処理
  パラメータ：nameタグ (string型)
  返り値：postされた値 (string型)
  備考：継承のため、未使用
  */
  // public function escape (string $key) :? string {
  //     return !empty($this->posted[$key]) ? htmlspecialchars($this->posted[$key],ENT_QUOTES,'UTF-8') : null;
  // }

  /*
  [メソッド説明]
  用途：パスワードのエスケープ処理 + ハッシュ化
  パラメータ：nameタグ (string型)
  返り値：postされた値 (string型)
  備考：項目全体をキーで取得し、エスケープした値を出力
  */
  public function escapePassword (string $key) :? string {
      return !empty($this->posted[$key]) ? hash("sha256",htmlspecialchars($this->posted[$key],ENT_QUOTES,'UTF-8')) : null;
  }

  /*
  [メソッド説明]
  用途；checkedをつける
  パラメータ：nameタグ (string型)
  返り値：postされた値 (string型)
  備考：継承のため、未使用
  */
  // public function checkMark (string $key, string $val) :? string {
  //   return $this->posted[$key] === $val ? "checked" : null;
  // }

  /*
  [メソッド説明]
  用途：パスワード変更時のバリデーション処理
  パラメータ：なし
  返り値：$errors (配列)
  返り値の配列構造：$errors['is_empty']、$errors['pregmatch']、$errors['eqaulity']
  備考：①空判定 を判定し、結果を配列に出力。戻り値を１つにまとめたい都合上、このメソッドで一括して処理。
  */
  public function updatePasswordError (array $posted) :? array {

    // 各エラー文言の格納用
    $errors['is_empty']   = [];
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
    if(isset($posted['is_update']) && $posted['is_update'] === "check_update_password") :
      foreach($this->updatePasswordEmpty() as $key) :
        empty($this->posted[$key]) ? $errors['is_empty'][$key] = Constant::REQUIRED[$key]."は必須入力です。"."<br>" : null;
      endforeach;
    endif;

  // 入力制限の判定
    if(isset($posted['is_update']) && $posted['is_update'] === "check_update_password") :
      foreach($this->updatePasswordPregmatch() as $key) :
        !preg_match($pattern[$key], $this->posted[$key]) ? $errors['pregmatch'][$key] = Constant::REQUIRED[$key]."{$answer[$key]}" : null;
      endforeach;
    endif;

  // 値の同一チェック
    if(isset($posted['is_update']) && $posted['is_update'] === "check_update_password") :
      foreach($this->updatePasswordEquality() as $key) :
        $this->posted[$key] !== $this->posted['user_password'] ? $errors['equality'][$key] = "入力された".Constant::REQUIRED[$key]."は一致しません"."</br>" : null;
      endforeach;
    endif;

    return $errors;
  }

  /*
  [メソッド説明]
  用途：管理画面ログイン時 (login.php)
  パラメータ：なし
  返り値：$errors (配列)
  備考：
  */
  public function loginErrors () :? array {
    // 各エラー文言の格納用
    $errors['is_empty']   = [];
    // 値の空判定
    foreach($this->loginItemEmpty() as $key) :
      empty($this->posted[$key]) ? $errors['is_empty'][$key] = Constant::REQUIRED[$key]."は必須入力です。"."<br>" : null;
    endforeach;

    return $errors;
  }

  /*
  [メソッド説明]
  用途：基本のバリデーション処理
  パラメータ：なし
  返り値：$errors (配列)
  返り値の配列構造：$errors['is_empty']、$errors['pregmatch']、$errors['eqaulity']
  備考：①空判定 ②入力値の判定 ③値の同一性 を判定し、結果を配列に出力。戻り値を１つにまとめたい都合上、このメソッドで一括して処理。
  */
  public function createNewUserErrors (array $posted) :? array {

    // 各エラー文言の格納用
    $errors['is_empty']   = [];
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
    if(isset($_POST['is_create_new_user']) && $posted['is_create_new_user'] === "check_create_new_user") :
      foreach($this->fieldItemEmpty() as $key) :
        empty($this->posted[$key]) ? $errors['is_empty'][$key] = Constant::REQUIRED[$key]."は必須入力です。"."<br>" : null;
      endforeach;
    endif;

  // 入力制限の判定
    if(isset($_POST['is_create_new_user']) && $posted['is_create_new_user'] === "check_create_new_user") :
      foreach($this->fieldItemPregmatch() as $key) :
        !preg_match($pattern[$key], $this->posted[$key]) ? $errors['pregmatch'][$key] = Constant::REQUIRED[$key]."{$answer[$key]}" : null;
      endforeach;
    endif;

  // 値の同一チェック
    if(isset($_POST['is_create_new_user']) && $posted['is_create_new_user'] === "check_create_new_user") :
      foreach($this->fieldItemEquality() as $key) :
        $this->posted[$key] !== $this->posted['user_password'] ? $errors['equality'][$key] = "入力された".Constant::REQUIRED[$key]."は一致しません"."</br>" : null;
      endforeach;
    endif;

    return $errors;
  }
  /*
  [メソッド説明]
  用途：ユーザ新規作成(registration.php)
  パラメータ：なし
  返り値：$errors (配列)
  返り値の配列構造：$errors['is_empty']、$errors['pregmatch']、$errors['eqaulity']
  備考：①空判定 ②入力値の判定 ③値の同一性 を判定し、結果を配列に出力。戻り値を１つにまとめたい都合上、このメソッドで一括して処理。
  */
  public function initialRegistrationErrors () :? array {

    // 各エラー文言の格納用
    $errors['is_empty']   = [];
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
    if(isset($_POST['registration'])) :
      foreach($this->fieldItemEmpty() as $key) :
        empty($this->posted[$key]) ? $errors['is_empty'][$key] = Constant::REQUIRED[$key]."は必須入力です。"."<br>" : null;
      endforeach;
    endif;

  // 入力制限の判定
    if(isset($_POST['registration'])) :
      foreach($this->fieldItemPregmatch() as $key) :
        !preg_match($pattern[$key], $this->posted[$key]) ? $errors['pregmatch'][$key] = Constant::REQUIRED[$key]."{$answer[$key]}" : null;
      endforeach;
    endif;

  // 値の同一チェック
    if(isset($_POST['registration'])) :
      foreach($this->fieldItemEquality() as $key) :
        $this->posted[$key] !== $this->posted['user_password'] ? $errors['equality'][$key] = "入力された".Constant::REQUIRED[$key]."は一致しません"."</br>" : null;
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
  [メソッド説明]
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
  [メソッド説明]
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
  [メソッド説明]
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
  [メソッド説明]
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
  [メソッド説明]
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
  [メソッド説明]
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