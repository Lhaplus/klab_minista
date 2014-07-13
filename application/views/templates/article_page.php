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
				<?php echo $body ?>
		</div><!-- container -->

		<?php $this->load->view('common/footer') ?>

	</body>
</html>
