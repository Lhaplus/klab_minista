<?php
/*
|--------------------------------------------------------------------------
| 利用可能な変数一覧
|
| 'articles' = データベース上のarticleの集合
| foreachで回して利用
| 画像の挿入はまだしていない
|--------------------------------------------------------------------------
 */
?>

<div class="main-right">
	<p class="popular">人気のミニスタ</p>
	<?php $this->load->view('minista/minista_list', array('articles' => $articles)) ?>
	<div class="top-pager">
		<ul class="pure-paginator">
			<?php echo $pager ?>
		</ul>
	</div><!-- top-pager -->
</div><!-- main-right -->
