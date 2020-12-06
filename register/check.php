<?php
$title = '入力内容確認';
require_once '../template/header.php';

session_start();

if(!isset($_SESSION['register'])) {
	header('Location: index.php');
	exit();
}
?>

<div class="container center-align">
	<div class="row">
		<h2>入力内容確認</h2>
	</div>

	<div class="row">
		<form class="col s12" action="" method="post">
			<div class="row">
				<div class="col s6">
					ユーザー名
				</div>
				<div class="col s6">
					<?= h($_SESSION['register']['name']); ?>
				</div>
			</div>
			<div class="row">
				<div class="col s6">
					メールアドレス
				</div>
				<div class="col s6">
					<?= h($_SESSION['register']['email']); ?>
				</div>
			</div>
			<div class="row">
				<div class="col s6">
					パスワード
				</div>
				<div class="col s6">
					<?= h($_SESSION['register']['password']); ?>
				</div>
			</div>
			<div class="row">
				<input type="hidden" name="action" value="submit" />
				<a href="index.php?action=back" class="waves-effect waves-light btn">&laquo;&nbsp;戻る</a>
				<button class="waves-effect waves-light btn" type="submit">登録</a>
			</div>
		</form>
	</div>
</div>
</body>
</html>