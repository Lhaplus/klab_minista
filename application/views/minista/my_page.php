<?php
/*
|--------------------------------------------------------------------------
| 利用可能な変数一覧
|
| 'histories as history' = ユーザの履歴（テーブル）
| 'username' = ユーザの名前
| 'email' = ユーザのメールアドレス
|--------------------------------------------------------------------------
 */
?>
<div class="main">
	<div class="minista-main">
		<h2><?php echo $username ?> さんのマイページ</h2>
		<div>
			<h3>会員メニュー</h3>
			<table class="table table-hover">
				<tr>
					<td><a href="/minista/write">ミニスタを投稿</a></td>
				</tr>
				<tr>
					<td><a href="/minista/my_minista">マイミニスタ</a></td>
				</tr>
				<tr>
					<td><a href="/minista/my_posted_article">投稿した記事</a></td>
				</tr>
				<tr>
					<td><a href="/auth/change_password">パスワード変更</a></td>
				</tr>
				<tr>
					<td><a href="/auth/change_email">メールアドレス変更</a></td>
				</tr>
				<tr>
					<td><a href="/auth/logout">ログアウト</a></td>
				</tr>
				<tr>
					<td><a href="/auth/unregister">退会</a></td>
				</tr>
			</table>
			<div>
				<h3>アクション</h3>
				<?php if($histories != null): ?>
				<table class="table table-hover">
				<?php foreach($histories as $key => $history): ?>
					<tr>
						<td>
							<?php echo date('Y年m月d日', $history['timestamp_int']) ?>
							<a href="/minista/article/<?php echo $history['article_id'] ?>">
								<?php echo $history['title'] ?>
							</a>
							<?php if($history['type'] == 'view'):?>
							を閲覧しました。
							<?php elseif($history['type'] == 'favorite'): ?>
							をお気に入りに登録しました。
							<?php elseif($history['type'] == 'vote'): ?>
							に
							<?php if($history['is_good'] == true): ?>
							good
							<?php else: ?>
							bad
							<?php endif; ?>
							と投票しました。
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</table>
				<?php endif; ?>
			</div>
		</div>
	</div><!-- minista-main -->
</div><!-- main -->