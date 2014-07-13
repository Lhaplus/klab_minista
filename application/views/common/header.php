<?php
$header_color = '';
if(!isset($now_category)){
	$header_color = '';
}else{
	if($now_category == 0){
		$header_color = '';
	}else if($now_category == 1) {
		$header_color = 'line-l';
	}else if($now_category == 2) {
		$header_color = 'line-b';
	}else if($now_category == 3) {
		$header_color = 'line-g';
	}else if($now_category == 4) {
		$header_color = 'line-t';
	}else if($now_category == 5) {
		$header_color = 'line-e';
	}
}
?>
<header id = <?php echo $header_color ?>>
	<div class="header-wraper">
		<div class="row">
			<div class="mt5 col-xs-4">
				<?php if ($small_flg === TRUE): ?>
				<form class="pure-form" action="/minista/search/" method="get">
					<input type="text" class="pure-input-rounded" placeholder="ミニスタを検索" name="keyword" value="">
					<button type="submit" class="pure-button">検索</button>
				</form>
				<?php endif; ?>
			</div>
			<div class="col-xs-4">
				<div class="top-logo" >
					<?php if ($small_flg === TRUE): ?>
					<a href="/minista/index"><img src="/image/minista_sq.png" /></a>
					<?php else: ?>
					<a href="/minista/index"><img src="/image/minista.png" /></a>
					<?php endif; ?>
				</div>
			</div>
			<div class="mt5 col-xs-4" align="right">
				<div class="">
					<?php if ($this->tank_auth->is_logged_in()): ?>
					<div class="btn-group">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<?php echo $this->tank_auth->get_username() ?><span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu" align="left">
							<li><a href="/minista/my_page">マイページ</a></li>
							<li><a href="/minista/config">設定</a></li>
							<li><a href="/auth/logout">ログアウト</a></li>
						</ul>
					</div>
					<?php else: ?>
					<ul class="list-inline">
						<li><a class="btn btn-success" href="/auth/login">ログイン</a></li>
						<li><a class="btn btn-danger" href="/auth/register">会員登録</a></li>
					</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</header>