<?php
$title = 'ログイン/Chat Space';
require_once './template/header.php';

session_start();

if(!empty($_COOKIE['auto_login'])) {
	$stmt = $db->prepare('SELECT * FROM users WHERE auto_login_key=:auto_login_key');
	$stmt->execute(array(':auto_login_key' => $_COOKIE['auto_login']));
	$user = $stmt->fetch();

	if($user['auto_login_key'] === $_COOKIE['auto_login']) {
		setup_auto_login($db, $user['id']);
		header('Location: index.php');
		exit();
	}
}


if(!empty($_POST)) {
	$email = h($_POST['email']);
	$password = h($_POST['password']);

	if($email === '') {
		$error_msg['email_required'] = "※メールアドレスは必ず入力してください";
	}
	if($password === '') {
		$error_msg['password_required'] = "※パスワードは必ず入力してください";
	}

	if(empty($error_msg)) {
		$stmt = $db->prepare("SELECT * FROM users WHERE email=:email");
		$stmt->execute(array(':email' => $email));
		$user = $stmt->fetch();

		if($user !== false) {
			if(password_verify($password, $user['password'])) {
				$_SESSION['id'] = $user['id'];
				$_SESSION['login_time'] = time();

				if(isset($_POST['auto_login']) && $_POST['auto_login'] === 'checked') {
					setup_auto_login($db, $user['id']);
				}

            	header('Location: index.php');
				exit();
			} else {
				$error_msg['login_failed'] = "※メールアドレスまたはパスワードが間違っています";
			}
		}
	}
}



?>

<div class="container">
	<div class="row">
		<h2>ログイン</h2>
	</div>

	<div class="row">
		<form class="col s12" action="" method="post">
			<div class="row">
                <?php if(isset($error_msg['login_failed'])): ?>
                    <p class="red-text"><?= $error_msg['login_failed'] ?></p>
                <?php endif;?>
            </div>
			<div class="row">
				<div class="input-field">
					<input class="validate" name="email" id="email" maxlength="255" type="email" value="<?= isset($email)? $email: ''; ?>">
					<label for="email">メールアドレス</label>
					<?php if(isset($error_msg['email_required'])): ?>
						<p class="red-text"><?= $error_msg['email_required'] ?></p>
					<?php endif;?>
					<?php if(isset($error_msg['email_duplicated'])): ?>
						<p class="red-text"><?= $error_msg['email_duplicated'] ?></p>
					<?php endif;?>
				</div>
			</div>
			<div class="row">
				<div class="input-field">
					<input class="validate" name="password" id="password" maxlength="30" type="password" value="<?= isset($password)? $password: ''; ?>">
					<label for="password">パスワード</label>
					<?php if(isset($error_msg['password_required'])): ?>
						<p class="red-text"><?= $error_msg['password_required'] ?></p>
					<?php elseif(isset($error_msg['password_min'])): ?>
						<p class="red-text"><?= $error_msg['password_min'] ?></p>
					<?php endif;?>
				</div>
				<label>
					<input name="auto_login" type="checkbox" value="checked">
					<span>次回から自動でログイン</span>
				</label>
			</div>
			<div class="row">
				<button class="waves-effect waves-light btn" type="submit">ログイン</button>
			</div>
			<div class="row">
				<a href="./register" class="not-registered">
					<span data-text="まだ登録がお済みでない方はコチラ">まだ登録がお済みでない方はコチラ</span>
				</a>
			</div>
		</form>
	</div>
	<!-- row -->
</div>
<!-- container -->
</body>
</html>
