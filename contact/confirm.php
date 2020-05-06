<?php
// バリデーション用クラス読み込み
require_once(__DIR__.'/app/ValidateClass.php');
// リダイレクト処理
$page = new PageAccess();
// ページアクセスの直打ち防止
$page->redirect();
// POSTされた値の格納
$posted = $_POST;
// バリデーション用クラスのインスタンス化
$validate = new Validate($posted);
?>

<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="Ctrl/caution.css">
		<title>お問い合わせ（確認画面）</title>
	</head>
	<body>
		<h3>お問い合わせ（確認画面）</h3>
		<form action="input.php" name="" method="POST">


<!--氏名-->
			<div>
				<label>■ 氏名</label><br>
				<?= $validate->purify('last'); ?> <?= $validate->purify('first'); ?>
				</div>
			</div>

<!--フリガナ-->
			<div>
				<label>■ フリガナ</label><br>
				<?= $validate->purify('last_kana'); ?> <?= $validate->purify('first_kana'); ?>
				</div>
			</div>

<!--性別-->
			<div>
				<label>■ 性別</label><br>
				<?= $validate->purify('sex'); ?>
			</div>

<!--郵便番号-->
			<div>
				<label>■ 郵便番号</label><br>
				<?= $validate->purify('zip'); ?>
			</div>

<!--都道府県-->
			<div>
				<label>■ 都道府県</label><br>
				<?= $validate->purify('pref'); ?>
				</div>
			</div>

<!--市区町村-->
			<div>
				<label>■ 市区町村</label><br>
				<?= $validate->purify('city'); ?>
			</div>

<!--番地-->
			<div>
				<label>■ 番地</label><br>
				<?= $validate->purify('street'); ?>
				</div>
			</div>

<!--建物名（任意）-->
			<div>
				<label>■ 建物名 (任意)</label><br>
				<?= $validate->purify('building') ?>
			</div>

<!--電話番号-->
			<div>
				<label>■ 電話番号</label><br>
				<?= $validate->purify('phone'); ?>
			</div>

<!--メールアドレス-->
			<div>
				<label>■ メールアドレス</label><br>
				<?= $validate->purify('mail'); ?>
			</div>

<!--確認用メールアドレス-->
			<div>
				<label>■ 確認用メールアドレス</label><br>
				<?= $validate->purify('cf_mail'); ?>
			</div>

<!--ご相談種別-->
			<div>
				<label>■ ご相談種別</label><br>
				<?= $validate->purify('consultation_type'); ?>
			</div>

<!--お問い合わせ内容-->
			<div>
				<label>■ お問い合わせ内容</label><br>
				<?= $validate->purify('dtl'); ?>
			</div>

			<?php foreach ($posted as $key => $val) :?>
				<input type="hidden" name="<?= $key ?>" value="<?= $val ?>">
			<?php endforeach; ?>
			<button type="submit" name="submit" value="back">戻る</button>
		</form>

		<form action="thanks.php" name="" method="POST">
			<?php foreach ($posted as $key => $val) :?>
				<input type="hidden" name="<?= $key ?>" value="<?= $val ?>">
			<?php endforeach; ?>
			<button type="submit" name="submit" value="confirm">送信する</button>
		</form>
	</body>
</html>