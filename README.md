概要
<hr> 
##■内容
PHP及びHTMLでの問合せフォーム構築（フレームワーク利用なし）

##実行環境
PHP Version 7.1 以上
##ファイル
　- 画面側
    - input.php
    - confirm.php
    - thanks.php
　- 処理側
    - app/ ConstantClass.php
    - app/ DBClass.php
    - app/ SendMailClass.php
    - app/ ValidateClass.php
    - app/ caution.css

　- データベース
    - 接続情報
        - contact_form.test 
　- データベース
    - テーブル
        - contact_form.test 

##バリデーション処理
・建物名以外は必須項目
・フリガナはカタカナ
・住所はハイフン抜き半角数字7桁
・電話番号はハイフン抜き半角数字9~11桁
・メールアドレスはメールアドレス形式
・確認用メールアドレスとメールアドレスが一致

##その他仕様
1. バリデーションのエラー時は項目の下にエラー内容を表示
2. ページ遷移をする上で入力内容の保持がされる
3. URLの直叩き対策（入力 -> 確認 -> 完了の流れ以外は入力画面にリダイレクト）
<hr>


<!-- - ログイン画面 -->

<!-- - 処理
    - input.php
    - confirm.php
    - thanks.php -->