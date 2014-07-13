$(document).ready(function(){
	/* 定数 */
	IS_OK = 0;
	IS_ERROR = 1;

	/* allocate item */
	reallocate_item_number();

	/* my minista folderの追加 */
	$(document).on('click', '#open_add_my_minista_folder_dialog', function(){
		open_dialog('add_my_minista_folder_dialog');
	});
	$(document).on('click', '#close_add_my_minista_folder_dialog', function(){
		close_dialog('add_my_minista_folder_dialog');
	});
	$(document).on('click', '#add_my_minista_folder', function(){
		add_my_minista_folder();
	});
	function add_my_minista_folder(){
		close_dialog('add_my_minista_folder_dialog');
		open_dialog('loading_dialog');
		var name = $('#foldername').val();
    var data = {};
    data['name'] = name;
    $.ajax({
			type: "post",
			url: "/minista/add_my_minista_folder",
			data: data,
			dataType: "json"
		}).done(function(data){
			close_dialog('loading_dialog');
			var state = data.state;
			if(state == IS_OK){
				var insert_id = data.insert_id;
				var name = data.name;
				var li = "<h5><li class='mr5 fa fa-folder-o'><a href='/minista/my_minista_folder/"+insert_id+"'>" + name + "</a></li></h5>";
				var hid1 = "<input type='hidden'  class='folder_id' value=" + insert_id + ">";
				var hid2 = "<input type='hidden'  class='folder_name' value=" + name + ">";
				var button1 = "<button type='button' class='open_delete_my_minista_folder_dialog btn btn-default'>削除</button>";
				var button2 = "<button type='button' class='open_update_my_minista_folder_name_dialog btn btn-default'>名前を変更</button>";

				$('#folder_list').append(
					"<div class='folder'>" + li + hid1 + hid2 + button1 + button2 + "</div>"
				);
			}else if(state == IS_ERROR){
				var error_message = data.error_message;
				$('#add_my_minista_folder_dialog .validation_message').html(error_message);
				open_dialog('add_my_minista_folder_dialog');
			}else{
				close_dialog('loading_dialog');
				open_general_dialog('フォルダの登録に失敗しました');
			}

		}).fail(function(){
			close_dialog('loading_dialog');
			open_general_dialog('フォルダの登録に失敗しました');
		});
	}

	/* delete my minista folder */
	$(document).on('click', '.open_delete_my_minista_folder_dialog', function(){
		var crt_position = $(this).parent();
		var folder_id  = crt_position.children('.folder_id').val();
		var foldername = crt_position.children('.folder_name').val();
		$('#delete_my_minista_folder_dialog h2').text(foldername+'を削除しますか');
		$('#delete_my_minista_folder_dialog .folder_id').val(folder_id);
		open_dialog('delete_my_minista_folder_dialog');
	});
	$(document).on('click', '#close_delete_my_minista_folder_dialog', function(){
		close_dialog('delete_my_minista_folder_dialog');
	});
	$(document).on('click', '#delete_my_minista_folder', function(){
		delete_my_minista_folder();
	});

	function delete_my_minista_folder(){
		close_dialog('delete_my_minista_folder_dialog');
		open_dialog('loading_dialog');
		var folder_id = $('#delete_my_minista_folder_dialog .folder_id').val();
		var crt_position = search_position_folder_id(folder_id);
    var data = {};
    data['folder_id'] = folder_id;
		$.ajax({
			crt_position: crt_position,
			type: "post",
			url: "/minista/delete_my_minista_folder",
			data: data,
			dataType: "json"
		}).done(function(data){
			state = data.state;
			if(state == IS_OK){
				close_dialog('loading_dialog');
				crt_position.remove();
				open_general_dialog('フォルダを削除しました。');
			}else if(state == IS_ERROR){
				close_dialog('loading_dialog');
				open_general_dialog(data.error_message);
			}else{
				close_dialog('loading_dialog');
				open_general_dialog('フォルダの削除に失敗しました');
			}
		}).fail(function(){
			close_dialog('loading_dialog');
			open_general_dialog('フォルダの削除に失敗しました');
		});
	}

	function search_position_folder_id(folder_id){
		var folder_list = $('#folder_list');
		var position = null;
		folder_list.children('.folder').each(function(){
			if(folder_id == $(this).children('.folder_id').val()){
				position = $(this);
			}
		});
		return position;
	}

	/* my minista folderの名前変更 */
	$(document).on('click', '.open_update_my_minista_folder_name_dialog', function(){
		var crt_position = $(this).parent();
		var folder_id  = crt_position.children('.folder_id').val();
		var foldername = crt_position.children('.folder_name').val();
		$('#update_my_minista_folder_name_dialog h2').text(foldername + 'の名前を変更します');
		$('#update_my_minista_folder_name_dialog .folder_id').val(folder_id);
		open_dialog('update_my_minista_folder_name_dialog');
	});
	$(document).on('click', '#close_update_my_minista_folder_name_dialog', function(){
		close_dialog('update_my_minista_folder_name_dialog');
	});
	$(document).on('click', '#update_my_minista_folder_name', function(){
		update_my_minista_folder_name();
	});
	function update_my_minista_folder_name(){
		close_dialog('update_my_minista_folder_name_dialog');
		open_dialog('loading_dialog');
		var folder_id = $('#update_my_minista_folder_name_dialog .folder_id').val();
		var new_folder_name = $('#new_folder_name').val();
		var crt_position = search_position_folder_id(folder_id);
    var data = {};
    data['folder_id'] = folder_id;
    data['new_folder_name'] = new_folder_name;

		$.ajax({
			crt_position: crt_position,
			type: "post",
			url: "/minista/update_my_minista_folder_name",
			data: data,
			dataType: "json"
		}).done(function(data){
			close_dialog('loading_dialog');
			var state = data.state;
			if(state == IS_OK){
				var new_folder_name = data.new_folder_name;
				crt_position.children('li').children('a').text(new_folder_name);
				open_general_dialog('フォルダの名前を変更しました');
			}else if(state == IS_ERROR){
				var error_message = data.error_message;
				$('#update_my_minista_folder_name_dialog .validation_message').html(error_message);
				open_dialog('update_my_minista_folder_name_dialog');
			}else{
				close_dialog('loading_dialog');
				open_general_dialog('フォルダの名前の変更に失敗しました');
			}
		}).fail(function(){
			close_dialog('loading_dialog');
			open_general_dialog('フォルダの名前の変更に失敗しました');
		});
	}

	/* delete my minista article */
	$(document).on('click', '.open_delete_my_minista_article_dialog', function(){
		var crt_position = $(this).parent().parent().parent();
		var article_id   = crt_position.children('.clearfix').children('.minista-text').children('.article_id').val();
		var article_title = crt_position.children('.clearfix').children('.minista-text').children('a').children('.minista-title').text();
		$('#delete_my_minista_article_dialog h2').text(article_title+'を削除しますか？');
		$('#delete_my_minista_article_dialog .article_id').val(article_id);
		open_dialog('delete_my_minista_article_dialog');
	});
	$(document).on('click', '#close_delete_my_minista_article_dialog', function(){
		close_dialog('delete_my_minista_article_dialog');
	});
	$(document).on('click', '#delete_my_minista_article', function(){
		delete_my_minista_article();
	});


	function delete_my_minista_article(){
		close_dialog('delete_my_minista_article_dialog');
		open_dialog('loading_dialog');
		var article_id = $('#delete_my_minista_article_dialog .article_id').val();
		var folder_id = $('#folder_id').val();
		var crt_position = search_position_article_id(article_id);
    var data = {};

		data['article_id'] = article_id;
		data['folder_id']  = folder_id;
		
		$.ajax({
			crt_position: crt_position,
			type: "post",
			url: "/minista/delete_my_minista_article",
			data: data,
			dataType: "json"
		}).done(function(data){
			close_dialog('loading_dialog');
			var state = data.state;
			if(state == IS_OK){
				crt_position.remove();
				open_general_dialog('ミニスタを削除しました');
			}else if(state == IS_ERROR){
				open_general_dialog(data.error_message);
			}else{
				open_general_dialog('ミニスタの削除に失敗しました');
			}
		}).fail(function(){
			close_dialog('loading_dialog');
			open_general_dialog('ミニスタの削除に失敗しました');
		});
	}

	function search_position_article_id(article_id){
		var article_list = $('#article_list');
		var position = null;
		article_list.children('.minista-list').each(function(){
			if(article_id == $(this).children('.clearfix').children('.minista-text').children('.article_id').val()){
				position = $(this);
			}
		});
		return position;
	}


	/* add user item */

	$(document).on('click', '#open_add_my_minista_item_dialog', function(){
		open_dialog('add_my_minista_item_dialog');
	});
	$(document).on('click', '#close_add_my_minista_item_dialog', function(){
		close_dialog('add_my_minista_item_dialog');
	});
	$(document).on('click', '#add_my_minista_item', function(){
		add_my_minista_item();
	});

	function add_my_minista_item(){
		close_dialog('add_my_minista_item_dialog');
		open_dialog('loading_dialog');
		var title = $('#item_title').val();
		var explanation = $('#item_explanation').val();
		var article_id = $('#article_id').val();
		
    var data = {};
    data['title'] = title;
    data['explanation'] = explanation;
		data['article_id'] = article_id;

		$.ajax({
			type: "post",
			url: "/minista/add_my_minista_item",
			data: data,
			dataType: "json"
		}).done(function(data){
			close_dialog('loading_dialog');
			var state = data.state;
			if(state == IS_OK){
				var item_id = data.insert_id;
				var title = data.title;
				var explanation = data.explanation;
				var hid1 = "<input type='hidden' class='item_id' value=" + item_id + ">";
				var hid2 = "<input type='hidden' class='item_title' value=" + title + ">";
				var h2 = "<h2><span class='key'></span>. <span>" + title + "</span></h2>";
				var p = "<p>" + explanation + "</p>";
				var button = "<button class='open_delete_my_minista_item_dialog btn btn-default'>削除</button>";
				$('#item_list').append(
					"<div class='item'>" + hid1 + hid2 + h2 + p + button + "</div>"
				);
				reallocate_item_number();
				open_general_dialog('アイテムを登録しました');
			}else if(state == IS_ERROR){
				var error_message = data.error_message;
				$('#add_my_minista_item_dialog .validation_message').html(error_message);
				open_dialog('add_my_minista_item_dialog');
			}else{
				close_dialog('loading_dialog');
				open_general_dialog('アイテムの登録に失敗しました');
			}

		}).fail(function(){
			close_dialog('loading_dialog');
			open_general_dialog('アイテムの登録に失敗しました');
		});
	}

	/* delete my minista item */
	$(document).on('click', '.open_delete_my_minista_item_dialog', function(){
		var crt_position = $(this).parent();
		var item_id  = crt_position.children('.item_id').val();
		var itemtitle = crt_position.children('.item_title').val();
		$('#delete_my_minista_item_dialog h2').text(itemtitle+'を削除しますか');
		$('#delete_my_minista_item_dialog .item_id').val(item_id);
		open_dialog('delete_my_minista_item_dialog');
	});
	$(document).on('click', '#close_delete_my_minista_item_dialog', function(){
		close_dialog('delete_my_minista_item_dialog');
	});
	$(document).on('click', '#delete_my_minista_item', function(){
		delete_my_minista_item();
	});

	function delete_my_minista_item(){
		close_dialog('delete_my_minista_item_dialog');
		open_dialog('loading_dialog');

		var article_id = $('#article_id').val();
		var item_id = $('#delete_my_minista_item_dialog .item_id').val();
		var crt_position = search_position_item_id(item_id);

    var data = {};
    data['item_id'] = item_id;
    data['article_id'] = article_id;
		
		$.ajax({
			crt_position: crt_position,
			type: "post",
			url: "/minista/delete_my_minista_item",
			data: data,
			dataType: "json"
		}).done(function(data){
			close_dialog('loading_dialog');
			var state = data.state;
			if(state == IS_OK){
				crt_position.remove();
				reallocate_item_number()
				open_general_dialog('アイテムを削除しました');
			}else if(state == IS_ERROR){
				open_general_dialog(data.error_message);
			}else{
				open_general_dialog('アイテムの削除に失敗しました');
			}
		}).fail(function(){
			close_dialog('loading_dialog');
			open_general_dialog('アイテムの削除に失敗しました');
		});
	}

	function search_position_item_id(item_id){
		var item_list = $('#item_list');
		var position = null;
		item_list.children('.item').each(function(){
			if(item_id == $(this).children('.item_id').val()){
				position = $(this);
			}
		});
		return position;
	}


	function reallocate_item_number(){
		var item_list = $('#item_list');
		var count = 1;
		item_list.children(".item").each(function(){
			$(this).children("h2").children("span.key").text(count);
			count = count + 1;
		});
		count--;
		var t_how_many;
		if(count > 10){
			t_how_many = count + '個の';
		}else{
			t_how_many = count + 'つの';
		}
		$('#t_how_many').html(t_how_many);
	}
	
});
