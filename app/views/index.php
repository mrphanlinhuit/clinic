<!DOCTYPE html>
<html data-ng-app="App" lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<title>Clinic Management</title>
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
	</head>
	<body data-ng-controller="AppCtrl" id="app" data-custom-background="" data-off-canvas-nav="">
		<!--[if lt IE 9]>
		<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->
		<header data-ng-hide="isHide()" data-ng-cloak="" class="no-print">
			<div data-ng-include=" 'html/header.html' " id="header" class="top-header"></div>
			<aside data-ng-include=" 'html/nav.html' " id="nav-container"></aside>
		</header>
		<div class="message" ng-include=" 'html/flash.html' "></div>
		<div class="view-container">
			<div data-ng-view="" id="content" class="animate-fade-up"></div>
		</div>
	</body>
	<script src="js/vendor.js"></script>
	<script src="js/ui.js"></script>
	<script src="js/main.js"></script>
	<script>
		angular.module("App").constant("CSRF_TOKEN", "<?php echo csrf_token(); ?>");
	</script>
</html>
