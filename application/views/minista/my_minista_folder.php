
<div class="clearfix">
	<div class="main">

		<div class="related-minista">
			<input type='hidden' id='folder_id' value=<?php echo $folder_id ?>>
			<div id='article_list'>
				<?php if($articles != null): ?>
				<?php foreach($articles as $article): ?>
				<div class="minista-list">
					<div class="clearfix">
						<div class="minista-img">
							<?php if(empty($article['image_path'])): ?>
							<img src=<?php echo base_url()?>image/no_image.png>
						<?php else: ?>
							<img src=<?php echo $article['image_path']?> onerror="this.src='<?php echo base_url()?>image/no_image.png';">
							<?php endif; ?>
						</div><!-- minista-img -->
						<div class="minista-text">
							<input type='hidden' class='article_id' value=<?php echo $article['id'] ?>>
							<a href="/minista/my_minista_article/<?php echo $article['id'] ?>">
								<p class='minista-title'>
									<?php echo $article['t_target'] ?>
                最低限
									<?php echo $article['t_do'] ?>
									<?php echo $article['t_how_many'] ?>
									<?php echo $article['t_what'] ?>
								</p>
							</a>
							<div>
								<?php echo $article['user_name'] ?>
							</div>
							<button class="open_delete_my_minista_article_dialog btn btn-default">削除</button>
						</div><!-- minista-text -->
					</div><!-- clearfix -->
				</div><!-- minista-list -->
				<?php endforeach; ?>
				<?php else: ?>
				<p class="mt20 mb20 txtRed"><i class="mr5 fa fa-exclamation-circle"></i>ミニスタが1件もお気に入り登録されていません。</p>
				<?php endif; ?>
			</div>
		</div>
		<div class="top-pager">
			<ul class="pure-paginator">
				<?php echo $pager ?>
			</ul>
		</div><!-- top-pager -->
	</div>
</div>
<!-- Dialog -->
<div id='delete_my_minista_article_dialog' class='dialog_container'>
	<div class='close_overlay'></div>
	<div class='dialog_main'>
		<h2>マイミニスタを削除しますか？</h2>
		<p>
			<button type='button' id='delete_my_minista_article' class="btn btn-default">削除</button>
			<button type='button' id='close_delete_my_minista_article_dialog' class="btn btn-default">閉じる</button>
		</p>
		<input type='hidden' class='article_id' value=''>
	</div>
</div>

<?php $this->load->view('common/dialog') ?>
