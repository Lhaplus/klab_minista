<?php
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
?>

<div class="main">
	<div class="minista-main">
		<h2>退会のお手続き</h2>
		<?php $attributes = array('class' => 'pure-form pure-form-stacked'); ?>
		<?php echo form_open($this->uri->uri_string(), $attributes); ?>
		<fieldset>
			<?php echo form_label('パスワード', $password['id']); ?>
			<?php echo form_password($password); ?>
			<p style="color: red;"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></p>
			<?php $data = array(
							'type'        => 'cancel',
							'value'       => '退会する',
							'class'       => 'btn btn-default'
							); ?>
			<?php echo form_submit($data); ?>
			<?php echo form_close(); ?>
		</fieldset>
	</div><!-- minista-main -->
</div><!-- main -->