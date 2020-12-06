<?php
$title = 'Confirm';
require_once '../template/header.php';
?>

<div class="container center-align">
	<div class="row">
		<h1>Confirm</h1>
	</div>

	<div class="row">
		<form class="col s12" action="" method="post">
			<div class="row">
				<div class="col s6">
					<p>Name</p>
				</div>
				<div class="col s6">
				</div>
			</div>
			<div class="row">
				<div class="col s6">
					<p>Email</p>
				</div>
				<div class="col s6">
				</div>
			</div>
			<div class="row">
				<div class="col s6">
					<p>Password</p>
				</div>
				<div class="col s6">
				</div>
			</div>
			<div class="row">
				<input type="hidden" name="action" value="submit" />
				<a href="index.php?action=rewrite" class="waves-effect waves-light btn">&laquo;&nbsp;Back</a>
				<button class="waves-effect waves-light btn" type="submit">Register</a>
			</div>
		</form>
	</div>
</div>
</body>
</html>