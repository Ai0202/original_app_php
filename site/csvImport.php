<?php

	//---------------------------------
	//	設定ファイルの読み込み
	//---------------------------------
	require_once( './config.php' ) ;	// 設定ファイル


	if ( is_uploaded_file( $_FILES["Staff"]["tmp_staff"] ) ) {

	}



//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
//　HTML
//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
// ヘッダー
include($PATH_PAGE_HEADER);
include($PATH_PAGE_MENU);

?>

<form action="upload.php" method="post" enctype="multipart/form-data">
  社員：<br />
  <input type="file" name="Staff" size="30" /><br />
  <br />
  <input type="submit" value="アップロード" />
</form>

<form action="upload.php" method="post" enctype="multipart/form-data">
  部署：<br />
  <input type="file" name="Department" size="30" /><br />
  <br />
  <input type="submit" value="アップロード" />
</form>

<form action="upload.php" method="post" enctype="multipart/form-data">
  機器カテゴリ：<br />
  <input type="file" name="Category" size="30" /><br />
  <br />
  <input type="submit" value="アップロード" />
</form>

<form action="upload.php" method="post" enctype="multipart/form-data">
  機器詳細：<br />
  <input type="file" name="Machine" size="30" /><br />
  <br />
  <input type="submit" value="アップロード" />
</form>

<?php include($PATH_PAGE_FOOTER); ?>
