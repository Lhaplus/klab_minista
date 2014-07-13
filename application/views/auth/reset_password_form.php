<?php
$new_password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_new_password = array(
	'name'	=> 'confirm_new_password',
	'id'	=> 'confirm_new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size' 	=> 30,
);
?>
		
<div class="main">
	<div class="minista-main">
		<h2>新しいパスワードの登録</h2>
		<?php echo form_open($this->uri->uri_string()); ?>
		<fieldset>
			<?php echo form_label('新しいパスワード', $new_password['id']); ?>
			<?php echo form_password($new_password); ?>
			<p style="color: red;"><?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?></p>
			<?php echo form_label('もう一度新しいパスワード', $confirm_new_password['id']); ?>
			<?php echo form_password($confirm_new_password); ?>
			<p style="color: red;"><?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?></p>
			<?php $data = array(
							'value'       => 'パスワードを変更',
							'class'       => 'btn btn-default'
			); ?>
			<?php echo form_submit($data); ?>
			<?php echo form_close(); ?>
		</fieldset>
	</div><!-- minista-main -->
</div><!-- main -->
