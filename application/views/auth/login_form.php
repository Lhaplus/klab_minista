<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'メールアドレスまたはユーザ名';
} else if ($login_by_username) {
	$login_label = 'ユーザ名';
} else {
	$login_label = 'メールアドレス';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>

<div class="main">
	<div class="minista-main">
		<h2>ログイン</h2>
		<?php $attributes = array('class' => 'pure-form pure-form-stacked'); ?>
		<?php echo form_open($this->uri->uri_string(), $attributes); ?>
		<fieldset>
			<?php echo form_label($login_label, $login['id']); ?>
			<?php echo form_input($login); ?>
			<p style="color: red;">
				<?php echo form_error($login['name']); ?>
				<?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?>
			</p>
			<?php echo form_label('パスワード', $password['id']); ?>
			<?php echo form_password($password); ?>
			<p style="color: red;">
				<?php echo form_error($password['name']); ?>
				<?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?>
			</p>

			<!-- 動かなくなるのでコメントアウト -->
			<!-- <?php echo form_checkbox($remember); ?> -->
			<!-- <?php echo form_label('ログインしたままにする', $remember['id']); ?> -->

			<?php $data = array(
							'type'        => 'submit',
							'value'       => 'ログイン',
							'class'       => 'btn btn-default'
							); ?>
			<?php echo form_submit($data); ?>
			<?php echo anchor('/auth/forgot_password/', 'パスワードをお忘れの方'); ?>
		</fieldset>
	</div><!-- minista-main -->
</div><!-- main -->