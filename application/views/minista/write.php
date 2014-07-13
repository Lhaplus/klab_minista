<?php
/*
|--------------------------------------------------------------------------
| 利用可能な変数一覧
|
| 'categories' = カテゴリ情報の配列．
| categories[i]['id'] = id
| categories[i]['name'] = カテゴリ名
| categories[i]['regist_count'] = 登録数
|--------------------------------------------------------------------------
 */
$NUM_CATEGORIES = 5;
?>

<div class="main-right">
	<h2>ミニスタを投稿</h2>
	<?php
	echo validation_errors();
	if(isset($error_message)){
		echo $error_message;
	}
	echo form_open('minista/write_form');
	echo '〜なら<br />';
	$data = array(
		'name' => 't_target',
		'value' => set_value('t_target'),
		'size' => '50',
		'class' => 'form-control',
		'placeholder' => '（例）新社会人なら'
	);
	echo form_input($data);
	echo '<h4 class="mt20">最低限</h4>';
	echo '〜べき:<br />';
	$data = array(
		'name' => 't_do',
		'value' => set_value('t_do'),
		'size' => '50',
		'class' => 'form-control',
		'placeholder' => '（例）知っておくべき'
	);
	echo form_input($data);
	echo "<h4 class='mt20'><span id = 'how_many'>◯つの</span></h4>";
	echo 'こと<br />';
	$data = array(
		'name' => 't_what',
		'value' => set_value('t_what'),
		'size' => '50',
		'class' => 'form-control',
		'placeholder' => '（例）ビジネススキル'
	);
	echo form_input($data);

	echo '<h4 class="mt20">カテゴリー</h4>';
	$data = array(
		'name' => 'category',
		'class' => 'ml5 mr20',
	);
	foreach($categories as $category) {
		$data['value'] = $category['id'];
		$data['checked'] = set_checkbox('category', $category['id'], FALSE);
		echo $category['name'];
		echo form_radio($data);
	}
	echo '<h4 class="mt20">タグ</h4>';
	for($i = 1; $i-1 < $NUM_CATEGORIES; $i++){
		$data = array(
			'name' => 'tags[]',
			'value' => set_value('tags[]'),
			'size' => '50',
			'class' => 'mb5 form-control',
			'placeholder' => "タグ$i"
		);
		echo form_input($data);
	}
	?>
	<div>
		<h4 class="mt20">画像</h4>
		<button type='button' id='open_search_image_dialog' class='mt5 mb5 btn btn-default'>検索して追加</button>
	</div>
	<div id='image_space'>
		<img id='is_image' src='<?php echo set_value('image_path') ?>' width='200' height='200'>
		<div id='is_title'></div>
		<div id='is_author'></div>
		<input type='hidden' id='is_hide_image' name='image_path' value='<?php echo set_value('image_path') ?>'>
		<input type='hidden' id='is_hide_title' name='image_title' value='<?php echo set_value('image_title') ?>'>
		<input type='hidden' id='is_hide_author' name='image_author' value='<?php echo set_value('image_author') ?>'>
	</div>
	<div id='item_list'>
		<?php
		for($i = 0; $i < $num_items; $i++){
			echo "<div class='l_item'>";
			echo '<h4 class="mt20">タイトル</h4>';
			$data = array(
				'name' => 'items[' . $i . '][title]',
				'value' => set_value('items[' . $i . '][title]'),
				'size' => '50',
				'class' => 'l_item_title form-control'
			);
			echo form_input($data);
			echo '<h4 class="mt20">説明</h4>';
			$data = array(
				'name' => 'items[' . $i . '][explanation]',
				'value' => set_value('items[' . $i . '][explanation]'),
				'size' => '50',
				'class' => 'l_item_explanation form-control'
			);
			echo form_input($data);
			echo "<button class='mt10 delete_l_item btn btn-default'>削除</button>";
			echo "</div>";
		}
		?>
	</div>
	<div>
		<button type='button' id='add_list' class='mt5 mb5 btn btn-default'>要素を追加</button>
	</div>
	<?php
	$data = array(
							'name'				=> 'write',
							'value'				=> 'ミニスタを投稿',
							'class'				=> 'mt10 btn btn-success'
							);
	echo form_submit($data);
	echo form_close();
	?>
</div><!-- main-right -->

<!-- Dialog	-->
<div id='search_image_dialog' class='dialog_container'>
	<div class='dialog_main'>
		<h2>画像を検索します</h2>
		<p>
			<input type='text' name='image_word' value='' id='image_word' class="form-control" maxlength='20' size='30' placeholder='（例）書籍'	style="width: 300px">
			<button type='button' id='search_image' class='mt5 mb5 btn btn-default'>画像を検索</button>
			<button type='button' id='close_search_image_dialog' class='mt5 mb5 btn btn-default'>閉じる</button>
		</p>
	</div>
</div>

<div id='show_image_dialog' class='dialog_container'>
	<div class='dialog_main'>
		<h2>画像を検索します</h2>
		<p>
			画像をクリックすると登録できます
			<button type='button' id='back_to_search_image_dialog' class='mt5 mb5 btn btn-default'>検索に戻る</button>
			<button type='button' id='close_show_image_dialog' class='mt5 mb5 btn btn-default'>閉じる</button>
			<div class='viewport'>
				<div id='FlickrPhotos'>
				</div>
			</div>
		</p>
	</div>
</div>

<?php $this->load->view('common/dialog') ?>