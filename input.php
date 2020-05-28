<?php
// バリデーション用クラス読み込み
require_once(__DIR__.'/app/FormValidateClass.php');
// 定数用クラス読み込み
require_once(__DIR__.'/app/ConstantClass.php');
// POSTされた値の格納
$posted = [];
$posted = $_POST;
// バリデーション用クラスのインスタンス化
$validate = new FormValidate($posted);
// エラー判定時の戻り値を変数格納
$form_errors = $validate->formErrors();
?>

<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="css/caution.css">
		<title>お問い合わせ（入力画面）</title>
	</head>
	<body>
		<h3>お問い合わせ（入力画面）</h3>
			<form action="" method="POST">
			<p>以下の項目は入力が必須（一部除く）となっております。</p>
<!--氏名-->
			<div>
				<label>■ 氏名</label><br>
				<input type="text" name="last" value="<?= $validate->escape('last'); ?>">
				<input type="text" name="first" value="<?= $validate->escape('first'); ?>">
				<div class="caution">
					<?= isset($form_errors['is_empty']['last']) ? $form_errors['is_empty']['last'] : null ?>
					<?= isset($form_errors['is_empty']['first']) ? $form_errors['is_empty']['first'] : null ?>
				</div>
			</div>

<!--フリガナ-->
			<div>
				<label>■ フリガナ</label><br>
				<input type="text" name="last_kana" value="<?= $validate->escape('last_kana'); ?>">
				<input type="text" name="first_kana" value="<?= $validate->escape('first_kana'); ?>">
				<div class="caution">
					<?= isset($form_errors['is_empty']['last_kana']) ? $form_errors['is_empty']['last_kana'] : null ?>
					<?= isset($form_errors['is_empty']['first_kana']) ? $form_errors['is_empty']['first_kana'] : null ?>
					<?= isset($form_errors['pregmatch']['last_kana']) ? $form_errors['pregmatch']['last_kana'] : null ?>
					<?= isset($form_errors['pregmatch']['first_kana']) ? $form_errors['pregmatch']['first_kana'] : null ?>
				</div>
			</div>

<!--性別-->
			<div>
				<label>■ 性別</label><br>
				<input type="radio" name="sex" id="" value="<?= Constant::SEX[0] ?>" <?= !empty($validate->escape('sex')) ? $validate->checkMark("sex", Constant::SEX[0]) : "checked" ?> >
				<?= Constant::SEX[0] ?>
				<input type="radio" name="sex" id="" value="<?= Constant::SEX[1] ?>" <?= !empty($validate->escape('sex')) ? $validate->checkMark("sex", Constant::SEX[1]) : null ?> >
				<?= Constant::SEX[1] ?>
				<div class="caution">
					<?= isset($form_errors['is_empty']['sex']) ? $form_errors['is_empty']['sex'] : null ?>
				</div>
			</div>

<!--郵便番号-->
			<div>
				<label>■ 郵便番号</label><br>
				<input type="text" name="zip" value="<?= $validate->escape('zip'); ?>">
				<div class="caution">
					<?= isset($form_errors['is_empty']['zip']) ? $form_errors['is_empty']['zip'] : null ?>
					<?= isset($form_errors['pregmatch']['zip']) ? $form_errors['pregmatch']['zip'] : null ?>
				</div>
			</div>

			<div>
				<label>■ 都道府県</label><br>
				<select name="pref">
					<option value="">
						選択してください
					</option>
					<?php foreach(Constant::PREF as $val) : ?>
						<option value="<?= $val?>" <?= $validate->escape('pref') === $val ? "selected" : "" ?>>
							<?= $val?>
						</option>
					<?php endforeach; ?>
				</select>
				<div class="caution">
					<?= isset($form_errors['is_empty']['pref']) ? $form_errors['is_empty']['pref'] : null ?>
				</div>
			</div>

<!--市区町村-->
			<div>
				<label>■ 市区町村</label><br>
				<input type="text" name="city" value="<?= $validate->escape('city'); ?>">
				<div class="caution">
					<?= isset($form_errors['is_empty']['city']) ? $form_errors['is_empty']['city'] : null ?>
				</div>
			</div>

<!--番地-->
			<div>
				<label>■ 番地</label><br>
				<input type="text" name="street" value="<?= $validate->escape('street'); ?>">
				<div class="caution">
					<?= isset($form_errors['is_empty']['street']) ? $form_errors['is_empty']['street'] : null ?>
				</div>
			</div>

<!--建物名（任意）-->
			<div>
				<label>■ 建物名 (任意)</label><br>
				<input type="text" name="building" value="<?= $validate->escape('building') ?>">
			</div>

<!--電話番号-->
			<div>
				<label>■ 電話番号</label><br>
				<input type="text" name="phone" value="<?= $validate->escape('phone'); ?>">
				<div class="caution">
					<?= isset($form_errors['is_empty']['phone']) ? $form_errors['is_empty']['phone'] : null ?>
					<?= isset($form_errors['pregmatch']['phone']) ? $form_errors['pregmatch']['phone'] : null ?>
				</div>
			</div>

<!--メールアドレス-->
			<div>
				<label>■ メールアドレス</label><br>
				<input type="mail" name="mail" value="<?= $validate->escape('mail'); ?>">
				<div class="caution">
					<?= isset($form_errors['is_empty']['mail']) ? $form_errors['is_empty']['mail'] : null ?>
					<?= isset($form_errors['pregmatch']['mail']) ? $form_errors['pregmatch']['mail'] : null ?>
				</div>
			</div>

<!--確認用メールアドレス-->
			<div>
				<label>■ 確認用メールアドレス</label><br>
				<input type="mail" name="cf_mail" value="<?= $validate->escape('cf_mail'); ?>">
				<div class="caution">
					<?= isset($form_errors['is_empty']['cf_mail']) ? $form_errors['is_empty']['cf_mail'] : null ?>
					<?= isset($form_errors['pregmatch']['cf_mail']) ? $form_errors['pregmatch']['cf_mail'] : null ?>
					<?= isset($form_errors['equality']['cf_mail']) ? $form_errors['equality']['cf_mail'] : null ?>
				</div>
			</div>

<!--ご相談種別-->
			<div>
				<label>■ ご相談種別</label><br>
				<label>
					<?php foreach(Constant::CONSULTATION_TYPE as $val) : ?>
						<input type="radio" name="consultation_type" value="<?= $val ?>" <?= $validate->escape('consultation_type') === $val ? "checked" : null ?> >
						<?= $val ?>
					<?php endforeach; ?>
				</label>
				<div class="caution">
					<?= isset($form_errors['is_empty']['consultation_type']) ? $form_errors['is_empty']['consultation_type'] : null ?>
				</div>
			</div>

<!--お問い合わせ内容-->
			<div>
				<label>■ お問い合わせ内容</label><br>
				<textarea name="dtl" value="" cols="30" rows="5" wrap="soft"><?= $validate->escape('dtl'); ?></textarea>
				<div class="caution">
					<?= isset($form_errors['is_empty']['dtl']) ? $form_errors['is_empty']['dtl'] : null ?>
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
	</body>
</html>