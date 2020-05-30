<?php

// バリデーション用クラス読み込み（問い合わせフォーム用）
require_once '../app/FormValidateClass.php';
// 定数用クラス読み込み
require_once '../app/ConstantClass.php';

// POSTされた値の格納
$posted = [];
$posted = $_POST;

// バリデーションをインスタンス化（問い合わせフォーム用）
$form_validate = new FormValidate($posted);
// エラー判定の戻り値を変数格納（問い合わせフォーム用）
$form_errors = $form_validate->formErrors();

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
		<section>
			<h3>お問い合わせ</h3>
			<form action="" method="POST">
				<p class="">以下の項目を入力してください。項目の記載は<span style="color:red">必須</span>（建物名を除く）となります。</p>

				<!--氏名-->
				<div class="input_box">
					<label>■ 氏名<span class="require">必須</span></label><br>
					<input type="text" name="last" value="<?= $form_validate->escape('last'); ?>">
					<input type="text" name="first" value="<?= $form_validate->escape('first'); ?>">
					<div class="caution">
						<?= isset($form_errors['empty']['last']) ? $form_errors['empty']['last'] : null ?>
						<?= isset($form_errors['empty']['first']) ? $form_errors['empty']['first'] : null ?>
					</div>
				</div>
				<!--フリガナ-->
				<div class="input_box">
					<label>■ フリガナ<span class="require">必須</span></label><br>
					<input type="text" name="last_kana" value="<?= $form_validate->escape('last_kana'); ?>">
					<input type="text" name="first_kana" value="<?= $form_validate->escape('first_kana'); ?>">
					<div class="caution">
						<?= isset($form_errors['empty']['last_kana']) ? $form_errors['empty']['last_kana'] : null ?>
						<?= isset($form_errors['empty']['first_kana']) ? $form_errors['empty']['first_kana'] : null ?>
						<?= isset($form_errors['pregmatch']['last_kana']) ? $form_errors['pregmatch']['last_kana'] : null ?>
						<?= isset($form_errors['pregmatch']['first_kana']) ? $form_errors['pregmatch']['first_kana'] : null ?>
					</div>
				</div>

	<!--性別-->
				<div class="input_box">
					<label>■ 性別<span class="require">必須</span></label><br>
					<input type="radio" name="sex" id="" value="<?= Constant::SEX[0] ?>" <?= !empty($form_validate->escape('sex')) ? $form_validate->checkMark("sex", Constant::SEX[0]) : "checked" ?> >
					<?= Constant::SEX[0] ?>
					<input type="radio" name="sex" id="" value="<?= Constant::SEX[1] ?>" <?= !empty($form_validate->escape('sex')) ? $form_validate->checkMark("sex", Constant::SEX[1]) : null ?> >
					<?= Constant::SEX[1] ?>
					<div class="caution">
						<?= isset($form_errors['empty']['sex']) ? $form_errors['empty']['sex'] : null ?>
					</div>
				</div>

	<!--郵便番号-->
				<div class="input_box">
					<label>■ 郵便番号<span class="require">必須</span></label><br>
					<input type="text" name="zip" value="<?= $form_validate->escape('zip'); ?>">
					<div class="caution">
						<?= isset($form_errors['empty']['zip']) ? $form_errors['empty']['zip'] : null ?>
						<?= isset($form_errors['pregmatch']['zip']) ? $form_errors['pregmatch']['zip'] : null ?>
					</div>
				</div>

				<div class="input_box">
					<label>■ 都道府県<span class="require">必須</span></label><br>
					<select name="pref">
						<option value="">
							選択してください
						</option>
						<?php foreach(Constant::PREF as $val) : ?>
							<option value="<?= $val?>" <?= $form_validate->escape('pref') === $val ? "selected" : "" ?>>
								<?= $val?>
							</option>
						<?php endforeach; ?>
					</select>
					<div class="caution">
						<?= isset($form_errors['empty']['pref']) ? $form_errors['empty']['pref'] : null ?>
					</div>
				</div>

	<!--市区町村-->
				<div class="input_box">
					<label>■ 市区町村<span class="require">必須</span></label><br>
					<input type="text" name="city" value="<?= $form_validate->escape('city'); ?>">
					<div class="caution">
						<?= isset($form_errors['empty']['city']) ? $form_errors['empty']['city'] : null ?>
					</div>
				</div>

	<!--番地-->
				<div class="input_box">
					<label>■ 番地<span class="require">必須</span></label><br>
					<input type="text" name="street" value="<?= $form_validate->escape('street'); ?>">
					<div class="caution">
						<?= isset($form_errors['empty']['street']) ? $form_errors['empty']['street'] : null ?>
					</div>
				</div>

	<!--建物名（任意）-->
				<div class="input_box">
					<label>■ 建物名<span class="optional">任意</span></label><br>
					<input type="text" name="building" value="<?= $form_validate->escape('building') ?>">
				</div>

	<!--電話番号-->
				<div class="input_box">
					<label>■ 電話番号<span class="require">必須</span></label><br>
					<input type="text" name="phone" value="<?= $form_validate->escape('phone'); ?>">
					<div class="caution">
						<?= isset($form_errors['empty']['phone']) ? $form_errors['empty']['phone'] : null ?>
						<?= isset($form_errors['pregmatch']['phone']) ? $form_errors['pregmatch']['phone'] : null ?>
					</div>
				</div>

	<!--メールアドレス-->
				<div class="input_box">
					<label>■ メールアドレス<span class="require">必須</span></label><br>
					<input type="mail" name="mail" value="<?= $form_validate->escape('mail'); ?>">
					<div class="caution">
						<?= isset($form_errors['empty']['mail']) ? $form_errors['empty']['mail'] : null ?>
						<?= isset($form_errors['pregmatch']['mail']) ? $form_errors['pregmatch']['mail'] : null ?>
					</div>
				</div>

	<!--確認用メールアドレス-->
				<div class="input_box">
					<label>■ 確認用メールアドレス<span class="require">必須</span></label><br>
					<input type="mail" name="cf_mail" value="<?= $form_validate->escape('cf_mail'); ?>">
					<div class="caution">
						<?= isset($form_errors['empty']['cf_mail']) ? $form_errors['empty']['cf_mail'] : null ?>
						<?= isset($form_errors['pregmatch']['cf_mail']) ? $form_errors['pregmatch']['cf_mail'] : null ?>
						<?= isset($form_errors['equality']['cf_mail']) ? $form_errors['equality']['cf_mail'] : null ?>
					</div>
				</div>

	<!--ご相談種別-->
				<div class="input_box">
					<label>■ ご相談種別<span class="require">必須</span></label><br>
					<label>
						<?php foreach(Constant::CONSULTATION_TYPE as $val) : ?>
							<input type="radio" name="consultation_type" value="<?= $val ?>" <?= $form_validate->escape('consultation_type') === $val ? "checked" : null ?> >
							<?= $val ?>
						<?php endforeach; ?>
					</label>
					<div class="caution">
						<?= isset($form_errors['empty']['consultation_type']) ? $form_errors['empty']['consultation_type'] : null ?>
					</div>
				</div>

	<!--お問い合わせ内容-->
				<div class="input_box">
					<label>■ お問い合わせ内容<span class="require">必須</span></label><br>
					<textarea name="dtl" value="" cols="30" rows="5" wrap="soft"><?= $form_validate->escape('dtl'); ?></textarea>
					<div class="caution">
						<?= isset($form_errors['empty']['dtl']) ? $form_errors['empty']['dtl'] : null ?>
					</div>
				</div>
				<div>
					<button type="submit" name="submit" value="input_reload" formaction="input.php">確認</button>
				</div>
				<div>
					<?php if(isset($posted['submit'])&& $posted['submit'] === "input_reload") : ?>
						<?php if(empty(array_filter($form_errors))) : ?>
							<button type="submit" name="submit" value="input" formaction="confirm.php">すすむ</button>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</form>

		</section>
	</body>
</html>