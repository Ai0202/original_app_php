<?php
	//---------------------------------
	//	設定ファイルの読み込み
	//---------------------------------
	require_once( '../config.php' ) ;// 設定ファイル
	require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_department.php" ) ;



	//---------------------------------
	//	権限がない場合にリダイレクト
	//---------------------------------
	$login->loginCheck();		               //ログインしてない場合にリダイレクト
	permission_full_or_edit( $URL_PAGE_TOP );  //フルコントールか編集権限がない場合にリダイレクト



	$Msg = "";
	$DepartmentName ="";
	$DepartmentNo ="";
	$EditFlg = 0;


	//---------------------------------
	//	DB接続
	//---------------------------------
	$DataBase = new Data_Base_Class();
    $DataBase->connect();


	//---------------------------------
	//	mDepatmentの編集ボタンが押された場合
	//---------------------------------
	if(isset($_GET["EditFlg"])) {
		$EditFlg 	   = intval($_GET["EditFlg"]);
		$EntDepartment = new Ent_M_Department() ;
		$EntDepartment->get_by_primary($_GET["DepartmentId"]);

		$DepartmentName = $EntDepartment->DepartmentName;
		$DepartmentNo 	= $EntDepartment->DepartmentNo;
	}


	//---------------------------------
	// 登録ボタン・編集ボタンが押された場合
	//---------------------------------
	if( isset( $_POST['edit'] ) ) {

		//---------------------------------
		//	POSTの値を取得
		//---------------------------------
		$EntDepartment                 = new Ent_M_Department() ;
		$EntDepartment->DepartmentName = $_POST['DepartmentName'] ;
		$EntDepartment->DepartmentNo   = intval( $_POST['DepartmentNo'] ) ;
		$EntDepartment->DepartmentId   = $_POST['DepartmentId'] ;
		$EditFlg					   = $_POST['EditFlg'];


		//削除ボタンが押された場合に削除フラグを1にする。
		if($_POST['edit'] == "delete") {
			$EntDepartment->DelFlag = 1;
		}


		//---------------------------------
		//	部署名、部署コードに重複がないかチェック
		//---------------------------------
		$DepAry = get_department_by_same_name_or_no( $EntDepartment->DepartmentName , $EntDepartment->DepartmentNo , $EntDepartment->DepartmentId ) ;
		if ( isset( $DepAry ) ) {
			foreach ( $DepAry as $EntDep ) {
				//部署名チェック
				if ( $EntDep->DepartmentName == $EntDepartment->DepartmentName ) {
					$Msg .= "入力された部署は既に登録されています。<br>";
				}

				// 部署コードチェック
				if ( $EntDep->DepartmentNo == $EntDepartment->DepartmentNo ) {
					$Msg .= "入力された部署コードは既に登録されています。<br>";
				}
				$DataBase->close();
			}

			//入力された部署名・部署コードをセット
			$DepartmentName = $EntDepartment->DepartmentName;
			$DepartmentNo   = $EntDepartment->DepartmentNo;
		}


		//---------------------------------
		//	DBに値を追加
		//---------------------------------
		if ( !$Msg ) {

			$EntDepartment->update_or_insert($DataBase) ;

			//insert（新規登録)が正常に完了した場合の処理
			if( $EditFlg == 0 ) {
				$Msg = "正常に登録されました。";
				$DepartmentName ="";
				$DepartmentNo ="";
			}

			//update(更新・削除)が正常に完了した場合の処理
			if( $EditFlg == 1) {
				header("location:{$URL_SITE_SRC_ROOT}"."/department/mDepartment.php");
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
	<h3>部署マスタ　登録</h3>
		<div class="msg"><?php echo $Msg ?></div>
        <form class="form-signin" action="departmentReg.php" method="post">

			<input type="hidden" name="DepartmentId" id="DepartmentId" class="form-control" value="<?php echo( $EntDepartment->DepartmentId ); ?>">

        	<label for="DepartmentName" class="sr-only">部署名<span class="required">必須</span></label>
        	<input type="text" name="DepartmentName" id="DepartmentName" class="form-control" required value="<?php echo( $DepartmentName ); ?>"></br></br>

        	<label for="DepartmentNo" class="sr-only">部署コード<span class="required">必須</span></label>
        	<input type="number" name="DepartmentNo" id="DepartmentNo" class="form-control" required value="<?php echo( $DepartmentNo ); ?>"></br></br>

			<!-- 新規登録のボタン -->
			<?php
				if($EditFlg == 0) {
			?>
			<input type="hidden" name="EditFlg" value=0 >
			<button class="btn btn-lg btn-primary" type="submit" name="edit">登録</button>
			<?php
				}
			?>


			<!-- 編集・削除のボタン -->
			<?php
				if($EditFlg == 1) {
			?>
			<input type="hidden" name="EditFlg" value=1 >
			<button class="btn btn-lg btn-primary" type="submit" name="edit">更新</button>
			<button class="btn btn-lg btn-primary" type="submit" name="edit" value="delete">削除</button>
			<?php
				}
			?>
		</form>
	</div>

<script>

</script>



<?php include($PATH_PAGE_FOOTER); ?>
