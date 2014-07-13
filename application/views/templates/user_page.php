<?php
/*
|--------------------------------------------------------------------------
| Header settings.
|
| 'small_flg' = true of false: true is big small figure, false is small figure.
|--------------------------------------------------------------------------
 */

$header_data['small_flg'] = TRUE;

 ?>
<!DOCTYPE html>
<html lang='ja-JP'>
	<?php echo $head ?>
	<body>

		<?php $this->load->view('common/analyticstracking') ?>
		<?php $this->load->view('common/header', $header_data) ?>

		<div class="container">
			<div class="title-area">
				<div class="minista-title">
					<div class="clearfix">
						<div class="minista-text">
							<h1></h1>
						</div><!-- minista-text -->
					</div><!-- clearfix -->
				</div><!-- minista-title -->
			</div><!-- title-area -->

			<div class="clearfix">
				<?php echo $body ?>
				<?php $this->load->view('sidebar/sidebar') ?>
			</div><!-- clearfix -->

		</div><!-- container -->

		<?php $this->load->view('common/footer') ?>

	</body>
</html>
