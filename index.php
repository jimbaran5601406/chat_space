<?php
$title = 'ホーム/Chat Space';
require_once './template/header.php';

if(isset($_GET['page'])) {
	$page = h($_GET['page']);
} else {
	$page = 1;
}

$page = max($page, 1);

$countStmt = $db->query("
					SELECT
						COUNT(*) AS postNum
					FROM
						posts
					");
$result = $countStmt->fetch();
$maxPage = ceil($result['postNum'] / 10);
$page = min($page, $maxPage);

$posts = fetch_all_posts($db, $page);


if(!empty($_SESSION['after_action_msg'])) {
	$after_action_msg = $_SESSION['after_action_msg'];
	unset($_SESSION['after_action_msg']);
}
if(!empty($_POST['post-action'])) {
	$message = h($_POST['message']);

	if($message === '') {
		$error_msg['message_required'] = '※投稿内容を入力しないと投稿できません';
	} else {
		$stmt = $db->prepare("INSERT INTO posts (message, user_id, created_at) VALUES(:message, :user_id, NOW())");
		$stmt->execute(array(
			':message' => $message,
			':user_id' => $user['id']
		));
		$_SESSION['after_action_msg'] = "投稿が完了しました！";

		// 二重送信防止
		header('Location: index.php');
		exit();
	}
}
if (!empty($_POST['del-action'])) {

	$post_id = h($_POST['post_id']);
	delete_post($db, $post_id);
	$_SESSION['after_action_msg'] = "削除が完了しました！";

	// 二重送信防止
	header('Location: index.php');
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
					<textarea name="message" id="message" class="materialize-textarea" data-length="300"></textarea>
            		<label for="message">投稿内容</label>
					<?php if(isset($error_msg['message_required'])): ?>
						<p class="red-text"><?= $error_msg['message_required'] ?></p>
					<?php endif;?>
				</div>
				<div class="input-field">
					<button class="waves-effect waves-light btn blue" type="submit" name="post-action" value="1">投稿</button>
					<button class="waves-effect waves-light btn grey" type="reset">リセット</button>
				</div>
			</div>
		</form>
	</div>
	<div class="row">
	<?php foreach($posts as $post): ?>
		<?php if($_SESSION['id'] == $post['user_id']): ?>
		<div id="modal<?= $post['id'] ?>" class="modal">
			<form class="col s12" action="" method="post">
    			<div class="modal-content">
      				<h4>本当に投稿を削除してよろしいですか？</h4>
      				<p>一度削除した投稿は元に戻せません。</p>
    			</div>
    			<div class="modal-footer">
					  <a href="#!" class="modal-close">キャンセル</a>
					  <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
					  <button class="modal-close btn-style-reset" type="submit" name="del-action" value="1">削除</button>
				</div>
			</form>
		  </div>
		<?php endif; ?>
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
						<button class="btn-like btn-style-reset">
							<img class="<?= $post['id'] ?> heart <?= $post['is_liked']? 'like': '' ?>" src="./asset/images/like-false.svg" alt="お気に入りの投稿" width="32px">
						</button>
					</div>
					<div class="card-action">
						<?php if($_SESSION['id'] == $post['user_id']): ?>
						<a class="del-btn modal-trigger" href="#modal<?= $post['id'] ?>" style="color: #F44336;">削除</a>
						<?php endif; ?>
						<a href="./detail.php?post_id=<?= h($post['id']) ?>">コメント</a>
						<a href="./profile.php?user_id=<?= h($post['user_id']) ?>"><?= $post['name'] ?></a>
						<span><?= $post['created_at'] ?></span>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
		<div class="row center-align">
			<ul class="pagination">
				<?php if($page > 1): ?>
				<li class="waves-effect"><a href="index.php?page=<?= $page-1 ?>"><i class="material-icons">chevron_left</i></a></li>
				<?php else: ?>
				<li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
				<?php endif; ?>

				<?php for($i = 1; $i <= $maxPage; $i++): ?>
				<li class="waves-effect"><a href="index.php?page=<?= $i ?>"><?= $i ?></a></li>
				<?php endfor; ?>

				<?php if($page < $maxPage): ?>
				<li class="waves-effect"><a href="index.php?page=<?= $page+1 ?>"><i class="material-icons">chevron_right</i></a></li>
				<?php else: ?>
				<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
				<?php endif; ?>
    			<!-- <li class="active"><a href="#!">1</a></li>
    			<li class="waves-effect"><a href="#!">2</a></li>
    			<li class="waves-effect"><a href="#!">3</a></li>
    			<li class="waves-effect"><a href="#!">4</a></li>
    			<li class="waves-effect"><a href="#!">5</a></li> -->
  			</ul>
		</div>
	</div>
</div>
<!-- container -->
</body>
</html>
