<?php
/*
|--------------------------------------------------------------------------
| Header settings.
|
| 'small_flg' = true of false: true is big small figure, false is small figure.
| 'categories' = category data set.(refer to database)
|--------------------------------------------------------------------------
 */

$header_data['small_flg'] = FALSE;

if(isset($now_category) === TRUE){
	$header_data['now_category'] = $now_category;
}else{
	$header_data['now_category'] = 0;
}
if(isset($index_flg) === TRUE){
	$sidebar_data['facebook_flg'] = $index_flg;
}else{
	$sidebar_data['facebook_flg'] = FALSE;
}
?>
<!DOCTYPE html>
<html lang='ja-JP'>

	<?php echo $head ?>

	<body>

		<?php $this->load->view('common/analyticstracking') ?>
		<?php $this->load->view('common/header', $header_data) ?>

		<div class="container">
			<div class="utility-area">
				<div class="clearfix">
					<div class="search">
						<!-- <?php if(!isset($now_category)) $now_category = 0; ?> -->
						<form class="pure-form" action="/minista/search/" method="get">
							<input type="text" class="pure-input-rounded" placeholder="ミニスタを検索" name="keyword" value="">
							<button type="submit" class="pure-button">検索</button>
						</form>
					</div><!-- search -->

					<?php $this->load->view('common/social_botton') ?>

				</div><!-- clearfix -->
			</div><!-- utility-area -->
			<div class="clearfix">
				<div class="main">
					<div class="clearfix">
						<div class="main-left">
							<div class="pure-menu pure-menu-open">
								<ul>
									<li class="pure-menu-selected"><a href="/minista/index/">TOP</a></li>
<?php
if(!isset($categories)) $categories = $this->minista_library->get_categories();
foreach($categories as $category) {
?>
									<li><a href="/minista/index/0/<?php echo $category["name"];?>" class="<?php echo $category["css_class"];?>"><?php echo $category["j_name"];?></a></li>
									<?php
									}
									?>
									<!--↑これ
									<li><a href="#" class="l">ライフ</a></li>
									<li><a href="#" class="b">ビジネス</a></li>
									<li><a href="#" class="g">ガール</a></li>
									<li><a href="#" class="t">ティーン</a></li>
									<li><a href="#" class="e">エンタメ</a></li>
									-->
								</ul>
							</div>
						</div><!-- main-left -->
						<?php echo $body ?>
					</div><!-- clearfix -->
				</div><!-- main -->
				<?php if(!isset($now_category)) $now_category = 0; ?>
				<?php $sidebar_data['category_id'] = $now_category; ?>
				<?php $this->load->view('sidebar/sidebar', $sidebar_data) ?>

			</div><!-- clearfix -->
		</div><!-- container -->


		<?php $this->load->view('common/footer') ?>

	</body>
</html>
