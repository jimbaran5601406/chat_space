<?php
$title = '投稿詳細/Chat Space';
require_once './template/header.php';

$post_id = h($_GET['post_id']);
$post = fetch_post($db, $_GET['post_id']);
$reply_posts = fetch_all_reply_posts($db, $_GET['post_id']);

if(!empty($_SESSION['after_action_msg'])) {
	$after_action_msg = $_SESSION['after_action_msg'];
	unset($_SESSION['after_action_msg']);
}
if(!empty($_POST)) {
	$message = h($_POST['message']);

	if($message === '') {
		$error_msg['message_required'] = '※投稿内容を入力しないと投稿できません';
	} else {
		$stmt = $db->prepare("INSERT INTO posts (message, reply_message_id, user_id, created_at) VALUES(:message, :reply_message_id, :user_id, NOW())");
		$stmt->execute(array(
			':message' => $message,
			':reply_message_id' => h($_POST['reply_message_id']),
			':user_id' => $user['id']
		));
		$_SESSION['after_action_msg'] = "投稿が完了しました！";

		// 二重送信防止
		header('Location: detail.php'.'?post_id='.h($_POST['reply_message_id']));
		exit();
	}
}
if (!empty($_POST['del-action'])) {

	$reply_post_id = h($_POST['reply_post_id']);
	delete_post($db, $reply_post_id);
	$_SESSION['after_action_msg'] = "削除が完了しました！";

	// 二重送信防止
	header('Location: detail.php?post_id='.$post_id);
	exit();
}
?>

<div class="container">
	<div class="row">
		<form class="col s12" action="" method="post">
			<div class="row">
				<?php if(isset($after_action_msg)): ?>
					<p class="session-success-msg"><?= $after_action_msg ?></p>
				<?php endif;?>
            </div>
			<div class="row">
				<div class="input-field">
                    <input name="reply_message_id" type="hidden" value="<?= h($_GET['post_id']) ?>">
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
                        <a href="./profile.php?user_id=<?= h($post['user_id']) ?>"><?= $post['name'] ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php foreach($reply_posts as $reply_post): ?>
	<?php if($_SESSION['id'] == $reply_post['user_id']): ?>
		<div id="modal<?= $reply_post['id'] ?>" class="modal">
			<form class="col s12" action="" method="post">
				<div class="modal-content">
					<h4>本当に投稿を削除してよろしいですか？</h4>
					<p>一度削除した投稿は元に戻せません。</p>
				</div>
				<div class="modal-footer">
					<a href="#!" class="modal-close">キャンセル</a>
					<input type="hidden" name="reply_post_id" value="<?= $reply_post['id'] ?>">
					<button class="modal-close btn-style-reset" type="submit" name="del-action" value="1">削除</button>
				</div>
			</form>
		</div>
	<?php endif; ?>
	<div class="row">
		<div class="col s12">
			<div class="card horizontal">
				<div class="card-image">
					<a href="./profile.php?user_id=<?= h($reply_post['user_id']) ?>">
					<?php if(isset($reply_post['photo'])): ?>
						<img src="./asset/images/<?= $reply_post['photo'] ?>">
					<?php else: ?>
						<img src="./asset/images/default_user.png">
					<?php endif; ?>
					</a>
				</div>
				<div class="card-stacked">
					<div class="card-content">
						<p><?= h($reply_post['message']) ?></p>
					</div>
					<div class="card-action">
						<?php if($_SESSION['id'] == $reply_post['user_id']): ?>
							<a class="del-btn modal-trigger" href="#modal<?= $reply_post['id'] ?>" style="color: #F44336;">削除</a>
						<?php endif; ?>
						<a href="./profile.php?user_id=<?= h($reply_post['user_id']) ?>"><?= $reply_post['name'] ?></a>
						<span><?= $reply_post['created_at'] ?></span>
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
