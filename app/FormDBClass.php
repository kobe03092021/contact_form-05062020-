<?php

// 定数用クラス読み込み
require_once(__DIR__.'/ConstantClass.php');

/*
--------------------------------------------------------------------------
クラス説明
--------------------------------------------------------------------------
用途：データベース操作
備考：各SQL文をセットで用意
*/
class FormDBConnect {

  private $dbh = null;

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
メソッド説明
用途：フォーム送信結果をテーブルに追記(thanks.php)
パラメータ：なし
リターン：$stmt
備考：
*/
  public function formInsert(array $posted) :object {
    try {
      // 配列内の各キー名を変数名として抽出
      extract($posted, EXTR_REFS);
      // SQL文の作成
      $sql = "INSERT INTO test (last, first, last_kana, first_kana, sex, zip, pref, city, street, building, phone, mail, consultation_type, detail) VALUES (:last, :first, :last_kana, :first_kana, :sex, :zip, :pref, :city, :street, :building, :phone, :mail, :consultation_type, :detail)";
      // SQL文のオブジェクトを取得
      $stmt = $this->dbh->prepare($sql);
      // 値をパラメータにバインド
      $stmt->bindValue(':last', $last);
      $stmt->bindValue(':first', $first);
      $stmt->bindValue(':last_kana', $last_kana);
      $stmt->bindValue(':first_kana', $first_kana);
      $stmt->bindValue(':sex', $sex);
      $stmt->bindValue(':zip', $zip);
      $stmt->bindValue(':pref', $pref);
      $stmt->bindValue(':city', $city);
      $stmt->bindValue(':street', $street);
      $stmt->bindValue(':building', $building);
      $stmt->bindValue(':phone', $phone);
      $stmt->bindValue(':mail', $mail);
      $stmt->bindValue(':consultation_type', $consultation_type);
      $stmt->bindValue(':detail', $dtl);
      // 挿入する値が入った変数をexecuteにセットしてSQLを実行
      $stmt->execute();
    }
    catch(Exception $e)
    {
      echo '[Failed] formInsert(array $posted)' .'「管理者に問い合わせください」' .'<br>';
      exit;
    }
    return $stmt;
  }

/*
メソッド説明
用途：テーブル情報を全件取得
パラメータ：なし
リターン：$row
SQL："SELECT * FROM test"
備考：現状、未使用
*/
  public function formSelect() :string {
    try {
      // SQL文の作成
      $select = ("SELECT * FROM test");
      // 変数をqueryにセットしてSQLを実行
      foreach($this->dbh->query($select) as $row) {
        echo '<table>';
        echo '<tr>'
          .'<th>ID</th>'
          .'<th>性</th>'
          .'<th>名</th>'
          .'<th>セイ</th>'
          .'<th>メイ</th>'
          .'<th>性別</th>'
          .'<th>郵便番号</th>'
          .'<th>都道府県</th>'
          .'<th>市区町村</th>'
          .'<th>番地</th>'
          .'<th>電話番号</th>'
          .'<th>メールアドレス</th>'
          .'<th>ご相談種別</th>'
          .'<th>お問い合わせ内容</th>'
          . '</tr>';
        echo '<th>'. '<tr>'
          . '<td>', $row['id']
          . '<td>', $row['last']
          . '<td>', $row['first']
          . '<td>', $row['last_kana']
          . '<td>', $row['first_kana']
          . '<td>', $row['sex']
          . '<td>', $row['zip']
          . '<td>', $row['pref']
          . '<td>', $row['city']
          . '<td>', $row['street']
          . '<td>', $row['building']
          . '<td>', $row['phone']
          . '<td>', $row['mail']
          . '<td>', $row['consultation_type']
          . '<td>', $row['detail'];
        echo '</tr>'. "</table>". "</br>";
      }
    }
    catch(Exception $e)
    {
      echo '[Failed] formSelect()' .'「管理者に問い合わせください」' .'<br>';
      exit;
    }
    return $row;
  }

/*
メソッド説明
用途：新規テーブル作成
パラメータ：なし
リターン：$stmt
SQL："SELECT * FROM test"
備考：現状、未使用
*/
  public function formTableCreate() :object {
    try {
      // SQL文の作成
      $sql = "CREATE TABLE test2 (
        id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
        last VARCHAR( 25 ) NOT NULL, 
        first VARCHAR( 25 ) NOT NULL,
        last_kana VARCHAR( 25 ) NOT NULL,
        first_kana VARCHAR( 25 ) NOT NULL,
        sex VARCHAR( 5 ) NOT NULL, 
        zip INT( 7 ) NOT NULL, 
        pref VARCHAR( 10 ) NOT NULL, 
        city VARCHAR( 150 ) NOT NULL, 
        street VARCHAR( 100 ) NOT NULL,
        building VARCHAR( 50 ) NOT NULL,
        phone INT( 11 ) NOT NULL,
        mail VARCHAR( 50 ) NOT NULL,
        consultation_type VARCHAR( 50 ) NOT NULL,
        detail VARCHAR( 250 ) NOT NULL
        )";
      // SQL文のオブジェクトを取得
      $stmt = $this->dbh->prepare($sql);
      // 挿入する値が入った変数をexecuteにセットしてSQLを実行
      $stmt->execute();
    }
    catch(Exception $e)
    {
      echo '[Failed] formTableCreate()' .'「管理者に問い合わせください」' .'<br>';
      exit;
    }
    return $stmt;
  }
  
}
