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
	<link href = <?php echo $path ?> ref = 'stylesheet'></script>
	<?php endforeach; ?>

	<?php foreach($javascript_paths as $path): ?>
	<script src = <?php echo $path ?> type = 'javascript'></script>
	<?php endforeach; ?>

	<title><?php $title ?></title>
</head>
