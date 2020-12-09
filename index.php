<?php
$title = 'ホーム/Chat Space';
require_once './template/header.php';

if(!empty($_SESSION['post_success_msg'])) {
	$post_success_msg = $_SESSION['post_success_msg'];
	unset($_SESSION['post_success_msg']);
}
if(!empty($_POST)) {
	$message = h($_POST['message']);

	if($message === '') {
		$error_msg['message_required'] = '※投稿内容を入力しないと投稿できません';
	} else {
		$stmt = $db->prepare("INSERT INTO posts (message, user_id) VALUES(:message, :user_id)");
		$stmt->execute(array(
			':message' => $message,
			':user_id' => $user['id']
		));
		$_SESSION['post_success_msg'] = "投稿が完了しました！";

		header('Location: index.php');
		exit();
	}
}
?>

<div class="container">
	<div class="row">
		<form class="col s12" action="" method="post">
			<div class="row">
				<?php if(isset($post_success_msg)): ?>
					<p class="session-success-msg"><?= $post_success_msg ?></p>
				<?php endif;?>
            </div>
			<div class="row">
				<div class="input-field">
					<textarea name="message" id="message" class="materialize-textarea" data-length="300"></textarea>
            		<label for="message">投稿内容</label>
					<?php if(isset($error_msg['message_required'])): ?>
						<p class="red-text"><?= $error_msg['message_required'] ?></p>
					<?php endif;?>
				</div>
			</div>
			<div class="row">
				<button class="waves-effect waves-light btn blue" type="submit">投稿</button>
				<button class="waves-effect waves-light btn grey" type="reset">リセット</button>
			</div>
		</form>
	</div>
	<!-- row -->
</div>
<!-- container -->
</body>
</html>
