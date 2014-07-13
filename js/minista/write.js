$(document).ready(function(){

	var MY_MINISTA_ITEM_MAX_COUNT = 30;
	/* search image word */
	$(document).on('click', '#open_search_image_dialog', function(){
		open_dialog('search_image_dialog');
	});
	$(document).on('click', '#close_search_image_dialog', function(){
		close_dialog('search_image_dialog');
	});
	$(document).on('click', '#search_image', function(){
		search_image();
	});

	function search_image(){
		var image_word = $('#image_word').val();
		flicker_get_images(image_word);
		close_dialog('search_image_dialog');
		open_dialog('loading_dialog');
	}

	/* show image */
	$(document).on('click', '#close_show_image_dialog', function(){
		close_dialog('show_image_dialog');
	});
	$(document).on('click', '#show_image', function(){
		close_dialog('show_image_dialog');
	});
	$(document).on('click', '#back_to_search_image_dialog', function(){
		close_dialog('show_image_dialog');
		open_dialog('search_image_dialog');
	});

	/* add list */
	$(document).on('click', '#add_list', function(){
		if(MY_MINISTA_ITEM_MAX_COUNT <= get_item_count()){
			open_general_dialog('アイテムの数は30件までです');
		}else{
			var title ='<h4>タイトル</h4>';
			var input1 ="<input type='text' size='50' class='l_item_title form-control'>";
			var explanation = '<h4>説明</h4>';
			var input2 = "<input type='text' size='50' class='l_item_explanation form-control'>";
			var button = "<button class='mt5 delete_l_item btn btn-default'>削除</button>";

			$('#item_list').append(
				"<div class='l_item'>" + title + input1 + explanation + input2 + button + "</div>"
			);
			if(10 > get_item_count()){
				$('#how_many').html(get_item_count() + "つの");
			}else{
				$('#how_many').html(get_item_count() + "この");
			}
			rellocate_item_list();
		}
	});

	/* delete list */
	$(document).on('click', '.delete_l_item', function(){
		var crt_position = $(this).parent();
		crt_position.remove();
		rellocate_item_list()
	});

	function get_item_count(){
		var item_list = $('#item_list .l_item').size();
		return item_list;
	}

	function rellocate_item_list(){
		var item_list = $('#item_list');
		var count = 0;
		item_list.children(".l_item").each(function(){
			$(this).children(".l_item_title").attr({
				name: "items["+count+"][title]"
			});
			$(this).children(".l_item_explanation").attr({
				name: "items["+count+"][explanation]"
			});
			count = count + 1;
		});
	}
});
