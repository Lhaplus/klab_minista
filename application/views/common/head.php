<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta property="og:title" content=<?php echo $title ?> >
	<meta property="og:type" content="website">
	<meta property="og:image" content="">
	<meta name="apple-mobile-web-app-title" content="">
	<meta name="keywords" content=<?php echo implode(",", $meta_keywords) ?> >
	<meta name="description" content=<?php echo $meta_description ?> >
	<meta content="width=device-width, user-scalable=yes, initial-scale=1, maximum-scale=2" name="viewport">

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

  <style>
  article, aside, dialog, figure, footer, header,
  hgroup, menu, nav, section { display: block; }
  </style>

	<?php foreach($css_paths as $path): ?>
	<link rel = "stylesheet" href =  <?php echo base_url();?><?php echo $path ?>>
	<?php endforeach; ?>

	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">

	<?php foreach($javascript_paths as $path): ?>
	<script src = '<?php echo base_url();?><?php echo $path ?>' type = 'text/javascript' charset='utf-8' ></script>
	<?php endforeach; ?>

  <link rel="stylesheet" href="<?php echo base_url();?>css/minista/style.css">

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

  <!-- Latest compiled and minified JavaScript -->
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>


	<link rel="shortcut icon" href="<?php base_url() ?>image/favicon.png">
	<link rel="apple-touch-icon" href="<?php base_url() ?>image/apple-touch-icon.png">
	<title><?php echo $title ?></title>
</head>
