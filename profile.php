<?php

$title = 'プロフィール/Chat Space';
require_once './template/header.php';

$user_id = h($_GET['user_id']);
$user_posts = fetch_all_user_posts($db, $_GET['user_id']);
$liked_posts = fetch_all_liked_posts($db, $_GET['user_id']);
$post_owner = fetch_user($db, $_GET['user_id']);

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
	header('Location: profile.php?user_id='.$user_id);
	exit();
}
?>

<div class="container">
	<div class="row">
		<div class="col s12">
			<div class="row">
				<?php if(isset($after_action_msg)): ?>
					<p class="session-success-msg"><?= $after_action_msg ?></p>
				<?php endif;?>
            </div>
			<div class="card profile">
                <div class="card-content profile__content">
                    <div class="profile-image"><img src="./asset/images/default_user.png" alt="ユーザー写真"></div>
                    <div class="profile-name"><?= h($post_owner['name']) ?></div>
                </div>
                <div class="card-tabs">
                    <ul class="tabs tabs-fixed-width">
                        <li class="tab"><a class="active" href="#post">投稿</a></li>
                        <li class="tab"><a href="#like">お気に入り</a></li>
                    </ul>
                </div>
                <div class="card-content grey lighten-4">
                    <div id="post">
	                    <div class="row">
						<?php foreach($user_posts as $user_post): ?>
							<?php if($_SESSION['id'] == $user_post['user_id']): ?>
								<div id="modal<?= $user_post['id'] ?>" class="modal">
									<form class="col s12" action="" method="post">
										<div class="modal-content">
											<h4>本当に投稿を削除してよろしいですか？</h4>
											<p>一度削除した投稿は元に戻せません。</p>
										</div>
										<div class="modal-footer">
											<a href="#!" class="modal-close">キャンセル</a>
											<input type="hidden" name="post_id" value="<?= $user_post['id'] ?>">
											<button class="modal-close btn-style-reset" type="submit" name="del-action" value="1">削除</button>
										</div>
									</form>
								</div>
							<?php endif; ?>
		                    <div class="col s12">
			                    <div class="card horizontal">
				                    <div class="card-image">
					                    <a href="./profile.php?user_id=<?= h($user_post['user_id']) ?>">
					                    <?php if(isset($user_post['photo'])): ?>
						                    <img src="./asset/images/<?= $user_post['photo'] ?>">
					                    <?php else: ?>
						                    <img src="./asset/images/default_user.png">
					                    <?php endif; ?>
					                    </a>
				                    </div>
				                    <div class="card-stacked">
					                    <div class="card-content">
											<p><?= h($user_post['message']) ?></p>
											<button class="btn-like btn-style-reset">
												<img class="<?= $user_post['id'] ?> heart <?= $user_post['is_liked']? 'like': '' ?>" src="./asset/images/like-false.svg" alt="お気に入りの投稿" width="32px">
											</button>
					                    </div>
					                    <div class="card-action">
											<?php if($_SESSION['id'] == $user_post['user_id']): ?>
												<a class="del-btn modal-trigger" href="#modal<?= $user_post['id'] ?>" style="color: #F44336;">削除</a>
											<?php endif; ?>
						                    <a href="./detail.php?post_id=<?= h($user_post['id']) ?>">コメント</a>
						                    <a href="./profile.php?user_id=<?= h($user_post['user_id']) ?>"><?= $user_post['name'] ?></a>
											<span><?= $user_post['created_at'] ?></span>
					                    </div>
				                    </div>
			                    </div>
		                    </div>
	                    <?php endforeach; ?>
	                    </div>
                    </div>
                    <div id="like">
						<div class="row">
						<?php foreach($liked_posts as $like_post): ?>
							<?php if($_SESSION['id'] == $like_post['user_id']): ?>
								<div id="modal<?= $like_post['id'] ?>" class="modal">
									<form class="col s12" action="" method="post">
										<div class="modal-content">
											<h4>本当に投稿を削除してよろしいですか？</h4>
											<p>一度削除した投稿は元に戻せません。</p>
										</div>
										<div class="modal-footer">
											<a href="#!" class="modal-close">キャンセル</a>
											<input type="hidden" name="post_id" value="<?= $like_post['id'] ?>">
											<button class="modal-close btn-style-reset" type="submit" name="del-action" value="1">削除</button>
										</div>
									</form>
								</div>
							<?php endif; ?>
		                    <div class="col s12">
			                    <div class="card horizontal">
				                    <div class="card-image">
					                    <a href="./profile.php?user_id=<?= h($like_post['user_id']) ?>">
					                    <?php if(isset($like_post['photo'])): ?>
						                    <img src="./asset/images/<?= $like_post['photo'] ?>">
					                    <?php else: ?>
						                    <img src="./asset/images/default_user.png">
					                    <?php endif; ?>
					                    </a>
				                    </div>
				                    <div class="card-stacked">
					                    <div class="card-content">
											<p><?= h($like_post['message']) ?></p>
											<button class="btn-like btn-style-reset">
												<img class="<?= $like_post['id'] ?> heart <?= $like_post['is_liked']? 'like': '' ?>" src="./asset/images/like-false.svg" alt="お気に入りの投稿" width="32px">
											</button>
					                    </div>
					                    <div class="card-action">
											<?php if($_SESSION['id'] == $like_post['user_id']): ?>
												<a class="del-btn modal-trigger" href="#modal<?= $like_post['id'] ?>" style="color: #F44336;">削除</a>
											<?php endif; ?>
						                    <a href="./detail.php?post_id=<?= h($like_post['id']) ?>">コメント</a>
						                    <a href="./profile.php?user_id=<?= h($like_post['user_id']) ?>"><?= $like_post['name'] ?></a>
						                    <span><?= $like_post['created_at'] ?></span>
					                    </div>
				                    </div>
			                    </div>
		                    </div>
	                    <?php endforeach; ?>
	                    </div>
					</div>
                </div>
            </div>
		</div>
	</div>
</div>
<!-- container -->
</body>
</html>
