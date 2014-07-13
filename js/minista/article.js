$(document).ready(function(){
	
	/* const */
	IS_OK = 0;
	IS_ERROR = 1;
	IS_ERROR_AUTH = 2;
	BAD  = 0;
	GOOD = 1;
	INVALID_VOTE = 2;
	HIDE = 0;
	SHOW = 1;

	/* t_how_many */
	reallocate_item_number();

	/* vote */
	$(document).on('click', '.fa-thumbs-o-up', function(){
		var crt_position = $(this).parent().parent();
		vote(crt_position, GOOD);
	});

	$(document).on('click', '.fa-thumbs-o-down', function(){
		var crt_position = $(this).parent().parent();
		vote(crt_position, BAD);
	});
	
	function vote(crt_position, good_bad){
		var article_id = $('#article_id').val();
		var item_id    = crt_position.children('.item_id').val();
		var is_good    = good_bad;
		var data = {};
		data['article_id'] = article_id;
		data['item_id']    = item_id;
		data['is_good']    = is_good;

		open_dialog('loading_dialog');

		$.ajax({
			crt_position: crt_position,
			type: "post",
			url: "/minista/vote",
			data: data,
			dataType: "json"
		}).done(function(data){
			close_dialog('loading_dialog');
			var state = data.state;
			var vote_state = data.vote_state;
			if(state == IS_OK){
				var tmp;
				if(vote_state == GOOD){
					tmp = crt_position.children('.vote').children('.vote_good').text();
					var count = Number(tmp) + 1;
					crt_position.children('.vote').children('.vote_good').text(count);
				}else if(vote_state == BAD){
					tmp = crt_position.children('.vote').children('.vote_bad').text();
					var count = Number(tmp) + 1;
					crt_position.children('.vote').children('.vote_bad').text(count);
				}else{
					open_general_dialog("投票しました。");
				}
			}else if(state == IS_ERROR){
					open_general_dialog(data.error_message);
			}else{
					open_general_dialog("不正な投票です。");
			}
		}).fail(function(){
				close_dialog('loading_dialog');
				open_general_dialog("不正な投票です。");
		});
	}

	/* add my minista dialog */
	close_dialog('select_folder_dialog');

	$(document).on('click', '#open_select_folder_dialog', function(){
		open_dialog('select_folder_dialog');
	});
	$(document).on('click', '#close_select_folder_dialog', function(){
		close_dialog('select_folder_dialog');
	});
	$(document).on('click', '#select_folder_button', function(){
		select_folder();
	});

	function select_folder(){
		close_dialog('select_folder_dialog');
		open_dialog('loading_dialog');

		var folder_id = $('#select_folder').val();
		var article_id = $('#article_id').val();

    var data = {};
    data['article_id'] = article_id;
    data['folder_id'] = folder_id;
		$.ajax({
			type: "post",
			url: "/minista/add_my_minista",
			data: data,
			dataType: "json"
		}).done(function(data){
			close_dialog('loading_dialog');
			var state = data.state;
			if(state == IS_OK){
				open_general_dialog("登録しました。");
			}else if(state == IS_ERROR){
				var error_message = data.error_message;
				$('#select_folder_dialog .validation_message').text(error_message);
				open_dialog('select_folder_dialog');
			}else{
				open_general_dialog("登録に失敗しました。");
			}
		}).fail(function(){
				close_dialog('loading_dialog');
				open_general_dialog("登録に失敗しました。");
		});
	}

	/* add user item */
	$(document).on('click', '#open_add_minista_item_dialog', function(){
		open_dialog('add_minista_item_dialog');
	});
	$(document).on('click', '#close_add_minista_item_dialog', function(){
		close_dialog('add_minista_item_dialog');
	});
	$(document).on('click', '#add_minista_item', function(){
		add_minista_item();
	});

	function add_minista_item(){
		close_dialog('add_minista_item_dialog');
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
			url: "/minista/add_minista_item",
			data: data,
			dataType: "json"
		}).done(function(data){
			close_dialog('loading_dialog');
			var state = data.state;
			if(state == IS_OK){
				var title       = data.title;
				var explanation = data.explanation;
				var insert_id   = data.insert_id;

				var t_title = "<h2>" + title + "</h2>";
				var t_explanation = "<p>" + explanation + "</p>";
				var t_div_vote = "<div class='vote'><i class='fa fa-thumbs-o-up'></i><span class='vote_good'>0</span><i class='fa fa-thumbs-o-down'></i><span class='vote_bad'>0</span>投稿しよう！</div>";
				var t_div_edit_item = "<div class='edit_item'><button class='hide_item  btn btn-default btn-xs'>非表示</button></div>";
				var t_input = "<input type='hidden' class='item_id' value=" + insert_id + ">";
				var t_div = "<div class='item'>" +
										t_title +
										t_explanation +
										t_div_vote +
										t_div_edit_item +
										t_input +
										"</div>";
				$('#item_list').append(t_div);
				reallocate_item_number();
				open_general_dialog('アイテムを登録しました。');

			}else if(state == IS_ERROR){
				$('#add_minista_item_dialog .validation_message').html(data.error_message);
				open_dialog('add_minista_item_dialog');
			}else{
				close_dialog('loading_dialog');
				open_general_dialog('アイテムの登録に失敗しました。');
			}

		}).fail(function(){
			close_dialog('loading_dialog');
			open_general_dialog('アイテムの追加に失敗しました。');
		});
	}

	/* switch visible article */
	$(document).on('click', '.hide_item', function(){
		var crt_position = $(this).parent().parent();
		switch_visible_article('hide', crt_position);
	});
	$(document).on('click', '.show_item', function(){
		var crt_position = $(this).parent().parent();
		switch_visible_article('show', crt_position);
	});

	function switch_visible_article(show_or_hide, crt_position){
		var item_id    = crt_position.children('.item_id').val();
		var visible;
		if(show_or_hide === 'show'){visible = SHOW;}
		else if(show_or_hide === 'hide'){visible = HIDE;}
		else{visible = HIDE;}

    var data = {};
    data['item_id']    = item_id;
    data['visible']    = visible;

		$.ajax({
			crt_position:crt_position,
			type: "post",
			url: "/minista/switch_visible_article",
			data: data,
			dataType: "json"
		}).done(function(data){
			var state = data.state;
			if(state == IS_OK){
				crt_position.children('.edit_item').empty();
				if(data.visible == '1'){
					crt_position.children('.edit_item').append("<button class='hide_item btn btn-default btn-xs'>非表示</button>");
					open_general_dialog("表示にしました。");
				}else{
					crt_position.children('.edit_item').append("<button class='show_item btn btn-default btn-xs'>表示</button>");
					open_general_dialog("非表示にしました。");
				}
			}else if(state == IS_ERROR){
				var errro_message = data.error_message;
				open_general_dialog(errro_message);
			}else{
				open_general_dialog('エラー');
			}
			
		}).fail(function(){
				open_general_dialog('通信エラーが発生しました。時間をおいてからやり直してください。');
		});
	}

	function reallocate_item_number(){
		var item_list = $('#item_list');
		var count = item_list.children(".item").size();;
		
		var t_how_many;
		if(count > 10){
			t_how_many = count + '個の';
		}else{
			t_how_many = count + 'つの';
		}
		$('#t_how_many').text(t_how_many);
	}
});