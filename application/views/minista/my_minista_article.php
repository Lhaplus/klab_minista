<div class="title-area">
	<div class="minista-title">
		<div class="clearfix">
			<div class="minista-img">
				<?php if(empty($article['image_path'])): ?>
				<img src=<?php echo base_url()?>image/no_image.png>
				<?php else: ?>
				<img src=<?php echo $article['image_path']?> onerror="this.src='<?php echo base_url()?>image/no_image.png';">
				<div>
					タイトル：<?php echo $article['image_title'] ?>
					著者：<?php echo $article['image_author'] ?>
				</div>
				<?php endif; ?>
			</div><!-- minista-img -->
			<div class="minista-text">
				<h1>
					<?php echo $article['t_target'] ?>
                最低限
					<?php echo $article['t_do'] ?>
					<span id='t_how_many'><?php echo $article['t_how_many'] ?></span>
					<?php echo $article['t_what'] ?>
				</h1>
				<span class="minista-version">バージョン：2</span>
				<div>
					<?php echo $article['user_name'] ?>
				</div>
				<?php $this->load->view('common/social_botton') ?>

			</div><!-- minista-text -->
		</div><!-- clearfix -->
	</div><!-- minista-title -->
</div><!-- title-area -->
<pre>
”削除”を押すとアイテムが削除されます。
”追加”を押すとアイテムが追加されます。
</pre>
<div class="clearfix">
	<div class="main">
		<div class="minista-main">
			<div id='item_list'>
				<input type="hidden" id='article_id' value=<?php echo $article['id'] ?>>
				<?php if($items != null): ?>
				<?php foreach($items as $key => $item): ?>
				<div class="item">
					<input type="hidden" class='item_id' value=<?php echo $item['id'] ?>>
					<input type="hidden" class='item_title' value=<?php echo $item['title'] ?>>
					<h2>
						<span class='key'><?php echo $key + 1 ?></span>.
						<span><?php echo $item['title'] ?></span>
					</h2>
					<p><?php echo $item['explanation'] ?></p>
					<button class="open_delete_my_minista_item_dialog btn btn-default">削除</button>
				</div>
				<?php endforeach; ?>
				<?php else: ?>
				* リストが登録されていません
				<?php endif; ?>
			</div>
			<button id='open_add_my_minista_item_dialog' class='btn btn-default'>追加</button>
		</div><!-- minista-main -->
		<?php $this->load->view('minista/category_menu') ?>
	</div><!-- main -->
	<?php $this->load->view('sidebar/sidebar') ?>
</div><!-- clearfix -->

<!-- Dialog -->
<div id='add_my_minista_item_dialog' class='dialog_container'>
	<div class='close_overlay'></div>
	<div class='dialog_main'>
		<h2>Itemを追加します</h2>
		<p>
			<div class='validation_message'></div>
			<div>
				<input type='text' value='' id='item_title' class="ml10 form-control" maxlength='20' size='30' placeholder='タイトル' style="width: 500px">
				<textarea value='' id='item_explanation' class="mt10 ml10 form-control" cols='30' rows='6' placeholder='説明' style="width: 500px"></textarea>
				
			</div>
			<button type='button' id='add_my_minista_item' class='btn btn-default'>追加</button>
			<button type='button' id='close_add_my_minista_item_dialog' class='btn btn-default'>閉じる</button>
		</p>
	</div>
</div>

<div id='delete_my_minista_item_dialog' class='dialog_container'>
	<div class='close_overlay'></div>
	<div class='dialog_main'>
		<h2>フォルダを削除しますか？</h2>
		<p>
			<button type='button' id='delete_my_minista_item' class='btn btn-default'>削除</button>
			<button type='button' id='close_delete_my_minista_item_dialog' class='btn btn-default'>閉じる</button>
		</p>
		<input type='hidden' class='item_id' value=''>
	</div>
</div>

<?php $this->load->view('common/dialog') ?>
