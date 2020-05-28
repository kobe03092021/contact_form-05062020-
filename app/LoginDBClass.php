<?php

// 定数用クラス読み込み
require_once(__DIR__.'/ConstantClass.php');
require_once(__DIR__.'/FormDBClass.php');

/*
--------------------------------------------------------------------------
クラス説明
--------------------------------------------------------------------------
用途：データベース操作（管理画面用）
備考：各SQL文をセットで用意
*/
class LoginDBConnect extends FormDBConnect {

  private $dbh  = null;
  private $table  = [];

  /*
  コンストラクタ説明
  用途：PDO初期化
  パラメータ：$dbh
  リターン：$this->dbh
  備考：
  */
  public function __construct(string $dbh) {
      // データベース接続オブジェクトを生成 ※接続オプションあり
      $dbh = new PDO(Constant::DB_CONNECT["DB_DSN"], Constant::DB_CONNECT["DB_USER"], Constant::DB_CONNECT["DB_PASS"]);
      // // データベース接続オプションを設定（データベースのエラー表示、）
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $this->dbh = $dbh;
  }

  /*
  用途：ユーザ新規登録
  パラメータ：$posted
  リターン：$stmt
  状態：使用中
  使用箇所：registration_upload.php
  */
  public function initialRegistration(array $posted) :object {
    try {
      // 配列内の各キー名を変数名に分解
      extract($posted, EXTR_REFS);
      // SQL文の作成
      $insert = "INSERT INTO admin_test (user_name, user_password) VALUES (:user_name, :user_password)";
      // SQL文のオブジェクトを取得
      $stmt = $this->dbh->prepare($insert);
      // 値をパラメータにバインド
      $stmt->bindValue(':user_name', $user_name);
      $stmt->bindValue(':user_password', $user_password);
      // 挿入する値が入った変数をexecuteにセットしてSQLを実行
      $stmt->execute();
    }
    catch(Exception $e)
    {
      echo '[Failed] initialRegistration(array $posted)' .'「管理者に問い合わせください」' .'<br>';
      exit;
    }
    return $stmt;
  }

/*
用途：管理画面でのユーザ作成
パラメータ：$posted
リターン：$stmt
備考：
*/
public function userCreate(array $posted) :object {
  try {
    // 配列内の各キー名を変数名として抽出
    extract($posted, EXTR_REFS);
    // SQL文の作成
    $sql = "INSERT INTO admin_test (user_name, user_password) VALUES (:user_name, :user_password)";
    // SQL文のオブジェクトを取得
    $stmt = $this->dbh->prepare($sql);
    // 値をパラメータにバインド
    $stmt->bindValue(':user_name', $user_name);
    $stmt->bindValue(':user_password', $user_password);
    // 挿入する値が入った変数をexecuteにセットしてSQLを実行
    $stmt->execute();
  }
  catch(Exception $e)
  {
    echo '[Failed] userCreate(array $posted)' .'「管理者に問い合わせください」' .'<br>';
    exit;
  }
  return $stmt;
}

  /*
  用途：管理画面でのパスワード変更
  パラメータ：$posted
  リターン：$stmt
  状態：使用中
  */
  public function userUpdatePassword(array $posted) :object {
    try {
      $id = $_SESSION['SSID']['id'];
      $user_password = "";
      // 配列のキー名から変数名を抽出 
      extract($posted, EXTR_REFS);
      // SQL文の作成
      $sql =  ( "UPDATE admin_test Set user_password='$user_password' WHERE id='$id' " );
      // SQL文を格納（パラメータにユーザ名のSESSIONデータ） 
      $stmt = $this->dbh->prepare($sql);
      var_dump($stmt);
      // 値をバインド
      // $stmt -> bindParam(':id',$id, PDO::PARAM_STR);
      // $stmt -> bindParam(':user_name',$user_name, PDO::PARAM_STR);
      $stmt -> bindParam(':user_password',$user_password, PDO::PARAM_STR);
      // 実行
      $stmt->execute();
      // フェッチで該当するデータを全件取得(連想配列をキー名で取得)
      // $rows = $stmt->fetchall(PDO::FETCH_ASSOC);
      // foreach($rows as $row) {
      //   // if(in_array($user_name,$row) && in_array($user_password,$row)) {
      //     $this->table = [
      //       'id'       => $row['id'],
      //       'user_name' => $row['user_name'],
      //       'user_password' => $row['user_password']
      //     ];
        // }
      // }

    }
    catch(Exception $e)
    {
      echo '[Failed] userUpdatePassword()' .'「管理者に問い合わせください」' .'<br>';
      exit;
    }
    return $stmt;
  }

  /*
  用途：管理画面でのアカウント削除
  パラメータ：$posted
  リターン：$stmt
  状態：使用中
  備考：削除の実施前にdisplayUserを実行
  */
  public function userDeleteAccount(string $user_name) :object {
    try {
      // SQL文の作成
      $sql =  ( "DELETE FROM admin_test WHERE user_name='$user_name' " );
      // SQL文を格納（パラメータにユーザ名のSESSIONデータ）
      $stmt = $this->dbh->prepare($sql);
      // 値をバインド
      $stmt -> bindParam(':user_name',$user_name, PDO::PARAM_STR);
      // 実行
      $stmt->execute();
    }
    catch(Exception $e)
    {
      echo '[Failed] userUpdatePassword()' .'「管理者に問い合わせください」' .'<br>';
      exit;
    }
    return $stmt;
  }

  /*
  用途：テーブル情報の削除
  パラメータ：なし
  リターン：$stmt(SQLの実行文)
  状態：使用中
  */
  public function adminDelete() :object {
    try {
      // SQL文の作成
      $sql ="DELETE From admin_test Where user_name='user_name'";
      // SQL文のオブジェクトを取得
      $stmt = $this->dbh->prepare($sql);
      // 挿入する値が入った変数をexecuteにセットしてSQLを実行
      $stmt->execute();
    }
    catch(Exception $e)
    {
      echo '[Failed] adminDelete()' .'「管理者に問い合わせください」' .'<br>';
      exit;
    }
    return $stmt;
  }

  /*
  用途：ユーザ名に紐づくユーザデータを照会
  パラメータ：$posted
  リターン：$row
  状態：使用中
  */
  public function displayUser(string $user_name) :array {
    try {
      // SQL文の作成
      $sql =  ( "SELECT * FROM admin_test WHERE user_name='$user_name' " );
      // var_dump($sql);
      // SQL文を格納（パラメータにユーザ名のPOSTデータ）
      $stmt = $this->dbh->prepare($sql);
      // // // 値をバインド
      $stmt -> bindParam(':user_name',$user_name, PDO::PARAM_STR);
      // 実行文の用意
      $stmt->execute();
      // フェッチで該当するデータを全件取得(連想配列をキー名で取得)
      $rows = $stmt->fetchall(PDO::FETCH_ASSOC);
      foreach($rows as $row) {
          $this->table = [
            'id'       => $row['id'],
            'user_name' => $row['user_name'],
            'user_password' => $row['user_password']
          ];
      }
    }
    catch(Exception $e)
    {
      echo '[Failed] login(array $posted)' .'「管理者に問い合わせください」' .'<br>';
      exit;
    }
    return $this->table;
  }

  /*
  メソッド説明
  用途：ログイン時のパスワードを照合
  パラメータ：なし
  リターン：$row
  状態：使用中
  */
  public function login(array $posted) :array {
    try {
      $user_name ="";
      $user_password ="";
      // 配列のキー名から変数名を抽出 
      extract($posted, EXTR_REFS);
      // SQL文の作成
      $sql =  ( "SELECT * FROM admin_test WHERE user_name='$user_name' AND user_password='$user_password' " );
      // SQL文を格納（パラメータにユーザ名のPOSTデータ） 
      $stmt = $this->dbh->prepare($sql);
      // // // 値をバインド
      $stmt -> bindParam(':user_name',$user_name, PDO::PARAM_STR);
      $stmt -> bindParam(':user_password',$user_password, PDO::PARAM_STR);
      // 実行文の用意
      $stmt->execute();
      // フェッチで該当するデータを全件取得(連想配列をキー名で取得)
      $rows = $stmt->fetchall(PDO::FETCH_ASSOC);
      foreach($rows as $row) {
        // if(in_array($user_name,$row) && in_array($user_password,$row)) {
          $this->table = [
            'id'       => $row['id'],
            'user_name' => $row['user_name'],
            'user_password' => $row['user_password']
          ];
        // }
      }

    }
    catch(Exception $e)
    {
      echo '[Failed] login(array $posted)' .'「管理者に問い合わせください」' .'<br>';
      exit;
    }
    return $this->table;
  }

  /*
  メソッド説明
  用途：テーブル情報を全件取得
  パラメータ：なし
  リターン：$row
  状態：使用中
  */
  public function allAccount() :array {
    try {
      // SQL文の作成
      $select = ("SELECT * FROM admin_test");
      // 変数をqueryにセットしてSQLを実行
      foreach($this->dbh->query($select) as $row) {
        echo '<table>';
        echo '<tr>'
          .'<th>ID</th>'
          .'<th>ユーザ名</th>'
          .'<th>パスワード</th>'
          . '</tr>';
        echo '<th>'. '<tr>'
          . '<td>', htmlspecialchars($row['id'])
          . '<td>', htmlspecialchars($row['user_name'])
          . '<td>', htmlspecialchars($row['user_password']);
        echo '</tr>'. "</table>". "</br>";
      }
    }
    catch(Exception $e)
    {
      echo '[Failed] adminSelect()' .'「管理者に問い合わせください」' .'<br>';
      exit;
    }
    return $row;
  }

  /*
  メソッド説明
  用途：登録時のユーザ名の重複を確認
  パラメータ：$posted
  リターン：$row
  状態：使用中
  */
  public function userDuplication(array $posted) :array {
    try {
      $user_name ="";
      // 配列のキー名から変数名を抽出 
      extract($posted, EXTR_REFS);
      // SQL文の作成
      $sql =  ( "SELECT * FROM admin_test WHERE user_name='$user_name'" );
      // SQL文を格納（パラメータにユーザ名のPOSTデータ） 
      $stmt = $this->dbh->prepare($sql);
      // // // 値をバインド
      $stmt -> bindParam(':user_name',$user_name, PDO::PARAM_STR);
      // 実行文の用意
      $stmt->execute();
      // フェッチで該当するデータを全件取得(連想配列をキー名で取得)
      $rows = $stmt->fetchall(PDO::FETCH_ASSOC);
      foreach($rows as $row) {
        // if(in_array($user_name,$row) && in_array($user_password,$row)) {
          $this->table = [
            'user_name' => $row['user_name'],
          ];
        // }
      }
    }
    catch(Exception $e)
    {
      echo '[Failed] userDuplication()' .'「管理者に問い合わせください」' .'<br>';
      exit;
    }
    return $this->table;
  }

  /*
  メソッド説明
  用途：テーブル情報を全件取得
  パラメータ：なし
  リターン：$row
  状態：未使用
  // */
  // public function adminSelect() :string {
  //   try {
  //     // SQL文の作成
  //     $select = ("SELECT * FROM test");
  //     // 変数をqueryにセットしてSQLを実行
  //     foreach($this->dbh->query($select) as $row) {
  //       echo '<table>';
  //       echo '<tr>'
  //         .'<th>ID</th>'
  //         .'<th>ユーザ名</th>'
  //         .'<th>パスワード</th>'
  //         . '</tr>';
  //       echo '<th>'. '<tr>'
  //         . '<td>', $row['id']
  //         . '<td>', $row['user_name']
  //         . '<td>', $row['user_password'];
  //       echo '</tr>'. "</table>". "</br>";
  //     }
  //   }
  //   catch(Exception $e)
  //   {
  //     echo '[Failed] adminSelect()' .'「管理者に問い合わせください」' .'<br>';
  //     exit;
  //   }
  //   return $row;
  // }

  /*
  用途：新規テーブル作成
  パラメータ：なし
  リターン：$stmt
  状態：未使用
  備考：テスト用
  */
  public function tableCreate(string $table_name) :object {
    try {
      // SQL文の作成
      $sql ="CREATE TABLE '$table_name' (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        user_name VARCHAR(30) NOT NULL,
        user_password VARCHAR(30) NOT NULL
        )";
      // SQL文のオブジェクトを取得
      $stmt = $this->dbh->prepare($sql);
      // 挿入する値が入った変数をexecuteにセットしてSQLを実行
      $stmt->execute();
    }
    catch(Exception $e)
    {
      echo '[Failed] tableCreate() ' .'「管理者に問い合わせください」' .'<br>';
      exit;
    }
    return $stmt;
  }

  /*
  用途：テーブル情報の更新
  パラメータ：なし
  リターン：$stmt(SQLの実行文)
  状態：未使用
  備考：
  */
  // public function adminUpdate() :object {
  //   try {
  //     // SQL文の作成
  //     $sql ="UPDATE admin_test Set user_name='updated' Where id=6";
  //     // SQL文のオブジェクトを取得
  //     $stmt = $this->dbh->prepare($sql);
  //     // 挿入する値が入った変数をexecuteにセットしてSQLを実行
  //     $stmt->execute();
  //   }
  //   catch(Exception $e)
  //   {
  //     echo '[Failed] adminUpdate()' .'「管理者に問い合わせください」' .'<br>';
  //     exit;
  //   }
  //   return $stmt;
  // }


  
}
