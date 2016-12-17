<?php
/*----------------------------------------------------------------------------*/
//
//  社員マスタ エンティティクラス
//
//  作成年月日・作成者
//    16/10/21 Atsushi Ikeda
//
/*----------------------------------------------------------------------------*/


class Ent_M_Staff {


	/*--------------------------------------------------------*/
	//  定数定義
	/*--------------------------------------------------------*/
	//---------------------------------
	//	権限 フィールドの値
	//---------------------------------
	const PERMISSION_SUPER_ADMIN			=   1 	;	//	Super管理者(全てできる)
	const PERMISSION_ADMIN					=   2 	;	//	管理者

	public static $PermissionAry	= array( self::PERMISSION_SUPER_ADMIN		,
											 self::PERMISSION_ADMIN				) ;





	/*--------------------------------------------------------*/
	//  メンバー変数定義
	/*--------------------------------------------------------*/
	public $StaffId        	= null ;	//	INT 	: 社員ID（AutoIncriment）
	public $StaffNo        	= null ;	//	VARCHAR : 社員コード
	public $StaffName      	= null ;	//	INT     : 社員名
    public $DepartmentId   	= null ;	//	INT     : 部署ID
	public $DelFlag			= 0 ;		//	TINYINT : 削除フラグ（0:通常、1:削除）
	public $EditTime		= null ;	//	DATETIME: 最終更新日時
	public $EditUser		= null ;	//	INT 	: 最終更新者




	/*----------------------------------------------------------------------------*/
	//
	//  1.イベント名
	//      コンストラクタ
	//
	//  2.機能説明
	//      なし
	//
	/*----------------------------------------------------------------------------*/
	public function __construct(	$DataRec = null	)
	{
		if ( func_num_args() != 1 ) return ;

		//	DBから取得したデータをセットする
		$this->set_data( $DataRec ) ;
	}


	/*----------------------------------------------------------------------------*/
	//
	//  1.概要
	//      クラスにDBから取得した値をセットする
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
	public function set_data(	$DataRec	)
	{
		$this->StaffId			= intval( $DataRec["StaffId"] ) ;
		$this->StaffNo			= intval( $DataRec["StaffNo"] ) ;
		$this->StaffName		=         $DataRec["StaffName"] ;
        $this->DepartmentId		= intval( $DataRec["DepartmentId"] ) ;
		$this->DelFlag			= intval( $DataRec["DelFlag"] ) ;
		$this->EditTime			=         $DataRec["EditTime"] ;
		$this->EditUser			=         $DataRec["EditUser"] ;
	}


	/*----------------------------------------------------------------------------*/
	//
	//  1.概要
	//      指定のPrimaryKeyから、データを取得
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
	public function get_by_primary(	$PrimaryKey	)
	{
		//---------------------------------
		//	DB接続
		//---------------------------------
		$DataBase = new Data_Base_Class() ;
		$DataBase->connect() ;


		//---------------------------------
		//	在庫場所を取得
		//---------------------------------
		$SqlStr  = "SELECT * " ;
		$SqlStr .= "FROM " ;
		$SqlStr .=     "m_staff " ;
		$SqlStr .= "WHERE " ;
		$SqlStr .=     "StaffId = ". $PrimaryKey ." " ;	//	PrimaryKey
		//	SQL発行
		$Res = mysql_query( $SqlStr ) ;
		if ( mysql_num_rows( $Res ) == 0 ) return ( "" ) ;
		$Row = mysql_fetch_assoc( $Res ) ;


		//---------------------------------
		//	自分自身に値をセット
		//---------------------------------
		$this->set_data( $Row ) ;
	}



	/*----------------------------------------------------------------------------*/
	//
	//  1.概要
	//      テーブル更新
	//
	//  2.パラメータ説明
	//      <I> DataBase	: DB接続済みのデータベースクラス
	//
	//  3.戻り値
	//       true : 成功
	//      false : 失敗
	//
	//  4.機能説明
	//      なし
	//
	/*----------------------------------------------------------------------------*/
	public function update( $DataBase )
	{
		//---------------------------------
		//	最終更新日時/最終更新者をセット
		//---------------------------------
		//	最終更新日時
		$this->EditTime = date( "y/m/d H:i:s" ) ;
		//	最終更新者
		$this->EditUser = Login::get_login_UserId_from_session() ;


		//---------------------------------
		//	SQL文作成
		//---------------------------------
		$SqlStr  = "UPDATE m_staff SET " ;


		$SetStr = "" ;
		make_update_set_sql( $SetStr , "StaffNo"			, $this->StaffNo		) ;	//	社員コード
		make_update_set_sql( $SetStr , "StaffName"			, $this->StaffName		) ;	//	氏名
		make_update_set_sql( $SetStr , "DepartmentId"		, $this->DepartmentId	) ;	//	部署ID
		make_update_set_sql( $SetStr , "DelFlag"			, $this->DelFlag		) ;	//	削除フラグ
		make_update_set_sql( $SetStr , "EditTime"			, $this->EditTime		) ;	//	最終更新日時
		make_update_set_sql( $SetStr , "EditUser"			, $this->EditUser		) ;	//	最終更新者
		$SqlStr .= $SetStr ;

		$SqlStr .= "WHERE " ;
		$SqlStr .=     "StaffId = ". $this->StaffId ;


		//---------------------------------
		//	SQL発行
		//---------------------------------
		if ( !mysql_query( $SqlStr ) ) {
			return ( false ) ;
		}

		return ( true ) ;
	}




	/*----------------------------------------------------------------------------*/
	//
	//  1.概要
	//      DBに新規登録
	//
	//  2.パラメータ説明
	//      <I> DataBase	: DB接続済みのデータベースクラス
	//
	//  3.戻り値
	//      納品書ID（0で失敗）
	//
	//  4.機能説明
	//      なし
	//
	/*----------------------------------------------------------------------------*/
	public function insert( $DataBase )
	{
		//---------------------------------
		//	最終更新日時/最終更新者をセット
		//---------------------------------
		//	最終更新日時
		$this->EditTime = date( "y/m/d H:i:s" ) ;
		//	最終更新者
		$this->EditUser = Login::get_login_UserId_from_session() ;


		//---------------------------------
		//	SQL文作成
		//---------------------------------
		$FeildStr = "" ;
		$ValueStr = "" ;

		make_insert_value_sql( $FeildStr , $ValueStr , "StaffNo"			, $this->StaffNo			) ;	//	社員コード
		make_insert_value_sql( $FeildStr , $ValueStr , "StaffName"			, $this->StaffName			) ;	//	社員名
		make_insert_value_sql( $FeildStr , $ValueStr , "DepartmentId"		, $this->DepartmentId		) ;	//	部署ID
		make_insert_value_sql( $FeildStr , $ValueStr , "EditTime"			, $this->EditTime			) ;	//	最終更新日時
		make_insert_value_sql( $FeildStr , $ValueStr , "EditUser"			, $this->EditUser			) ;	//	最終更新者

		$SqlStr = "INSERT INTO m_staff ( ". $FeildStr .") VALUES ( ". $ValueStr .")" ;


		//---------------------------------
		//	SQL発行
		//---------------------------------
		if ( !mysql_query( $SqlStr ) ) {
			return ( 0 ) ;
		}
		//	AutoInclimentの値を取得
		$this->StaffId = mysql_insert_id() ;

		return ( $this->StaffId ) ;
	}












	/*----------------------------------------------------------------------------*/
	//
	//  1.概要
	//      テーブル新規登録or更新
	//
	//  2.パラメータ説明
	//      <I> DataBase	: DB接続済みのデータベースクラス
	//
	//  3.戻り値
	//       true : 成功
	//      false : 失敗
	//
	//  4.機能説明
	//      updateに失敗したら、insertする
	//
	/*----------------------------------------------------------------------------*/
	public function update_or_insert( $DataBase )
	{
		if ( $this->update( $DataBase ) ) return ( true ) ;

		if ( $this->insert( $DataBase ) != 0 ) return ( true ) ;

		return ( false ) ;
	}






}	//	end of class


/*----------------------------------------------------------------------------*/
//
//  1.概要
//      削除フラグが 0、StaffIdが異なり、かつ社員コードと氏名が一致するものを抽出する。
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
function get_staff_by_same_no(	$StaffNo,
								$StaffId	)
{

	//---------------------------------
	//	取得
	//---------------------------------
	$SqlStr  = "SELECT * " ;
	$SqlStr .= "FROM " ;
	$SqlStr .=     "m_staff " ;
	$SqlStr .= "WHERE " ;
	$SqlStr .=     "StaffNo = '". $StaffNo ."' AND " ;		//
	$SqlStr .=     "( DelFlag = 0 AND StaffId != '". $StaffId ."') " ;

	// SQL発行
	$Res = mysql_query( $SqlStr );
	if ( !isset( $Res ) ) return ( null );

	$StfArray = array();
	while( $Row = mysql_fetch_array( $Res ) ) {
		$EntStf = new Ent_M_Staff();
		$EntStf->set_data( $Row );
		array_push( $StfArray, $EntStf );
	}

	return ( $StfArray );
}











?>
