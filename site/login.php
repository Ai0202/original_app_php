<?php
	//---------------------------------
	//	設定ファイルの読み込み
	//---------------------------------
	require_once( './config.php' ) ;	// 設定ファイル
	$errorMessage = "";


	//---------------------------------
	// ログインボタンが押された場合
	//---------------------------------
	if (isset($_POST["login"])) {

		//空欄の有無をチェック
		if (empty($_POST["Email"])) {
	  	  	$errorMessage .= "Emailが未入力です。<br>";
	  	}
	  	if (empty($_POST["Password"])) {
	  		$errorMessage .= "パスワードが未入力です。<br>";
	  	}

		//パスワードが正しいかチェック、正しければログイン
		if(empty($errorMessage)) {
			$login->LoginTry();
			if($login->login_error ==1) {
				$errorMessage .="EmailかPasswordに誤りがあります。";
			}
		}
  	 }

?>
<!DOCTYPE html>
<html lang="ja">
<head>

	<!-- start: Meta -->
	<meta charset="utf-8">
	<title>IT資産管理</title>
	<meta name="description" content="Bootstrap Metro Dashboard">
	<meta name="author" content="Dennis Ji">
	<meta name="keyword" content="Metro, Metro UI, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
	<!-- end: Meta -->

	<!-- start: Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- end: Mobile Specific -->

	<!-- start: CSS -->
	<link id="bootstrap-style" href="<?php echo($URL_SITE_ROOT); ?>/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo($URL_SITE_ROOT); ?>/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link id="base-style" href="<?php echo($URL_SITE_ROOT); ?>/css/style.css" rel="stylesheet">
	<link id="base-style-responsive" href="<?php echo($URL_SITE_ROOT); ?>/css/style-responsive.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
	<!--自作CSS -->
	<link href="<?php echo($URL_SITE_SRC_ROOT);?>/css/style.css" rel="stylesheet">
	<!-- end: CSS -->


	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<link id="ie-style" href="<?php echo($URL_SITE_ROOT); ?>/css/ie.css" rel="stylesheet">
	<![endif]-->

	<!--[if IE 9]>
		<link id="ie9style" href="<?php echo($URL_SITE_ROOT); ?>/css/ie9.css" rel="stylesheet">
	<![endif]-->

	<!-- start: Favicon -->
	<link rel="shortcut icon" href="<?php echo($URL_SITE_ROOT); ?>/img/favicon.ico">
	<!-- end: Favicon -->

<!--			<style type="text/css">
			body { background: url(<?php echo($URL_SITE_ROOT); ?>/img/bg-login.jpg) !important; }
		</style>
-->


</head>

<body>
		<div class="container-fluid-full">
		<div class="row-fluid">

		<div class="row-fluid">
			<div class="login-box">
				<div class="icons">
					<h2>Login to your account</h2>
					<form class="form-horizontal" action="login.php" method="post">
						<fieldset>

						<!--エラーメッセージ -->
						<div class="errorMsg left"><?php echo $errorMessage ?></div>

							<div class="input-prepend" title="email">
								<span class="add-on"><i class="halflings-icon user"></i></span>
								<input class="input-large span10" name="Email" id="Email" type="Email" placeholder="Email"/>
							</div>
							<div class="clearfix"></div>

							<div class="input-prepend" title="Password">
								<span class="add-on"><i class="halflings-icon lock"></i></span>
								<input class="input-large span10" name="Password" id="Password" type="Password" placeholder="Password"/>
							</div>
							<div class="clearfix"></div>

							<div class="button-login">
								<button type="submit" class="btn btn-primary" id="login" name="login">Login</button>
							</div>
							<div class="clearfix"></div>
					</form>
					<hr>
				</div><!--/span-->
			</div><!--/row-->

		</div><!--/.fluid-container-->
		</div><!--/fluid-row-->

	<!-- start: JavaScript-->

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery-1.9.1.min.js"></script>
	<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery-migrate-1.0.0.min.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery-ui-1.10.0.custom.min.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.ui.touch-punch.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/modernizr.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/bootstrap.min.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.cookie.js"></script>

		<script src='<?php echo($URL_SITE_ROOT); ?>/js/fullcalendar.min.js'></script>

		<script src='<?php echo($URL_SITE_ROOT); ?>/js/jquery.dataTables.min.js'></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/excanvas.js"></script>
	<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.flot.js"></script>
	<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.flot.pie.js"></script>
	<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.flot.stack.js"></script>
	<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.flot.resize.min.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.chosen.min.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.uniform.min.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.cleditor.min.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.noty.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.elfinder.min.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.raty.min.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.iphone.toggle.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.uploadify-3.1.min.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.gritter.min.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.imagesloaded.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.masonry.min.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.knob.modified.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/jquery.sparkline.min.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/counter.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/retina.js"></script>

		<script src="<?php echo($URL_SITE_ROOT); ?>/js/custom.js"></script>
	<!-- end: JavaScript-->

</body>
</html>
