<?php
/*
|--------------------------------------------------------------------------
| Header tag settings.
|
| 'title' = Title of HTML5.
| 'meta_keywords' = Words or phrases which you want to attach with this page.
| 'meta_description' = Awesome description here
|
| 'javascript_paths' = Directory where the javascript files will be created.
| 'css_paths' = Directory where the css files will be created.
|--------------------------------------------------------------------------
 */

$head_data['title'] = '最低限知っておくべきただ一つのサイト - Minista.jp';
$head_data['meta_keywords'] = array(
	'Minista',
	'Minista.jp',
	'ミニスタ',
	'minimum standard',
	'ミニマムスタンダード',
	'最低限',
	'最低基準',
	'まとめ'
);
$head_data['meta_description'] = '最低限知っておくべきただ一つのサイト';

$head_data['javascript_paths'] = array(
	'js/jquery/jquery-1.8.3.js',
	'js/jquery/ui/jquery-ui.js',
	'js/minista/modal_dialog.js',
	'js/minista/my_minista.js'
);
$head_data['css_paths'] = array(
	'css/minista/detail.css',
	'css/minista/pure-min.css',
	'css/minista/common.css',
	'css/minista/my_minista.css',
	'css/minista/dialog.css'
);
?>
<?php $this->load->view('common/head', $head_data) ?>
