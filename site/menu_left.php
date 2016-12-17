<!-- start: Main Menu -->
<div id="sidebar-left" class="span2">
	<div class="nav-collapse sidebar-nav">
		<ul class="nav nav-tabs nav-stacked main-menu">
			<!-- すべての権限で表示されるメニュー　-->
			<li><a href="<?php echo $URL_PAGE_TOP ?>"><i class="icon-folder-close-alt"></i><span class="hidden-tablet">TOP</span></a></li>
			<li><a href="<?php echo $URL_SITE_SRC_ROOT ?>/department/mDepartment.php"><i class="icon-folder-close-alt"></i><span class="hidden-tablet"> 部署一覧</span></a></li>
			<li><a href="<?php echo $URL_SITE_SRC_ROOT ?>/staff/mStaff.php"><i class="icon-folder-close-alt"></i><span class="hidden-tablet"> 社員一覧</span></a></li>
			<li><a href="<?php echo $URL_SITE_SRC_ROOT ?>/category/mCategory.php"><i class="icon-folder-close-alt"></i><span class="hidden-tablet"> 機器カテゴリ一覧</span></a></li>

			<!-- 編集・フルコントロールの権限で表示されるメニュー　-->
			<?php if( $_SESSION["Permission"] != 1 ): ?>
				<li><a href="<?php echo $URL_SITE_SRC_ROOT ?>/machine/machineReg.php"><i class="icon-folder-close-alt"></i><span class="hidden-tablet"> 機器詳細登録</span></a></li>
				<li><a href="<?php echo $URL_SITE_SRC_ROOT ?>/department/departmentReg.php"><i class="icon-folder-close-alt"></i><span class="hidden-tablet"> 部署登録</span></a></li>
				<li><a href="<?php echo $URL_SITE_SRC_ROOT ?>/staff/staffReg.php"><i class="icon-folder-close-alt"></i><span class="hidden-tablet"> 社員登録</span></a></li>
				<li><a href="<?php echo $URL_SITE_SRC_ROOT ?>/category/categoryReg.php"><i class="icon-folder-close-alt"></i><span class="hidden-tablet"> 機器カテゴリ登録</span></a></li>
				<li><a href="<?php echo $URL_SITE_SRC_ROOT ?>/csv/csvimport.php"><i class="icon-folder-close-alt"></i><span class="hidden-tablet"> CSVインポート</span></a></li>
			<?php endif; ?>

			<!-- フルコントロールの権限で表示されるメニュー　-->
 			<?php if( $_SESSION["Permission"] == 3 ): ?>
				<li><a href="<?php echo $URL_SITE_SRC_ROOT ?>/user/mUser.php"><i class="icon-folder-close-alt"></i><span class="hidden-tablet"> ユーザー一覧</span></a></li>
				<li><a href="<?php echo $URL_SITE_SRC_ROOT ?>/user/userReg.php"><i class="icon-folder-close-alt"></i><span class="hidden-tablet"> ユーザー登録</span></a></li>
			<?php endif; ?>

			<!-- すべての権限で表示されるメニュー　-->
			<li><a href="<?php echo $URL_SITE_SRC_ROOT ?>/logout.php"><i class="icon-folder-close-alt"></i><span class="hidden-tablet"> ログアウト</span></a></li>
		</ul>
	</div>
</div>
<!-- end: Main Menu -->

<noscript>
	<div class="alert alert-block span10">
		<h4 class="alert-heading">Warning!</h4>
		<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
	</div>
</noscript>
<div id="content" class="span10">
