<?php
	//---------------------------------
	//	設定ファイルの読み込み
	//---------------------------------
	require_once( '../config.php' ) ;// 設定ファイル
	require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_staff.php" ) ;
	require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_department.php" ) ;

	$Msg            = "";
	$StaffId		= "";
	$StaffNo        = "";
	$StaffName      = "";
	$EditFlg        = 0;
	$DepartmentId   = "";
	$DepartmentName = "";
	$Selected       = "";


	//---------------------------------
	//	DB接続
	//---------------------------------
	$DataBase = new Data_Base_Class();
	$DataBase->connect();



	//---------------------------------
	//	部署一覧を取得
	//---------------------------------
	//DBから値を抽出
	$EntDepartment = new Ent_M_Department();
	$EntDepAry     = get_all_department( $DataBase );



	//---------------------------------
	//	mStaffの編集ボタンが押された場合
	//---------------------------------
	if( isset( $_GET['EditFlg'] ) ) {
		$EditFlg  = intval( $_GET['EditFlg'] );
		$EntStaff = new Ent_M_Staff();
		$EntStaff->get_by_primary( $_GET['StaffId'] );

		$StaffId		= $EntStaff->StaffId;
		$StaffNo        = $EntStaff->StaffNo;
		$StaffName      = $EntStaff->StaffName;
		$DepartmentId   = $EntStaff->DepartmentId;
	}




	//---------------------------------
	// 登録ボタン・編集ボタンが押された場合
	//---------------------------------
	if( isset( $_POST['edit'] ) ) {

		//---------------------------------
		//	POSTの値を取得
		//---------------------------------
		$EntStaff               = new Ent_M_Staff();
		$EntStaff->StaffNo      = intval( $_POST['StaffNo'] );
		$EntStaff->StaffName    = $_POST['StaffName'];
		$EntStaff->DepartmentId = intval( $_POST['DepartmentId'] );
		$EntStaff->StaffId      = $_POST['StaffId'];
		$EditFlg		        = $_POST['EditFlg'];

		//削除ボタンが押された場合に削除フラグを1にする。
		if( $_POST['edit'] == "delete") {
			$EntStaff->DelFlag = 1;
		}

		//---------------------------------
		//	社員コード、氏名に重複がないかチェック
		//---------------------------------
		$StfAry = get_staff_by_same_no( $EntStaff->StaffNo, $EntStaff->StaffId);
		if( isset( $StfAry ) ) {
			foreach( $StfAry as $EntStf ) {
				//社員コードチェック
				if( $EntStf->StaffNo == $EntStaff->StaffNo ) {
					$Msg .="入力された社員コードは既に登録されています。<br>";
				}
				$DataBase->close();
			}
			//入力された社員コード・氏名をセット
			$StaffNo   = $EntStaff->StaffNo;
			$StaffName = $EntStaff->StaffName;
		}

		//---------------------------------
		//	DBに値を追加
		//---------------------------------
		if( !$Msg ) {

			$EntStaff->update_or_insert( $DataBase );

			//insert（新規登録)が正常に完了した場合の処理
			if( $EditFlg == 0 ) {
				$Msg = "正常に登録されました。";
				$StaffNo   = "";
				$StaffName = "";
			}

			//update(更新・削除)が正常に完了した場合の処理
			if( $EditFlg == 1) {
				header("location:{$URL_SITE_SRC_ROOT}"."/staff/mStaff.php");
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
<h3>社員マスタ　登録・編集</h3>
	<div class="msg"><?php echo $Msg ?></div>
	<form class="form-signin" action="staffReg.php" method="post">

	<input type="hidden" name="StaffId" id="StaffId" class="form-control" value="<?php echo( $StaffId ); ?>">

	<label for="StaffNo" class="sr-only">社員コード<span class="required">必須</span></label>
	<input type="number" name="StaffNo" id="StaffNo" class="form-control" required autofocus value="<?php echo ( $StaffNo ); ?>"></br>

	<label for="StaffName" class="sr-only">氏名<span class="required">必須</span></label>
	<input type="text" name="StaffName" id="StaffName" class="form-control" required  value="<?php echo ( $StaffName ); ?>">

	<label for="DepartmentName" class="sr-only">部署名<span class="required">必須</span></label>
	<select name="DepartmentId" class="form-control" required>


		<?php foreach( $EntDepAry as $EntDep ): ?>
			<?php if( $EntDep->DepartmentId == $DepartmentId ) : ?>
				<?php $Selected = "Selected"; ?>
			<?php endif; ?>
			<option value="<?php echo $EntDep->DepartmentId; ?> " <?php echo $Selected; ?>><?php echo $EntDep->DepartmentName ; ?></option>
			<?php $Selected = ""; ?>
		<?php endforeach; ?>

	</select></br>


	<!-- 新規登録のボタン -->
	<?php
		if( $EditFlg == 0 ) {
	?>
		<input type='hidden' name='EditFlg' value=0 >
		<button class="btn btn-lg btn-primary" type="submit" name="edit">登録</button>
	<?php
		}
	?>


	<!-- 編集・削除のボタン -->
	<?php
		if( $EditFlg == 1 ) {
	?>
		<input type="hidden" name="EditFlg" value=1 >
		<button class="btn btn-lg btn-primary" type="submit" name="edit">更新</button>
		<button class="btn btn-lg btn-primary" type="submit" name="edit" value="delete">削除</button>
	<?php
		}
	?>
</form>
</div>

<?php include($PATH_PAGE_FOOTER); ?>
