<?php
// バリデーション用クラス読み込み
require_once(__DIR__.'/app/ValidateClass.php');
// 定数用クラス読み込み
require_once(__DIR__.'/app/ConstantClass.php');
// POSTされた値の格納
$posted = [];
$posted = $_POST;
// バリデーション用クラスのインスタンス化
$validate = new Validate($posted);
// エラー判定時の戻り値を変数格納
$errors = $validate->errors();
?>

<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="app/caution.css">
		<title>お問い合わせ（入力画面）</title>
	</head>
	<body>
		<h3>お問い合わせ（入力画面）</h3>
			<form action="" method="POST">
			<p>以下の項目は入力が必須（一部除く）となっております。</p>
<!--氏名-->
			<div>
				<label>■ 氏名</label><br>
				<input type="text" name="last" value="<?= $validate->purify('last'); ?>">
				<input type="text" name="first" value="<?= $validate->purify('first'); ?>">
				<div class="caution">
					<?= isset($errors['is_empty']['last']) ? $errors['is_empty']['last'] : null ?>
					<?= isset($errors['is_empty']['first']) ? $errors['is_empty']['first'] : null ?>
				</div>
			</div>

<!--フリガナ-->
			<div>
				<label>■ フリガナ</label><br>
				<input type="text" name="last_kana" value="<?= $validate->purify('last_kana'); ?>">
				<input type="text" name="first_kana" value="<?= $validate->purify('first_kana'); ?>">
				<div class="caution">
					<?= isset($errors['is_empty']['last_kana']) ? $errors['is_empty']['last_kana'] : null ?>
					<?= isset($errors['is_empty']['first_kana']) ? $errors['is_empty']['first_kana'] : null ?>
					<?= isset($errors['pregmatch']['last_kana']) ? $errors['pregmatch']['last_kana'] : null ?>
					<?= isset($errors['pregmatch']['first_kana']) ? $errors['pregmatch']['first_kana'] : null ?>
				</div>
			</div>

<!--性別-->
			<div>
				<label>■ 性別</label><br>
				<input type="radio" name="sex" id="" value="<?= Constant::SEX[0] ?>" <?= !empty($validate->purify('sex')) ? $validate->checkMark("sex", Constant::SEX[0]) : "checked" ?> >
				<?= Constant::SEX[0] ?>
				<input type="radio" name="sex" id="" value="<?= Constant::SEX[1] ?>" <?= !empty($validate->purify('sex')) ? $validate->checkMark("sex", Constant::SEX[1]) : null ?> >
				<?= Constant::SEX[1] ?>
				<div class="caution">
					<?= isset($errors['is_empty']['sex']) ? $errors['is_empty']['sex'] : null ?>
				</div>
			</div>

<!--郵便番号-->
			<div>
				<label>■ 郵便番号</label><br>
				<input type="text" name="zip" value="<?= $validate->purify('zip'); ?>">
				<div class="caution">
					<?= isset($errors['is_empty']['zip']) ? $errors['is_empty']['zip'] : null ?>
					<?= isset($errors['pregmatch']['zip']) ? $errors['pregmatch']['zip'] : null ?>
				</div>
			</div>

			<div>
				<label>■ 都道府県</label><br>
				<select name="pref">
					<option value="">
						選択してください
					</option>
					<?php foreach(Constant::PREF as $val) : ?>
						<option value="<?= $val?>" <?= $validate->purify('pref') === $val ? "selected" : "" ?>>
							<?= $val?>
						</option>
					<?php endforeach; ?>
				</select>
				<div class="caution">
					<?= isset($errors['is_empty']['pref']) ? $errors['is_empty']['pref'] : null ?>
				</div>
			</div>

<!--市区町村-->
			<div>
				<label>■ 市区町村</label><br>
				<input type="text" name="city" value="<?= $validate->purify('city'); ?>">
				<div class="caution">
					<?= isset($errors['is_empty']['city']) ? $errors['is_empty']['city'] : null ?>
				</div>
			</div>

<!--番地-->
			<div>
				<label>■ 番地</label><br>
				<input type="text" name="street" value="<?= $validate->purify('street'); ?>">
				<div class="caution">
					<?= isset($errors['is_empty']['street']) ? $errors['is_empty']['street'] : null ?>
				</div>
			</div>

<!--建物名（任意）-->
			<div>
				<label>■ 建物名 (任意)</label><br>
				<input type="text" name="building" value="<?= $validate->purify('building') ?>">
			</div>

<!--電話番号-->
			<div>
				<label>■ 電話番号</label><br>
				<input type="text" name="phone" value="<?= $validate->purify('phone'); ?>">
				<div class="caution">
					<?= isset($errors['is_empty']['phone']) ? $errors['is_empty']['phone'] : null ?>
					<?= isset($errors['pregmatch']['phone']) ? $errors['pregmatch']['phone'] : null ?>
				</div>
			</div>

<!--メールアドレス-->
			<div>
				<label>■ メールアドレス</label><br>
				<input type="mail" name="mail" value="<?= $validate->purify('mail'); ?>">
				<div class="caution">
					<?= isset($errors['is_empty']['mail']) ? $errors['is_empty']['mail'] : null ?>
					<?= isset($errors['pregmatch']['mail']) ? $errors['pregmatch']['mail'] : null ?>
				</div>
			</div>

<!--確認用メールアドレス-->
			<div>
				<label>■ 確認用メールアドレス</label><br>
				<input type="mail" name="cf_mail" value="<?= $validate->purify('cf_mail'); ?>">
				<div class="caution">
					<?= isset($errors['is_empty']['cf_mail']) ? $errors['is_empty']['cf_mail'] : null ?>
					<?= isset($errors['pregmatch']['cf_mail']) ? $errors['pregmatch']['cf_mail'] : null ?>
					<?= isset($errors['equality']['cf_mail']) ? $errors['equality']['cf_mail'] : null ?>
				</div>
			</div>

<!--ご相談種別-->
			<div>
				<label>■ ご相談種別</label><br>
				<label>
					<?php foreach(Constant::CONSULTATION_TYPE as $val) : ?>
						<input type="radio" name="consultation_type" value="<?= $val ?>" <?= $validate->purify('consultation_type') === $val ? "checked" : null ?> >
						<?= $val ?>
					<?php endforeach; ?>
				</label>
				<div class="caution">
					<?= isset($errors['is_empty']['consultation_type']) ? $errors['is_empty']['consultation_type'] : null ?>
				</div>
			</div>

<!--お問い合わせ内容-->
			<div>
				<label>■ お問い合わせ内容</label><br>
				<textarea name="dtl" value="" cols="30" rows="5" wrap="soft"><?= $validate->purify('dtl'); ?></textarea>
				<div class="caution">
					<?= isset($errors['is_empty']['dtl']) ? $errors['is_empty']['dtl'] : null ?>
				</div>
			</div>

			<button type="submit" name="submit" value="input_reload" formaction="input.php">確認</button>

			<?php if(isset($_POST['submit'])) : ?>
				<?php if(empty(array_filter($errors))) : ?>
					<button type="submit" name="submit" value="input" formaction="confirm.php">すすむ</button>
				<?php endif; ?>
			<?php endif; ?>
		</form>
	</body>
</html>