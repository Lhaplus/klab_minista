<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'メールアドレスまたはユーザ名';
} else {
	$login_label = 'メールアドレス';
}
?>

<div class="main">
	<div class="minista-main">
	<h2>パスワードの再発行</h2>
		<?php $attributes = array('class' => 'pure-form pure-form-stacked'); ?>
		<?php echo form_open($this->uri->uri_string(), $attributes); ?>
		<fieldset>
			<?php echo form_label($login_label, $login['id']); ?>
			<?php echo form_input($login); ?>
			<p style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></p>
			<?php echo form_hidden('reset', 'none'); ?>
			<?php $data = array(
							'value'       => 'パスワードを変更',
							'class'       => 'btn btn-default'
							); ?>
			<?php echo form_submit($data); ?>
			<?php echo form_close(); ?>
		</fieldset>

	</div><!-- minista-main -->
</div><!-- main -->