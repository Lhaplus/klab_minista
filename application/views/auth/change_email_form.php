<?php
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
?>

<div class="main">
	<div class="minista-main">
		<h2>メールアドレスの変更</h2>
		<?php $attributes = array('class' => 'pure-form pure-form-stacked'); ?>
		<?php echo form_open($this->uri->uri_string(), $attributes); ?>
		<fieldset>
			<?php echo form_label('新しいメールアドレス', $email['id']); ?>
			<?php echo form_input($email); ?>
			<p style="color: red;"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></p>
			<?php echo form_label('パスワード', $password['id']); ?>
			<?php echo form_password($password); ?>
			<p style="color: red;"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></p>
			<?php $data = array(
							'value'       => 'メールアドレスを変更',
							'class'       => 'btn btn-default'
			); ?>
			<?php echo form_submit($data); ?>
			<?php echo form_close(); ?>
		</fieldset>

	</div><!-- minista-main -->
</div><!-- main -->