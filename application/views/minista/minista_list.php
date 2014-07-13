<?php
/*
|--------------------------------------------------------------------------
| 利用可能な変数一覧
|
| 'articles' = データベース上のarticleの集合
| foreachで回して利用
|--------------------------------------------------------------------------
 */
?>

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
			<a href="/minista/article/<?php echo $article['id'] ?>">
				<p class="minista-title">
					<?php echo $article['t_target'] ?>
					最低限
					<?php echo $article['t_do'] ?>
					<?php echo $article['t_how_many'] ?>
					<?php echo $article['t_what'] ?>
				</p>
			</a>
			<span class="minista-view"><?php echo $article['view_count'] ?> view</span>
			<span class="minista-stock">★ <?php echo $article['favorite_count'] ?></span>
			<div>
				<?php echo $article['user_name'] ?>
			</div>
		</div><!-- minista-text -->
	</div><!-- clearfix -->
</div><!-- minista-list -->
<?php endforeach; ?>
<?php else: ?>
	記事がありません。
<?php endif; ?>