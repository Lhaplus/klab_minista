
<div class="main">
	<div class="minista-main">

		<h2>マイミニスタ</h2>
		<div class="panel panel-success">
			<div class="panel-heading">
				<span class="txt16"><i class="fa fa-info-circle"></i> マイミニスタではお気に入り登録したミニスタをフォルダで管理できます。</span>
			</div>
			<div class="panel-body">
				<ul class="mb0">
					<li class="list-unstyled">フォルダは最大5つまで登録することができます。</li>
				</ul>
			</div>
		</div>
		<div id='folder_list'>
			<?php if($folders != null): ?>
			<?php foreach($folders as $folder): ?>
			<div class='folder'>
				<h5><i class="mr5 fa fa-folder-o"></i><a href="/minista/my_minista_folder/<?php echo $folder['id'] ?>"><?php echo $folder['name'] ?></a></h5>
				<input type='hidden' class='folder_id' value=<?php echo $folder['id'] ?>>
				<input type='hidden' class='folder_name' value=<?php echo $folder['name'] ?>>
				<button type='button' class='open_delete_my_minista_folder_dialog btn btn-default'>削除</button>
				<button type='button' class='open_update_my_minista_folder_name_dialog btn btn-default'>名前を変更</button>
			</div>
			<?php endforeach; ?>
			<?php else: ?>
			<p class="txtRed"><i class="mr5 fa fa-exclamation-circle"></i>現在一つもフォルダが登録されていません</p>
			<?php endif; ?>
		</div>
		<div class="mt20">
			<button type='button' id='open_add_my_minista_folder_dialog' class="btn btn-default">フォルダの追加</button>
		</div>
	</div><!-- minista-main -->
</div><!-- main -->

<!-- Dialog -->
<div id='add_my_minista_folder_dialog' class='dialog_container'>
	<div class='dialog_main'>
		<h2>フォルダを追加します</h2>
		<p>
			<div class='validation_message'></div>
			<input type='text' name='foldername' value='' id='foldername' class="ml10 form-control" maxlength='20' size='30' placeholder='フォルダ名' style="width: 200px">
			<button type='button' id='add_my_minista_folder' class="mt15 btn btn-default">追加</button>
			<button type='button' id='close_add_my_minista_folder_dialog' class="mt15 btn btn-default">閉じる</button>
		</p>
	</div>
</div>

<div id='delete_my_minista_folder_dialog' class='dialog_container'>
	<div class='dialog_main'>
		<h2>フォルダを削除しますか？</h2>
		<p>
			<button type='button' id='delete_my_minista_folder' class="btn btn-default">フォルダを削除</button>
			<button type='button' id='close_delete_my_minista_folder_dialog' class="btn btn-default">閉じる</button>
		</p>
		<input type='hidden' class='folder_id' value=''>
	</div>
</div>

<div id='update_my_minista_folder_name_dialog' class='dialog_container'>
	<div class='dialog_main'>
		<h2>フォルダの名前を変更します</h2>
		<p>
			<div class='validation_message'></div>
			<input type='text' id='new_folder_name' class="ml10 form-control" maxlength='20' size='30' placeholder='新しいフォルダ名' style="width: 200px">
			<button type='button' id='update_my_minista_folder_name' class="mt15 btn btn-default">名前を変更</button>
			<button type='button' id='close_update_my_minista_folder_name_dialog' class="mt15 btn btn-default">閉じる</button>
		</p>
		<input type='hidden' class='folder_id' value=''>
	</div>
</div>

<?php $this->load->view('common/dialog') ?>
