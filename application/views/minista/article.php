<?php
/*
|--------------------------------------------------------------------------
| 利用可能な変数一覧
|
| 'article' = データベースのarticleテーブルの情報そのまま．
| 'items' = アイテムの配列．各配列の中身はデータベースのitemテーブルの情報そのまま．
|
|--------------------------------------------------------------------------
 */
?>

<div class="title-area">
	<div class="minista-title">
		<div class="clearfix">
			<div class="minista-img">
				<?php if(empty($article['image_path'])): ?>
				<img src=<?php echo base_url()?>image/no_image.png>
				<?php else: ?>
				<img src=<?php echo $article['image_path']?> onerror="this.src='<?php echo base_url()?>image/no_image.png';">
				<div class="mt5 txt10 color_a">
					画像元：<?php echo $article['image_title'] ?>（<?php echo $article['image_author'] ?>）
				</div>
				<?php endif; ?>
			</div><!-- minista-img -->

			<div class="mt10 minista-text">
				<div class="mr20" align="right">
					<span class="mr10 minista-view color_b txt18"><?php echo $article['view_count'] ?> view</span>
					<span class="minista-stock color_b txt18">★ <?php echo $article['favorite_count'] ?></span>
				</div>
				<!-- only display -->
				<div class="mb10 tag">
					<?php
					if(is_array($tags)):
						foreach($tags as $tag):
					?>
							<a href="/minista/search/0/2?keyword=<?php echo $tag['name'] ?>"><span class="mr10 label label-default txt20"><?php echo $tag['name']." "; ?></span></a>
					<?php
						endforeach;
					endif;
					?>
				</div>
				<h1 class="mb20">
					<?php echo $article['t_target'] ?>
					最低限
					<?php echo $article['t_do'] ?>
					<span id = 't_how_many'><?php echo $t_how_many ?></span>
					<?php echo $article['t_what'] ?>
				</h1>
				<input type='hidden' id='article_id' value=<?php echo $article['id'] ?>>
				<div><?php echo $article['user_name'] ?> さん</div>
				<?php $this->load->view('common/social_botton') ?>

				<?php if($this->tank_auth->is_logged_in()): ?>
				<button type='button' id='open_select_folder_dialog' class='stock-b pure-button'>お気に入りに登録</button>
				<?php endif; ?>

			</div><!-- minista-text -->
		</div><!-- clearfix -->
	</div><!-- minista-title -->
</div><!-- title-area -->

<div class="clearfix">
	<div class="main">
		<div class="mb40 minista-main">
			<div id='item_list'>
				<?php if($items != null): ?>
				<?php foreach($items as $item): ?>
				<?php if($item['visible'] == 1 || $posted_user): ?>
				<div class='item'>
					<h2><?php echo $item['title'] ?></h2>
					<?php if(isset($item['explanation'])): ?>
					<p><?php echo $item['explanation'] ?></p>
					<?php else: ?>
					<p></p>
					<?php endif; ?>
					<div class='vote'>
						<i class='fa fa-thumbs-o-up'></i>
						<span class='vote_good'><?php echo $item['good'] ?></span>
						<i class='fa fa-thumbs-o-down'></i>
						<span class='vote_bad'><?php echo $item['bad'] ?></span>
						<?php if($item['vote_flg']): ?>
						投票済み
						<?php endif; ?>
					</div>
					<?php if($posted_user): ?>
					<div class='edit_item'>
						<?php if($item['visible'] === '1'): ?>
						<button class='hide_item btn btn-default btn-xs'>非表示</button>
						<?php else: ?>
						<button class='show_item btn btn-default btn-xs'>表示</button>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<input type="hidden" class='item_id' value=<?php echo $item['id'] ?>>
				</div>
				<?php endif; ?>
				<?php endforeach; ?>
				<?php else: ?>
※ 要素がありません
				<?php endif; ?>
			</div>
			<?php if($posted_user): ?>
			<button id='open_add_minista_item_dialog' class="mt10 btn btn-default">追加</button>
			<?php endif; ?>
		</div><!-- minista-main -->

		<div class="related-minista">
			<h3>このミニスタを見た人はこんなミニスタも見ています</h3>
			<?php $this->load->view('minista/minista_list', array('articles' => $recommend_articles)) ?>
		</div><!-- related-minista -->
		<?php $this->load->view('minista/category_menu') ?>

	</div><!-- main -->
	<?php $this->load->view('sidebar/sidebar') ?>
</div><!-- clearfix -->

<!-- Dialog -->
<div id='select_folder_dialog' class='dialog_container'>
	<div class='dialog_main'>
		<h2>登録するフォルダを選択してください</h2>
		<p>
			<div class='validation_message'></div>
			<?php if(!empty($folders)): ?>
			<select id="select_folder" name="select_folder">
				<?php foreach($folders as $folder): ?>
				<option value=<?php echo $folder['id'] ?>><?php echo $folder['name'] ?></option>
				<?php endforeach; ?>
			</select>
			<button type='button' id='select_folder_button' class="mt10 btn btn-default">フォルダを選択</button>
			<?php else: ?>
			フォルダがありません。作成してください。
			<?php endif ?>
			<button type='button' id='close_select_folder_dialog' class="mt10 btn btn-default">閉じる</button>
		</p>
	</div>
</div>

<div id='add_minista_item_dialog' class='dialog_container'>
	<div class='close_overlay'></div>
	<div class='dialog_main'>
		<h2>要素を追加します</h2>
		<p>
			<div class='validation_message'></div>
			<div>
				<input type='text' value='' id='item_title' class="ml10 form-control" maxlength='20' size='30' placeholder='タイトル' style="width: 500px">
				<textarea value='' id='item_explanation' class="mt10 ml10 form-control" cols='30' rows='6' placeholder='説明' style="width: 500px"></textarea>
			</div>
			<button type='button' id='add_minista_item' class="mt10 btn btn-default">追加</button>
			<button type='button' id='close_add_minista_item_dialog' class="mt10 btn btn-default">閉じる</button>
		</p>
	</div>
</div>

<?php $this->load->view('common/dialog') ?>
