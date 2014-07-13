<?php 
/*
|--------------------------------------------------------------------------
| 利用可能な変数一覧
|
| 'minista' = 説明
|--------------------------------------------------------------------------
 */
/*
|--------------------------------------------------------------------------
| TODO list
|
| 1. 動的に5つ生成する
| 2. helper関数を作成する $this->library->get_minista_list_new
|    view, stock, title, minista_id(url用)
|--------------------------------------------------------------------------
 */
 ?>

<div class="minista-list-new">
	<h3>最新のミニスタ</h3>
	<div class="minista-list">
		<div class="clearfix">
			<div class="minista-text">
				<?php if(!isset($category_id)) $category_id = 0; ?>
				<?php $recent_articles = $this->minista_library->get_recent_articles($category_id); ?>
				<?php if(isset($recent_articles)): ?>
				<?php foreach($recent_articles as $article): ?>
				<?php if(isset($article['article_id'])): ?>
				<a href="/minista/article/<?php echo $article['article_id'] ?>">
				<?php else: ?>
				<a href="/minista/article/<?php echo $article['id'] ?>">
				<?php endif; ?>
				<p class="minista-title">
				<?php echo $article['t_target'] ?>
				最低限
				<?php echo $article['t_do'] ?>
				<?php echo $article['t_how_many'] ?>
				<?php echo $article['t_what'] ?>
				</p></a>
				<span class="minista-view"><?php echo $article['view_count'] ?></span>
				<span class="minista-stock">★ <?php echo $article['favorite_count'] ?></span>
				<?php endforeach; ?>
				<?php endif; ?>
			</div><!-- minista-text -->
		</div><!-- clearfix -->
	</div><!-- minista-list -->
</div><!-- minista-list-new -->