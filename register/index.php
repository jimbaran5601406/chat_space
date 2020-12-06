<?php
$title = '会員登録';
require_once '../template/header.php';

session_start();

if(!empty($_POST)) {
	$name = h($_POST['name']);
	$email = h($_POST['email']);
	$password = h($_POST['password']);

	if($name === '') {
		$error_msg['name_required'] = "※ユーザー名は必ず入力してください";
	}
	if($email === '') {
		$error_msg['email_required'] = "※メールアドレスは必ず入力してください";
	}
	if($password === '') {
		$error_msg['password_required'] = "※パスワードは必ず入力してください";
	}
	if(mb_strlen($password) < 8) {
		$error_msg['password_min'] = "※パスワードは8文字以上入力してください";
	}

	if(empty($error_msg)) {
		$_SESSION['register'] = $_POST;
		header('Location: check.php');
		exit();
	}
}

if(isset($_GET['action']) && isset($_SESSION['register'])) {
	$_POST = $_SESSION['register'];
	$name = h($_POST['name']);
	$email = h($_POST['email']);
}

?>

<div class="container">
	<div class="row">
		<h2>会員登録</h2>
	</div>

	<div class="row">
		<form class="col s12" action="" method="post">
			<div class="row">
				<div class="input-field">
					<input class="validate" name="name" id="name" maxlength="255" type="text" value="<?= isset($name)? $name: ''; ?>">
					<label for="name">ユーザー名</label>
					<?php if(isset($error_msg['name_required'])): ?>
						<p class="red-text"><?= $error_msg['name_required'] ?></p>
					<?php endif;?>
				</div>
			</div>
			<div class="row">
				<div class="input-field">
					<input class="validate" name="email" id="email" maxlength="255" type="email" value="<?= isset($email)? $email: ''; ?>">
					<label for="email">メールアドレス</label>
					<?php if(isset($error_msg['email_required'])): ?>
						<p class="red-text"><?= $error_msg['email_required'] ?></p>
					<?php endif;?>
				</div>
			</div>
			<div class="row">
				<div class="input-field">
					<input class="validate" name="password" id="password" maxlength="30" type="password">
					<label for="password">パスワード</label>
					<?php if(isset($error_msg['password_required'])): ?>
						<p class="red-text"><?= $error_msg['password_required'] ?></p>
					<?php elseif(isset($error_msg['password_min'])): ?>
						<p class="red-text"><?= $error_msg['password_min'] ?></p>
					<?php endif;?>
				</div>
			</div>
			<div class="row">
				<button class="waves-effect waves-light btn" type="submit">確認</button>
			</div>
		</form>
	</div>
	<!-- row -->
</div>
<!-- container -->
</body>
</html>
