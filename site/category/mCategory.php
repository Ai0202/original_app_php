<?php

//---------------------------------
//	設定ファイルの読み込み
//---------------------------------
require_once('../config.php' ) ;	// 設定ファイル
require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_category.php" ) ;
require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_join.php" ) ;

$Selected = "";


//---------------------------------
//	権限がない場合にリダイレクト
//---------------------------------
$login->loginCheck();		               //ログインしてない場合にリダイレクト



$DataBase = new Data_Base_Class();
$DataBase->connect();

//DBから値を抽出
//カテゴリ一覧
$ParentAry   = get_parent( $DataBase );

//親カテゴリと子カテゴリが紐付いた一覧
$EntCtgryAry = get_child_and_parent( $DataBase );



//---------------------------------
//	カテゴリでフィルタをかけた場合の処理
//---------------------------------
if( isset( $_POST['Search'] ) ) {
	$CtgryId = $_POST['Category'];		//選択されたカテゴリIDを取得
}


//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
//　HTML
//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
include($PATH_PAGE_HEADER);		// ヘッダー
include($PATH_PAGE_MENU);		//サイドメニュー

?>

<div class="container">
	<h1>機器カテゴリマスタ</h1>

<?php
	//print( "<pre>\n" ) ;
	//var_dump( $EntCtgryAry ) ;
	//print( "</pre>\n" ) ;
 ?>

	<form class="form-signin" action="mCategory.php" method="post">
		<select name="Category">
			<option value=0> </option>
			<?php foreach( $ParentAry as $Parent ): ?>
				<?php if( $CtgryId == $Parent->CtgryId ): ?>
					<?php $Selected = "selected"; ?>
				<?php endif; ?>
				<option value="<?php echo $Parent->CtgryId; ?>" <?php echo $Selected; ?> ><?php echo $Parent->CtgryName; ?></option>
				<?php $Selected = ""; ?>
			<?php endforeach; ?>
		</select>
		<button class="btn btn-lg btn-primary" type="submit" name="Search">検索</button>
	</form>

	<table width = "400" border="1">
		<tr>
			<th>　</th>
			<th>カテゴリ</th>
			<th>機器名称</th>
		</tr>
		<?php foreach( $EntCtgryAry as $EntCtgry ): ?>
			<!-- カテゴリでフィルタをかけた場合に選択したカテゴリ以外はとばす-->
			<?php if( isset( $CtgryId ) && $CtgryId != 0 ): ?>
				<?php if( $CtgryId != $EntCtgry['ParentId'] ): ?>
					<?php continue; ?>
				<?php endif; ?>
			<?php endif; ?>
			<tr>
				<td>
					<a href="categoryReg.php?CategoryId=<?php echo $EntCtgry['ChildId']; ?>&EditFlg=1">編集</a>
				</td>
				<td>
					<?php echo $EntCtgry['ParentName']; ?>
				</td>
				<td>
					<?php echo $EntCtgry['ChildName']; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>


<?php include($PATH_PAGE_FOOTER); ?>
