<?php

$title = 'プロフィール/Chat Space';
require_once './template/header.php';

$user_posts = fetch_all_user_posts($db, $_GET['user_id']);
$liked_posts = fetch_all_liked_posts($db, $_GET['user_id']);
$post_owner = fetch_user($db, $_GET['user_id']);

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
		<div class="col s12">
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
											<button class="btn-like">
												<img class="<?= $user_post['id'] ?> heart <?= $user_post['is_liked']? 'like': '' ?>" src="./asset/images/like-false.svg" alt="お気に入りの投稿" width="32px">
											</button>
					                    </div>
					                    <div class="card-action">
						                    <span><?= $user_post['created_at'] ?></span>
						                    <a href="./detail.php?post_id=<?= h($user_post['id']) ?>">コメント</a>
						                    <a href="./profile.php?user_id=<?= h($user_post['user_id']) ?>"><?= $user_post['name'] ?></a>
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
											<button class="btn-like">
												<img class="<?= $like_post['id'] ?> heart <?= $like_post['is_liked']? 'like': '' ?>" src="./asset/images/like-false.svg" alt="お気に入りの投稿" width="32px">
											</button>
					                    </div>
					                    <div class="card-action">
						                    <span><?= $like_post['created_at'] ?></span>
						                    <a href="./detail.php?post_id=<?= h($like_post['id']) ?>">コメント</a>
						                    <a href="./profile.php?user_id=<?= h($like_post['user_id']) ?>"><?= $like_post['name'] ?></a>
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
