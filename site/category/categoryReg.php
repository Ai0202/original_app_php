<?php

	//---------------------------------
	//	設定ファイルの読み込み
	//---------------------------------
	require_once( '../config.php' ) ;	// 設定ファイル
	require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_category.php" ) ;

	//---------------------------------
	//	権限がない場合にリダイレクト
	//---------------------------------
	$login->loginCheck();		               //ログインしてない場合にリダイレクト
	permission_full_or_edit( $URL_PAGE_TOP );  //フルコントールか編集権限がない場合にリダイレクト


	$Msg          = "";
	$EditFlg      = 0;
	$CategoryName = "";
	$ParentId     = "";
	$Selected     = "";

	//---------------------------------
	//	DB接続
	//---------------------------------
	$DataBase = new Data_Base_Class();
	$DataBase->connect();


	//---------------------------------
	//	mCategoryの編集ボタンが押された場合
	//---------------------------------
	if( isset( $_GET['EditFlg'] ) ) {
		$EditFlg     = intval( $_GET['EditFlg'] );
		$EntCategory = new Ent_M_Category();
		$EntCategory->get_by_primary( $_GET['CategoryId'] );

		$CategoryName = $EntCategory->CtgryName;
		$ParentId     = $EntCategory->ParentId;

	}

	//---------------------------------
	//	カテゴリ一覧を取得
	//---------------------------------
	//DBから値を抽出
	$EntCtgryAry = get_parent( $DataBase );



	//---------------------------------
	// 登録ボタン・編集ボタンが押された場合
	//---------------------------------
	if( isset( $_POST['edit'] ) ) {

		//---------------------------------
		//	POSTの値を取得
		//---------------------------------
		$EntCategory            = new Ent_M_Category();
		$EntCategory->CtgryName = $_POST['CategoryName'];
		$EntCategory->ParentId  = intval( $_POST['ParentId'] );
		$EntCategory->CtgryId   = $_POST['CategoryId'];
		$EntCategory->ParentId  = $_POST['ParentId'];
		$EditFlg		 	    = $_POST['EditFlg'];


		//削除ボタンが押された場合に削除フラグを1にする。
		if( $_POST['edit'] == "delete") {
			$EntCategory->DelFlag = 1;
		}

		//---------------------------------
		//	カテゴリ名に重複がないかチェック
		//---------------------------------
		$CtgryAry = get_category_by_same_name( $EntCategory->CtgryName, $EntCategory->CtgryId );
		if( isset( $CtgryAry ) ) {
			foreach( $CtgryAry as $EntCtgry ) {
				//カテゴリ名チェック
				if( $EntCtgry->CtgryName == $EntCategory->CtgryName ) {
					$Msg .="入力されたカテゴリは既に登録されています。<br>";
				}
			$DataBase->close();
			}
			//入力されたカテゴリ名をセット
			$CategoryName = $EntCategory->CtgryName;
		}

		//---------------------------------
		//	DBに値を追加
		//---------------------------------
		if( !$Msg ) {

			$EntCategory->update_or_insert( $DataBase );

			//insert (新規登録)が正常に完了した場合の処理
			if( $EditFlg == 0) {
				$Msg          = "正常に登録されました。";
				$CategoryName = "";
			}

			//update(更新・削除)が正常に完了した場合の処理
			if( $EditFlg == 1) {
				header( "Location:{$URL_SITE_SRC_ROOT}"."/category/mCategory.php" );
				exit();
			}
		}
	}

//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
//　HTML
//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
include($PATH_PAGE_HEADER);		// ヘッダー
include($PATH_PAGE_MENU);		//サイドメニュー

?>

<div class="container">
<h3>機器カテゴリマスタ　登録・編集</h3>
	<div class="msg"><?php echo $Msg ?></div>
	<form class="form-signin" action="categoryReg.php" method="post">

	<input type="hidden" name="CategoryId" id="CategoryId" class="form-control" value="<?php echo $EntCategory->CtgryId; ?>">

	<label for="CategoryName" class="sr-only">機器カテゴリ名<span class="required">必須</span></label></label>
	<input type="text" name="CategoryName" id="CategoryName" class="form-control" required value="<?php echo $CategoryName; ?>"></br>

	<label for="ParentId" class="sr-only">分類</label>
	<select name="ParentId" class="form-control">
		<option value=0>なし</option>

		<?php foreach( $EntCtgryAry as $EntCtgry ): ?>
			<?php if( $EntCtgry->CtgryId == $ParentId ): ?>
				<?php $Selected = "selected"; ?>
			<?php endif; ?>
			<option value="<?php echo $EntCtgry->CtgryId; ?> " <?php echo $Selected; ?>><?php echo $EntCtgry->CtgryName; ?></option>
			<?php $Selected = ""; ?>
		<?php endforeach; ?>

	</select></br></br>


	<!-- 新規登録のボタン-->
	<?php if( $EditFlg == 0 ): ?>
		<input type="hidden" name="EditFlg" value=0>
		<button class="btn btn-lg btn-primary" type="submit" name="edit">登録</button>
	<?php endif; ?>


	<!-- 編集・削除のボタン -->
	<?php if( $EditFlg == 1 ): ?>
		<input type="hidden" name="EditFlg" value=1>
		<button class="btn btn-lg btn-primary" type="submit" name="edit">更新</button>
		<button class="btn btn-lg btn-primary" type="submit" name="edit" value="delete">削除</button>
	<?php endif; ?>
	</form>
</div>

<?php include($PATH_PAGE_FOOTER); ?>
