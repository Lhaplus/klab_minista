<div class="side">
	<?php $this->load->view('sidebar/ad') ?>
	<?php if(!isset($category_id)) $category_id = 0; ?>
	<?php $minista_list_new_data['category_id'] = $category_id ; ?>
	<?php $this->load->view('sidebar/minista_list_new', $minista_list_new_data) ?>
	<?php $this->load->view('sidebar/what_minista') ?>
	<?php $this->load->view('sidebar/fd_like_box') ?>
</div><!-- side -->
