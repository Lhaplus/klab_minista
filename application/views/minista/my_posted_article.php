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
<div class="clearfix">
	<div class="main">
		<div class="related-minista">
			<h2> 投稿したミニスタ一覧 </h2>
			<?php $this->load->view('minista/minista_list', array('articles' => $articles)) ?>
			<div class="top-pager">
				<ul class="pure-paginator">
					<?php echo $pager ?>
				</ul>
			</div><!-- top-pager -->
		</div>
	</div>
</div>