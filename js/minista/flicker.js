function _getFickerPhotos(data)

{
  var dataStat = data.stat;
  if(dataStat == 'ok'){
    $('#FlickrPhotos').empty();
    $.each(data.photos.photo, function(i,item){
      var itemFarm = item.farm;
      var itemServer = item.server;
      var itemID = item.id;
      var itemSecret = item.secret;
      var itemTitle = item.title;
			var itemOwner = item.owner;
      var itemLink = 'http://www.flickr.com/photos/cbn_akey/' + itemID + '/'
      var itemPath = 'http://farm' + itemFarm + '.static.flickr.com/' + itemServer + '/' + itemID + '_' + itemSecret + '_m.jpg';
      var image_path = '<img src="' + itemPath + '" alt="' + itemTitle + '" width="200" height="200">';
			var image_title = "<div class='title'>" + item.title  + "</div>";
			var image_author = "<div class='author'>" + item.owner  + "</div>";
      var htmlSrc = "<div class='item'>" + image_path + image_title + image_author + '</div>';
      $('#FlickrPhotos').append(htmlSrc);
    });
    $('#FlickrPhotos div').on('click',function(){
      var path = $(this).children('img').attr('src');
			var title = $(this).children('.title').html();
			var author = $(this).children('.author').html();
			$('#image_space #is_image').attr({
				src: path
			});
			$('#is_title').html(title);
			$('#is_author').html(author);
			$('#is_hide_image').val(path);
			$('#is_hide_author').val(author);
			$('#is_hide_title').val(title);
			close_dialog('show_image_dialog');
			open_general_dialog("イメージを登録しました");
    });
    Flipsnap('#FlickrPhotos', {
      distance: 220
    });
		// フリッカで取得した画像の大きさに合わせる
		$('#FlickrPhotos').css('width', 220 * data.photos.photo.length);
		close_dialog('loading_dialog');
		open_dialog('show_image_dialog');
  }else{
			close_dialog('loading_dialog');
			open_general_dialog('画像の読み込みに失敗しました');
	}
}
function _errorFlicker(XMLHttpRequest, textStatus, errorThrown){
	close_dialog('loading_dialog');
	open_general_dialog('画像の読み込みに失敗しました');
}
function flicker_get_images(image_word)
{
	$.ajax({
			type: 'GET',
			url: 'https://secure.flickr.com/services/rest',
			data: {
				format: 'json',
				method: 'flickr.photos.search',
				api_key: 'aed061f3169354e67cd80d815347d5ac',
				text: image_word,
				per_page: '10',
				privacy_filter: '1',
				safe_search: '3',
				license: '4,5,6,7'
			},
			dataType: 'jsonp',
			jsonp: 'jsoncallback',
			timeout: '10000',
			success: _getFickerPhotos,
			error: _errorFlicker
	});
}