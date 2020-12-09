<?php
$title = '登録完了';
require_once '../template/header.php';

session_start();

if(!isset($_SESSION['register'])) {
	header('Location: index.php');
	exit();
}

unset($_SESSION['register']);

?>

<div class="container center-align">
	<div class="row">
        <h2>登録完了</h2>
	</div>

	<div class="row">
		<div class="col s12">
            <a class="waves-effect waves-light btn-large"   href="../login.php">ログイン</a>
        </div>
	</div>
</div>
</body>
</html>
