<?php
$title = 'ホーム/Chat Space';
require_once './template/header.php';

$posts = fetch_all_posts($db, $user['id']);

if(!empty($_SESSION['post_success_msg'])) {
	$post_success_msg = $_SESSION['post_success_msg'];
	unset($_SESSION['post_success_msg']);
}
if(!empty($_POST)) {
	$message = h($_POST['message']);

	if($message === '') {
		$error_msg['message_required'] = '※投稿内容を入力しないと投稿できません';
	} else {
		$stmt = $db->prepare("INSERT INTO posts (message, user_id, created_at) VALUES(:message, :user_id, NOW())");
		$stmt->execute(array(
			':message' => $message,
			':user_id' => $user['id']
		));
		$_SESSION['post_success_msg'] = "投稿が完了しました！";

		// 二重送信防止
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
				<div class="input-field">
					<button class="waves-effect waves-light btn blue" type="submit">投稿</button>
					<button class="waves-effect waves-light btn grey" type="reset">リセット</button>
				</div>
			</div>
		</form>
	</div>
	<?php foreach($posts as $post): ?>
	<div class="row">
		<div class="col s12">
			<div class="card horizontal">
				<div class="card-image">
					<a href="./profile.php?user_id=<?= h($post['user_id']) ?>">
					<?php if(isset($post['photo'])): ?>
						<img src="./asset/images/<?= $post['photo'] ?>">
					<?php else: ?>
						<img src="./asset/images/default_user.png">
					<?php endif; ?>
					</a>
				</div>
				<div class="card-stacked">
					<div class="card-content">
						<p><?= h($post['message']) ?></p>
					</div>
					<div class="card-action">
						<span><?= $post['created_at'] ?></span>
						<a href="./detail.php?post_id=<?= h($post['id']) ?>">コメント</a>
						<a href="./profile.php?user_id=<?= h($post['user_id']) ?>"><?= $post['name'] ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<!-- container -->
</body>
</html>
