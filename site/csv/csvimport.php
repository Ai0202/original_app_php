<?php

	//---------------------------------
	//	設定ファイルの読み込み
	//---------------------------------
    require_once( '../config.php' ) ;	// 設定ファイル
    require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_t_lend.php" ) ;
    require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_t_machine.php" ) ;
    require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_category.php" ) ;
    require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_staff.php" ) ;
    require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_department.php" ) ;
    $CsvAry = "";
    $Res    = "";
    $SqlStr = "";
    $ErrMsg = "";


    $UPLOAD_PATH = $PATH_SITE_ROOT . "/UploadFile/" ;


	if( isset( $_POST["Btn"] ) ){
        /*
        switch( $_POST[$UpFileName] ) {
            case "Staff"      :$UpFileName = "Staff"; break;
            case "Department" :$UpFileName = "Department"; break;
            case "Category"   :$UpFileName = "Category"; break;
            case "Machine"    :$UpFileName = "Machine"; break;
        }
        */

        if( isset( $_POST['Staff'] ) ){
			$UpFileName = "Staff";
		}
        if( isset( $_POST['Department'] ) ){
			$UpFileName = "Department";
		}
        if( isset( $_POST['Category']) ){
			$UpFileName = "Category";
		}
        if( isset( $_POST['Machine']) ){
			$UpFileName = "Machine";
		}

		//---------------------------------
		//	ファイルのパスを取得
		//---------------------------------
		$TmpName  = $_FILES[$UpFileName]["tmp_name"] ;
    	$FileName = $_FILES[$UpFileName]["name"] ;
    	$FileName = date( "ymd_His" ) ."_". $FileName ;
    	$FilePath = $UPLOAD_PATH . $FileName ;


    	//---------------------------------
    	//	文字コードをutf-8に変換して、格納用フォルダに保存
    	//---------------------------------
    	make_directory( $UPLOAD_PATH ) ;

    	/*
    	$buffer = file_get_contents( $TmpName ) ;
    	file_put_contents( $FilePath , mb_convert_encoding( $buffer , 'UTF-8' , 'auto' ) ) ;
    	unset( $buffer );
    	*/

		if ( !change_file_to_utf8( $TmpName , $FilePath ) ) {
			print_error( "csvファイルの文字コード変換に失敗しました。" ) ;
			exit ;
		}


    	//---------------------------------
    	//	CSVファイル読込み
    	//---------------------------------
		$SplObj = new SplFileObject( $FilePath ) ;
		$SplObj->setFlags( SplFileObject::READ_CSV ) ;


		//---------------------------------
		//	配列に取り込む
		//---------------------------------
		$CsvAry = array() ;
		foreach ( $SplObj as $line ) {
		    //	空行は飛ばす
		    if ( count( $line ) == 0 ) continue ;
		    //	配列に突っ込む
		    $CsvAry[] = $line ;
		}




		//---------------------------------
		//
		//---------------------------------
		switch ( $UpFileName ) {
			case "Staff"		    : import_databese_staff( $CsvAry, $ErrMsg , $WarnMsg ); break;
            case "Department"		: import_databese_department( $CsvAry, $ErrMsg , $WarnMsg ); break;
            case "Category"		    : import_databese_category( $CsvAry, $ErrMsg , $WarnMsg ); break;
            case "Machine"		    : import_databese_machine( $CsvAry, $ErrMsg , $WarnMsg ); break;

		}

	}


//社員インポート
function import_databese_staff( $CsvAry, &$ErrMsg , &$WarnMsg) {
    //---------------------------------
    //	DB接続
    //---------------------------------
    $DataBase = new Data_Base_Class();
    $DataBase->connect();

    foreach( $CsvAry as $Csv ) {
        $EntStaff = new Ent_M_Staff();
        $EntStaff->StaffNo      = $Csv[0];      //社員コード
        $EntStaff->StaffName    = $Csv[1];      //氏名
        $DepAry                 = get_same_department( $DataBase, $Csv[2]);            //部署名が同じ行を取得
        $EntStaff->DepartmentId = $DepAry['DepartmentId'];

        /*
        //オートコミットを0に設定
        $sql = "SET AUTOCOMMIT = 0";
        mysql_query( $sql, $DataBase );


        $Query = "begin";
        mysql_query( $Query, $DataBase );

        $Query = "INSERT INTO m_staff (StaffNo, StaffName) VALUES ($EntStaff->StaffNo, $EntStaff->StaffName)";

        $Query = "commit";
        mysql_query( $Query, $DataBase );

        $Query = "rollback";
        mysql_query( $Query, $DataBase );
        print "ロールバックしました"

        //トランザクション開始
        $DataBase->begin();
        */

        $EntStaff->insert( $DataBase );

        /*
        //ロールバックする
		$DataBase->rollback();
        */
    }



	$ErrMsg .= "adafaseafsef";
	$WarnMsg .= "aaaaaaaaaaaaaaaaaa";
}



//部署インポート
function import_databese_department( $CsvAry, &$ErrMsg , &$WarnMsg) {
    //---------------------------------
    //	DB接続
    //---------------------------------
    $DataBase = new Data_Base_Class();
    $DataBase->connect();

    $i = 1;
    foreach( $CsvAry as $Csv ) {
        $EntDepartment = new Ent_M_Department();
        if( !is_numeric( $Csv[1] ) ) {
            $ErrMsg .= $i."行目の部署コードに数字以外が含まれています。<br>";
        }
        $EntDepartment->DepartmentName = $Csv[0];       //部署名
        $EntDepartment->DepartmentNo   = $Csv[1];       //部署コード

        //部署名・部署コードのエラーチェック
        $DepAry = get_department_by_same_name_or_no( $EntDepartment->DepartmentName, $EntDepartment->DepartmentNo );
        if( isset( $DepAry ) ) {
            foreach( $DepAry as $EntDep ) {
                //部署名チェック
                if( $EntDep->DepartmentName == $Csv[0] ) {
                    $ErrMsg .= $i."行目の部署名が登録済みか、csvの部署名に重複があります。<br>";
                }

                // 部署コードチェック
				if ( $EntDep->DepartmentNo == $Csv[1] ) {
					$ErrMsg .= $i."行目の部署コードが登録済みか、csvの部署コードに重複があります。<br>";
				}
            }
        }
        if( !$ErrMsg ) {
            $EntDepartment->insert( $DataBase );
        }
        $i ++;
    }
    $DataBase->close();



	$WarnMsg .= "aaaaaaaaaaaaaaaaaa";
}


//カテゴリインポート
function import_databese_category( $CsvAry, &$ErrMsg , &$WarnMsg) {
    //---------------------------------
    //	DB接続
    //---------------------------------
    $DataBase = new Data_Base_Class();
    $DataBase->connect();

    foreach( $CsvAry as $Csv ) {
        $EntCtgry = new Ent_M_Category();
        $EntCtgry->CtgryName   = $Csv[1];       //子カテゴリ
        $EntCtgry->insert( $DataBase );         //子カテゴリをデータベースに保存
    }

    foreach( $CsvAry as $Csv ) {
        if( $Csv[0] != "" ) {
            $EntCtgry = new Ent_M_Category();
            $ParentAry   = get_same_category( $DataBase, $Csv[0]);
            $ChildAry    = get_same_category( $DataBase, $Csv[1]);
            $ChildAry['ParentId'] = $ParentAry['CtgryId'];
            $EntCtgry->CtgryId    = $ChildAry['CtgryId'];
            $EntCtgry->ParentId   = $ChildAry['ParentId'];
            $EntCtgry->update( $DataBase );
        }
    }



	$ErrMsg .= "adafaseafsef";
	$WarnMsg .= "aaaaaaaaaaaaaaaaaa";
}



//機器詳細インポート
function import_databese_machine( $CsvAry, &$ErrMsg , &$WarnMsg) {
    //---------------------------------
    //	DB接続
    //---------------------------------
    $DataBase = new Data_Base_Class();
    $DataBase->connect();

    foreach( $CsvAry as $Csv ) {
        $EntMachine = new Ent_T_Machine();
        $EntMachine->Host         = $Csv[0];    //ホスト名
        $EntMachine->Serial       = $Csv[1];    //シリアル
        $DepAry                   = get_same_category( $DataBase, $Csv[2]); //カテゴリ名からカテゴリId取得
        $EntMachine->CategoryId  = $DepAry['CtgryId']; //カテゴリID
        $EntMachine->Status       = $Csv[3];    //貸出状況
        $EntMachine->Note         = $Csv[4];    //備考
        $EntMachine->insert( $DataBase );
    }



	$ErrMsg .= "adafaseafsef";
	$WarnMsg .= "aaaaaaaaaaaaaaaaaa";
}


/*----------------------------------------------------------------------------*/
//
//  1.概要
//      ディレクトリが無ければ作成する
//
//  2.パラメータ説明
//      <I> MakeDir	: ディレクトリのパス
//
//  3.戻り値
//      なし
//
//  4.機能説明
//      なし
//
/*----------------------------------------------------------------------------*/
function make_directory( $MakeDir )
{
	if ( file_exists( $MakeDir ) ) return ;

	mkdir( $MakeDir , 0755 , true ) ;
	chmod( $MakeDir , 0755 ) ;
}





//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
//　HTML
//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
// ヘッダー
include($PATH_PAGE_HEADER);
include($PATH_PAGE_MENU);

?>

<?php echo $ErrMsg; ?>

<form action="" method="post" enctype="multipart/form-data">
  社員：<br />
  <input type="hidden" name="Staff" value="staff" />
  <input type="file" name="Staff" size="30" /><br />
  <br />
  <input type="submit" name="Btn" value="アップロード" />
</form>

<form action="" method="post" enctype="multipart/form-data">
  部署：<br />
  <input type="hidden" name="Department" value="Department" />
  <input type="file" name="Department" size="30" /><br />
  <br />
  <input type="submit" name="Btn" value="アップロード" />
</form>

<form action="" method="post" enctype="multipart/form-data">
  機器カテゴリ：<br />
  <input type="hidden" name="Category" value="Category" />
  <input type="file" name="Category" size="30" /><br />
  <br />
  <input type="submit" name="Btn" value="アップロード" />
</form>

<form action="" method="post" enctype="multipart/form-data">
  機器詳細：<br />
  <input type="hidden" name="Machine" value="Machine" /><br />
  <input type="file" name="Machine" size="30" /><br />
  <br />
  <input type="submit" name="Btn" value="アップロード" />
</form>

<?php include($PATH_PAGE_FOOTER); ?>



















<?php

/*----------------------------------------------------------------------------*/
//
//  1.概要
//      ファイルのエンコードを変換して、格納する
//
//  2.パラメータ説明
//      <I>
//
//  3.戻り値
//       1 : 成功
//      -1 : 失敗
//
//  4.機能説明
//      なし
//
/*----------------------------------------------------------------------------*/
function change_file_to_utf8( $TmpName , $FilePath )
{
    setlocale( LC_ALL , 'ja_JP.UTF-8' ) ;

    //	ファイルの読み込み
    $buffer = file_get_contents( $TmpName ) ;

    //	文字コードを取得
    $encoding = mb_detect_encoding( $buffer , "ASCII,JIS,UTF-8,CP51932,SJIS-win" , true ) ;
    if ( !$encoding ) {
        // 文字コードの自動判定に失敗
        unset( $buffer ) ;
        return ( false ) ;
    }

    //	文字コードを変換してファイル書出し
    file_put_contents( $FilePath , mb_convert_encoding( $buffer , 'UTF-8' , $encoding ) ) ;
    unset( $buffer ) ;

    return ( true ) ;
}



?>
