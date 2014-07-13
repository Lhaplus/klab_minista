<?php
if ($use_username) {
	$username = array(
		'name'	=> 'username',
		'id'	=> 'username',
		'value' => set_value('username'),
		'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
		'size'	=> 30,
		'placeholder' => 'ユーザ名を入力'
	);
}
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
	'placeholder' => 'メールアドレスを入力'
);
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
	'placeholder' => 'パスワードを入力'
);
$confirm_password = array(
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'value' => set_value('confirm_password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
	'placeholder' => '再度パスワードを入力'
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>

<div class="main">
	<div class="minista-main">
		<div class="boder-all">
			<h2>新規会員登録</h2>
			<div class="panel panel-success">
				<div class="panel-heading">
					<span class="txt16"><i class="fa fa-info-circle"></i> 会員登録を行うと、以下のようなことができるようになります。</span>
				</div>
				<div class="panel-body">
					<ul class="mb0">
						<li class="list-unstyled"><strong>ミニスタを投稿</strong>することができます。</li>
						<li class="list-unstyled">他のユーザーが投稿したミニスタを<strong>お気に入り登録</strong>することができます。</li>
						<li class="list-unstyled">他のユーザーが投稿したミニスタをベースに<strong>自分だけのミニスタを作成</strong>することができます。</li>
					</ul>
				</div>
			</div>
			<?php $attributes = array('class' => 'pure-form pure-form-aligned'); ?>
			<?php echo form_open($this->uri->uri_string(), $attributes); ?>
				<?php if ($use_username) { ?>
				<fieldset>
					<div class="pure-control-group">
						<?php echo form_label('ユーザ名', $username['id']); ?>
						<?php echo form_input($username); ?>
						<p style="color: red;"><?php echo form_error($username['name']); ?><?php echo isset($errors[$username['name']])?$errors[$username['name']]:''; ?><p>
					</div>
				<?php } ?>
					<div class="pure-control-group">
						<?php echo form_label('メールアドレス', $email['id']); ?></td>
						<?php echo form_input($email); ?></td>
						<p style="color: red;"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></td>
					</div>
					<div class="pure-control-group">
						<?php echo form_label('パスワードを入力', $password['id']); ?>
						<?php echo form_password($password); ?>
						<p style="color: red;"><?php echo form_error($password['name']); ?></p>
					</div>
					<div class="pure-control-group">
						<?php echo form_label('パスワードを再入力', $confirm_password['id']); ?>
						<?php echo form_password($confirm_password); ?>
						<p style="color: red;"><?php echo form_error($confirm_password['name']); ?></p>
					</div>
				</fieldset>
			</table>
			<?php echo form_hidden('register', 'none'); ?>
			<?php $data = array(
							'name'        => 'register',
							'value'       => '会員登録をする',
							'class'       => 'btn btn-danger'
							); ?>
			<?php echo form_submit($data); ?>
			<?php echo form_close(); ?>
		</div><!-- border-all -->
	</div><!-- minista-main -->
</div><!-- main -->