<?php

	//---------------------------------
	//	設定ファイルの読み込み
	//---------------------------------
	require_once( '../config.php' ) ;	// 設定ファイル
	require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_t_machine.php" ) ;
	require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_category.php" ) ;



	//---------------------------------
	//	権限がない場合にリダイレクト
	//---------------------------------
	$login->loginCheck();		               //ログインしてない場合にリダイレクト
	permission_full_or_edit( $URL_PAGE_TOP );  //フルコントールか編集権限がない場合にリダイレクト



	$Msg          = "";
	$EditFlg      = 0;
	$Host		  = "";
	$serial		  = "";
	$Note         = "";

	//---------------------------------
	//	DB接続
	//---------------------------------
	$DataBase = new Data_Base_Class();
	$DataBase->connect();


	//---------------------------------
	//	tMachineの編集ボタンが押された場合
	//---------------------------------
	if( isset( $_GET['EditFlg'] ) ) {
	}

	//---------------------------------
	//	カテゴリ一覧を取得
	//---------------------------------
	//DBから値を抽出
	$ChildCtgryAry = get_child( $DataBase );



	//---------------------------------
	// 登録ボタン・編集ボタンが押された場合
	//---------------------------------
	if( isset( $_POST['edit'] ) ) {

		//---------------------------------
		//	POSTの値を取得
		//---------------------------------
		$EntMachine              = new Ent_T_Machine();
		$EntMachine->Host        = $_POST['Host'];
		$EntMachine->Serial      = $_POST['Serial'];
		$EntMachine->CategoryId  = $_POST['CategoryId'];
		$EntMachine->Status      = $_POST['Status'];
		$EntMachine->Note        = $_POST['Note'];
		$EditFlg		 	     = $_POST['EditFlg'];


		//削除ボタンが押された場合に削除フラグを1にする。
		if( $_POST['edit'] == "delete") {
			$EntMachine->DelFlag = 1;
		}

		//---------------------------------
		//	カテゴリ名に重複がないかチェック
		//---------------------------------
		$MachineAry = get_machine_by_same_name( $EntMachine->Host, $EntMachine->Serial, $EntMachine->MachineId );
		if( isset( $MachineAry ) ) {
			foreach( $MachineAry as $EntMchn ) {

				//シリアル
				if( $EntMchn->Serial == $EntMachine->Serial) {
					$Msg .="入力されたシリアルは既に登録されています。<br>";
				}

				//ホスト名
				if( $EntMchn->Host == $EntMachine->Host ) {
					$Msg .="入力されたホスト名は既に登録されています。<br>";
				}

			$DataBase->close();
			}

			//入力されたホスト名・シリアル・備考をセット
			$Host      = $EntMachine->Host;
			$serial    = $EntMachine->Serial;
			$Note      = $EntMachine->Note;
		}

		//---------------------------------
		//	DBに値を追加
		//---------------------------------
		if( !$Msg ) {

			$EntMachine->update_or_insert( $DataBase );


			//insert (新規登録)が正常に完了した場合の処理
			if( $EditFlg == 0) {
				$Msg          = "正常に登録されました。";
				$Host      = "";
				$serial    = "";
			}

			//update(更新・削除)が正常に完了した場合の処理
			if( $EditFlg == 1) {
				header( "Location:{$URL_SITE_SRC_ROOT}"."/machine/tMachine.php" );
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
<h3>機器　登録・編集</h3>
	<div class="msg"><?php echo $Msg ?></div>
	<form class="form-signin" action="machineReg.php" method="post">

	<input type="hidden" name="MachineId" id="MachineId" class="form-control" value="<?php echo $EntMachine->MachineId; ?>">

    <label for="Serial" class="sr-only">シリアル<span class="required">必須</span></label>
	<input type="text" name="Serial" id="Serial" class="form-control" required value="<?php echo $serial; ?>"></br>

	<label for="Host" class="sr-only">ホスト名</label>
	<input type="text" name="Host" id="Host" class="form-control" value="<?php echo $Host; ?>"></br>

	<label for="CategoryId" class="sr-only">分類<span class="required">必須</span></label>
	<select name="CategoryId" class="form-control">
		<?php foreach( $ChildCtgryAry as $ChildCtgry ): ?>
			<option value="<?php echo $ChildCtgry->CtgryId ?>"><?php echo $ChildCtgry->CtgryName; ?></option>
		<?php endforeach; ?>
	</select></br></br>

	<select name="Status" required>
		<option value="在庫">在庫</option>
		<option value="貸出中">貸出中</option>
		<option value="修理中">修理中</option>
		<option value="廃棄">廃棄</option>
	</select>

    <label for="Note" class="sr-only">備考</label>
	<textarea name="Note" class="form-control" value="<?php echo $Note; ?>"></textarea></br>

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
