<?php
// 定数用クラス読み込み
require_once(__DIR__.'/ConstantClass.php');
$data[] = $posted;

try {
  $dbh = new PDO(Constant::DB_CONNECT["DB_DSN"], Constant::DB_CONNECT["DB_USER"], Constant::DB_CONNECT["DB_PASS"]);
  // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


  $sql = 'INSERT INTO "form_test" (
    "last",
    "first",
    "last_kana",
    "first_kana",
    "sex",
    "zip",
    "pref",
    "city",
    "street",
    "building",
    "phone",
    "mail",
    "consultation_type",
    "dtl"
    )
    VALUE (
      "?",
      "?",
      "?",
      "?",
      "?",
      "?",
      "?",
      "?",
      "?",
      "?",
      "?",
      "?",
      "?",
      "?"
    )';
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  $dbh = null;
  
  echo "$data"."を登録しました。";
}
catch(Exception $e)
{
  echo "登録ができませんでした" ."<br>";
  exit;
}

