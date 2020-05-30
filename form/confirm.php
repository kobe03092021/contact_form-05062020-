<?php

// POST経由でなければ、リダイレクト（ディレクトリ内最初のページへ）
if ( $_SERVER['REQUEST_METHOD'] !== 'POST') {
	header("location:input.php");
	exit;
	}

// バリデーション用クラス（問い合わせフォーム用）
require_once('../app/FormValidateClass.php');

// POSTされた値の格納
$posted = $_POST;
// バリデーションをインスタンス化（問い合わせフォーム用）
$form_validate = new FormValidate($posted);

?>

<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="../css/common.css">
		<title>お問い合わせ</title>
	</head>
	<body>
		<h3>お問い合わせ</h3>

		<form action="input.php" name="" method="POST">
			<!--  氏名  -->
			<div>
				<label>■ 氏名<span class="require">必須</span></label><br>
				<?= $form_validate->escape('last'); ?> <?= $form_validate->escape('first'); ?>
			</div>
			<!--  フリガナ  -->
			<div>
				<label>■ フリガナ<span class="require">必須</span></label><br>
				<?= $form_validate->escape('last_kana'); ?> <?= $form_validate->escape('first_kana'); ?>
			</div>
			<!--  性別  -->
			<div>
				<label>■ 性別<span class="require">必須</span></label><br>
				<?= $form_validate->escape('sex'); ?>
			</div>
			<!--  郵便番号  -->
			<div>
				<label>■ 郵便番号<span class="require">必須</span></label><br>
				<?= $form_validate->escape('zip'); ?>
			</div>
			<!--  都道府県  -->
			<div>
				<label>■ 都道府県<span class="require">必須</span></label><br>
				<?= $form_validate->escape('pref'); ?>
			</div>
			<!--  市区町村  -->
			<div>
				<label>■ 市区町村<span class="require">必須</span></label><br>
				<?= $form_validate->escape('city'); ?>
			</div>
			<!--  番地  -->
			<div>
				<label>■ 番地<span class="require">必須</span></label><br>
				<?= $form_validate->escape('street'); ?>
			</div>
			<!--  建物名  -->
			<div>
				<label>■ 建物名<span class="optional">任意</span></label><br>
				<?= $form_validate->escape('building') ?>
			</div>
			<!--  電話番号  -->
			<div>
				<label>■ 電話番号<span class="require">必須</span></label><br>
				<?= $form_validate->escape('phone'); ?>
			</div>
			<!--  メールアドレス  -->
			<div>
				<label>■ メールアドレス<span class="require">必須</span></label><br>
				<?= $form_validate->escape('mail'); ?>
			</div>
			<!--  確認用メールアドレス  -->
			<div>
				<label>■ 確認用メールアドレス<span class="require">必須</span></label><br>
				<?= $form_validate->escape('cf_mail'); ?>
			</div>
			<!--  ご相談種別  -->
			<div>
				<label>■ ご相談種別<span class="require">必須</span></label><br>
				<?= $form_validate->escape('consultation_type'); ?>
			</div>
			<!--  お問い合わせ内容  -->
			<div>
				<label>■ お問い合わせ内容<span class="require">必須</span></label><br>
				<?= $form_validate->escape('dtl'); ?>
			</div>
			<div>
				<?php foreach ($posted as $key => $val) :?>
					<input type="hidden" name="<?= $key ?>" value="<?= $val ?>">
				<?php endforeach; ?>
				<button type="submit" name="submit" value="back">戻る</button>
			</div>
		</form>

		<form action="thanks.php" name="" method="POST">
			<div>
				<?php foreach ($posted as $key => $val) :?>
					<input type="hidden" name="<?= $key ?>" value="<?= $val ?>">
				<?php endforeach; ?>
				<button type="submit" name="submit" value="confirm">送信する</button>
			</div>
		</form>

	</body>
</html>