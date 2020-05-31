## ポートフォリオ概要
PHP及びHTMLでの問合せフォーム構築～管理画面

#### 内容
フレームワーク利用：なし
制作期間：おおよそ1ヶ月
#### 構築物
・問い合わせフォーム
・管理画面
・新規管理ユーザ登録画面
####  実行環境
PHP Version 7.1 以上
#### ファイル構成
contact配下
- 問い合わせフォーム (form)
        - input.php
        - confirm.php
        - thanks.php
- 処理側 (app)
        - FormDBClass.php
        - FormValidateClass.php (バリデーション)
        - UserDBClass.php
        - UserValidateClass.php (バリデーション)
        - SendMailClass.php
        - ConstantClass.php (定数)
- 管理画面 (manage)
        - login.php
        - console.php
        - check.php
        - complete.php
- 新規ユーザ登録画面 (registration)
        - input.php
        - confirm.php
        - upload.php
- CSS (css)
        - css/ common.css
- 設定ファイル (settings)
        - admin_account.php
- 概要説明
        - readme.md
#### データベース構造
###### データベース (1)
contact_form
###### テーブル (2)
admin_test (管理画面ユーザ用)
| id | user_name | user_password |
| :--- | :---: | ---: |
|  | admin | password |

test (問い合わせ結果)
| id | last | first | last_kana | first_kana | sex | zip | pref | city | street | building | phone | mail | consultation_type | detail |
| :--- | :---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: |


#### 共通のバリデーション処理
・建物名以外は必須項目
・フリガナはカタカナ
・住所はハイフン抜き半角数字7桁
・電話番号はハイフン抜き半角数字9~11桁
・メールアドレスはメールアドレス形式
・確認用メールアドレスとメールアドレスが一致

#### その他共通の基本仕様
・ バリデーションのエラー時は項目の下にエラー内容を表示
・ ページ遷移をする上で入力内容の保持がされる
・ URLの直叩き対策（入力 -> 確認 -> 完了の流れ以外は入力画面にリダイレクト）
<hr>


<!-- - ログイン画面 -->

<!-- - 処理
    - input.php
    - confirm.php
    - thanks.php -->