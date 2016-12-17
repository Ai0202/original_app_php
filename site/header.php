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
	<link id="bootstrap-style" href="<?php echo($URL_SITE_ROOT);?>/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo($URL_SITE_ROOT);?>/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link id="base-style" href="<?php echo($URL_SITE_ROOT);?>/css/style.css" rel="stylesheet">
	<link id="base-style-responsive" href="<?php echo($URL_SITE_ROOT);?>/css/style-responsive.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
	<!--自作CSS -->
	<link href="<?php echo($URL_SITE_SRC_ROOT);?>/css/style.css" rel="stylesheet">
	<!-- end: CSS -->


	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<link id="ie-style" href="css/ie.css" rel="stylesheet">
	<![endif]-->

	<!--[if IE 9]>
		<link id="ie9style" href="css/ie9.css" rel="stylesheet">
	<![endif]-->

	<!-- start: Favicon -->
	<link rel="shortcut icon" href="img/favicon.ico">
	<!-- end: Favicon -->

</head>

<body>
<div class="navbar">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="index.php"><span>IT資産管理システム</span></a>

            <div class="nav-no-collapse header-nav">
                <ul class="nav pull-right">

					<!-- 一覧メニュー　-->
                    <li class="dropdown hidden-phone">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            <span>一覧</span>
                        </a>
                        <ul class="dropdown-menu notifications">
                            <li class="dropdown-menu-title">
                                <span>一覧</span>
                                <a href="#refresh"><i class="icon-repeat"></i></a>
                            </li>
							<?php if( $_SESSION["Permission"] == 3 ): ?>	<!-- フルコントロール権限の場合のみ表示-->
	                            <li>
	                                <a href="<?php echo $URL_SITE_SRC_ROOT; ?>/user/mUser.php">
	                                    <span class="icon blue"><i class="icon-user"></i></span>
	                                    <span class="message">ユーザー一覧</span>
	                                </a>
	                            </li>
							<?php endif; ?>
                            <li>
                                <a href="<?php echo $URL_SITE_SRC_ROOT; ?>/staff/mStaff.php">
                                    <span class="icon green"><i class="icon-comment-alt"></i></span>
                                    <span class="message">社員一覧</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $URL_SITE_SRC_ROOT; ?>/department/mDepartment.php">
                                    <span class="icon green"><i class="icon-comment-alt"></i></span>
                                    <span class="message">部署一覧</span>
                                </a>
                            </li>
							<li>
                                <a href="<?php echo $URL_SITE_SRC_ROOT; ?>/category/mCategory.php">
                                    <span class="icon green"><i class="icon-comment-alt"></i></span>
                                    <span class="message">機器カテゴリ一覧</span>
                                </a>
                            </li>
                        </ul>
                    </li>

					<!-- 登録メニュー　-->
					<?php if( $_SESSION["Permission"] != 1 ): ?>	<!-- 編集・フルコントロール権限の場合のみ表示-->
						<li class="dropdown hidden-phone">
	                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
	                            <span>登録</span>
	                        </a>
	                        <ul class="dropdown-menu notifications">
								<li class="dropdown-menu-title">
	                                <span>登録</span>
	                                <a href="#refresh"><i class="icon-repeat"></i></a>
	                            </li>
								<?php if( $_SESSION["Permission"] == 3 ): ?>	<!-- フルコントロール権限の場合のみ表示-->
		                            <li>
		                                <a href="userReg.php">
		                                    <span class="icon blue"><i class="icon-user"></i></span>
		                                    <span class="message">ユーザー</span>
		                                </a>
		                            </li>
								<?php endif; ?>
								<li>
	                                <a href="<?php echo $URL_SITE_SRC_ROOT; ?>/staff/staffReg.php">
	                                    <span class="icon blue"><i class="icon-user"></i></span>
	                                    <span class="message">社員</span>
	                                </a>
	                            </li>
	                            <li>
	                                <a href="<?php echo $URL_SITE_SRC_ROOT; ?>/department/departmentReg.php">
	                                    <span class="icon green"><i class="icon-comment-alt"></i></span>
	                                    <span class="message">部署</span>
	                                </a>
	                            </li>
	                            <li>
	                                <a href="<?php echo $URL_SITE_SRC_ROOT; ?>/category/categoryReg.php">
	                                    <span class="icon green"><i class="icon-comment-alt"></i></span>
	                                    <span class="message">機器カテゴリ</span>
	                                </a>
	                            </li>
	                        </ul>
	                    </li>
					<?php endif; ?>

					<!-- csvインポート　-->
					<?php if( $_SESSION["Permission"] != 1 ): ?>	<!-- 編集・フルコントロール権限の場合のみ表示-->
	                    <li>
	                        <a class="btn" href="<?php echo $URL_SITE_SRC_ROOT; ?>/csvImport.php">
	                            <span class="message">CSVインポート</span>
	                        </a>
	                    </li>
                    <?php endif; ?>

					<!-- アカウント表示　-->
						<li class="dropdown">
	                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
	                            <i class="halflings-icon white user"></i>
								<?php echo $_SESSION["UserName"]; ?>
	                            <span class="caret"></span>
	                        </a>
							<ul class="dropdown-menu">
	                        	<li><a href="logout.php"><i class="halflings-icon off"></i>Logout</a></li>
							</ul>
	                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid-full">
<div class="row-fluid">
