#TASK 5　概要
<hr> 

##■ルール
PHPコード解説などの参考資料以外は何も見ないで作ること。当然コピペしたものはNGです。

##■内容
PHP及びHTMLでの問合せフォーム構築（管理画面、DB利用なし、フレームワーク利用なし）

##■仕様
##項目
名前（姓）
名前（メイ）
フリガナ（セイ）
フリガナ（メイ）
住所
都道府県
市区町村
番地
建物名
電話番号
メールアドレス
確認用メールアドレス
お問い合わせ内容

##バリデーション
・建物名以外は必須項目
・フリガナはカタカナ
・住所はハイフン抜き半角数字7桁
・電話番号はハイフン抜き半角数字9~11桁
・メールアドレスはメールアドレス形式
・確認用メールアドレスとメールアドレスが一致

##その他仕様
1. バリデーションにかかった場合は項目の下にエラーを表示
2. 戻るボタンでも前回入力内容が保持されていること
3. URLの直叩き対策（入力->確認->完了の遷移順以外は入力画面にリダイレクト）
4. CSSでのデザインは不要

<hr>
##■チェックリスト

##実行環境
PHP Version 7.2.27
##ファイル
- 画面(処理)側
    - input.php
    - confirm.php
    - thanks.php

<!-- - ログイン画面 -->

<!-- - 処理
    - input.php
    - confirm.php
    - thanks.php -->


##項目
####input.php
    <form class="" action="confirm.php" method="post">
		<label for="" class=""> </label><br>
			<div class="">
			<input type=" " class="" name=" " placeholder="" value="">
			</div>
		<button type="submit" class="btn btn-light" name="submit">確認</button>
	</form>
##バリデーション
・建物名以外は必須項目

・フリガナはカタカナ

・住所はハイフン抜き半角数字7桁

・電話番号はハイフン抜き半角数字9~11桁

・メールアドレスはメールアドレス形式

・確認用メールアドレスとメールアドレスが一致

##その他仕様
1. バリデーションにかかった場合は項目の下にエラーを表示

2. 戻るボタンでも前回入力内容が保持されていること

3. URLの直叩き対策（入力->確認->完了の遷移順以外は入力画面にリダイレクト）

######質問
PHPの宣言を "<?=" と書くとPOST型変数に渡されたデータが中身は空だが無条件に１が表示されるようになった。<?php と書き直すと直ったが、中で何が起こっていたのかがわからない
<?php isset($_POST['posted']['last']) ?? print "入力は必須です". '</br>' ?>


<!-- 4. CSSでのデザインは不要 -->