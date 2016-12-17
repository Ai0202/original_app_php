<?php
/*----------------------------------------------------------------------------*/
//
//  機器テーブル エンティティクラス
//
//  作成年月日・作成者
//    16/10/21 Atsushi Ikeda
//
/*----------------------------------------------------------------------------*/
require_once( $PATH_SITE_SRC_ROOT . "/common.php" ) ;


class Ent_T_Machine {


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
	public $MachineId        	= null ;	//	INT 	: 機器ID（AutoIncriment）
	public $Host        	    = null ;	//	VARCHAR : ホスト名
	public $Serial      	    = null ;	//	VARCHAR : シリアル
    public $CategoryId   	    = null ;	//	INT     : カテゴリID
    public $Status      	    = null ;	//	VARCHAR : 貸出状況
	public $Note      	        = null ;	//	VARCHAR : 備考
	public $DelFlag			    = 0 ;		//	TINYINT : 削除フラグ（0:通常、1:削除）
	public $EditTime		    = null ;	//	DATETIME: 最終更新日時
	public $EditUser	      	= null ;	//	INT 	: 最終更新者




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
		$this->MachineId		= intval( $DataRec["MachineId"] ) ;
		$this->Host				=         $DataRec["Host"] ;
		$this->Serial			=         $DataRec["Serial"] ;
        $this->CategoryId		= intval($DataRec["CategoryId"]) ;
		$this->Status			= 		  $DataRec["Status"] ;
        $this->Note				=         $DataRec["Note"] ;
		$this->DelFlag			= intval( $DataRec["DelFlag"] ) ;
		$this->EditTime			=         $DataRec["EditTime"] ;
		$this->EditUser			= intval( $DataRec["EditUser"] ) ;
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
		$SqlStr .=     "t_machine " ;
		$SqlStr .= "WHERE " ;
		$SqlStr .=     "MachineId = ". $PrimaryKey ." " ;	//	PrimaryKey
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
		$SqlStr  = "UPDATE t_machine SET " ;


		$SetStr = "" ;
		make_update_set_sql( $SetStr , "Host"				, $this->Host			) ;	//	ホスト名
		make_update_set_sql( $SetStr , "Serial"				, $this->Serial			) ;	//	シリアル
		make_update_set_sql( $SetStr , "CategoryId"			, $this->CategoryId		) ;	//	機器カテゴリID
		make_update_set_sql( $SetStr , "Status"				, $this->Status			) ;	//	貸出状況
		make_update_set_sql( $SetStr , "Note"				, $this->Note			) ;	//	備考
		make_update_set_sql( $SetStr , "DelFlag"			, $this->DelFlag		) ;	//	削除フラグ
		make_update_set_sql( $SetStr , "EditTime"			, $this->EditTime		) ;	//	最終更新日時
		make_update_set_sql( $SetStr , "EditUser"			, $this->EditUser		) ;	//	最終更新者
		$SqlStr .= $SetStr ;

		$SqlStr .= "WHERE " ;
		$SqlStr .=     "MachineId = ". $this->MachineId ;


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

		make_insert_value_sql( $FeildStr , $ValueStr , "Host"			, $this->Host			) ;	//	ホスト名
		make_insert_value_sql( $FeildStr , $ValueStr , "Serial"			, $this->Serial			) ;	//	シリアル
		make_insert_value_sql( $FeildStr , $ValueStr , "CategoryId"		, $this->CategoryId		) ;	//	機器カテゴリID
		make_insert_value_sql( $FeildStr , $ValueStr , "Status"			, $this->Status			) ;	//	貸出状況
		make_insert_value_sql( $FeildStr , $ValueStr , "Note"			, $this->Note			) ;	//	備考
		make_insert_value_sql( $FeildStr , $ValueStr , "EditTime"		, $this->EditTime		) ;	//	最終更新日時
		make_insert_value_sql( $FeildStr , $ValueStr , "EditUser"		, $this->EditUser		) ;	//	最終更新者

		$SqlStr = "INSERT INTO t_machine ( ". $FeildStr .") VALUES ( ". $ValueStr .")" ;


		//---------------------------------
		//	SQL発行
		//---------------------------------
		if ( !mysql_query( $SqlStr ) ) {
			return ( 0 ) ;
		}
		//	AutoInclimentの値を取得
		$this->MachineId = mysql_insert_id() ;

		return ( $this->MachineId ) ;
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

function get_machine_by_same_name( $Host, $Serial, $MachineId ) {
	//---------------------------------
	//	取得
	//---------------------------------
	$SqlStr  = "SELECT * " ;
	$SqlStr .= "FROM " ;
	$SqlStr .=     "t_machine " ;
	$SqlStr .= "WHERE " ;
	$SqlStr .=     "( Host = '". $Host ."' OR " ;		//
	$SqlStr .=     "  Serial = '"  . $Serial   ."' ) AND " ;	//
	$SqlStr .=     "DelFlag = 0 AND MachineId != '". $MachineId ."'" ;

	// SQL発行
	$Res = mysql_query( $SqlStr );
	if( !isset( $Res ) ) return( null );

	$MachineArray =array();
	while( $Row = mysql_fetch_array( $Res ) ) {
		$EntMachine = new Ent_T_Machine();
		$EntMachine->set_data( $Row );
		array_push( $MachineArray, $EntMachine );
	}
	return( $MachineArray );
}




function get_all_machine( $DataBase ) {
	$SqlStr  = "SELECT * " ;
	$SqlStr .= "FROM " ;
	$SqlStr .=      "t_machine ";
	$SqlStr .= "WHERE ";
	$SqlStr .=      "DelFlag = 0";

	// SQL発行
	$Res = mysql_query( $SqlStr );
	if( !isset( $Res ) ) return( null );

	$MachineArray =array();
	while( $Row = mysql_fetch_array( $Res ) ) {
		$EntMachine = new Ent_T_Machine();
		$EntMachine->set_data( $Row );
		array_push( $MachineArray, $EntMachine );
	}
	return( $MachineArray );
}









?>
