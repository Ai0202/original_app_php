<?php
/*----------------------------------------------------------------------------*/
//
//  部署マスタ エンティティクラス
//
//  作成年月日・作成者
//    16/10/21 Atsushi Ikeda
//
/*----------------------------------------------------------------------------*/

class Ent_M_Department {


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
	public $DepartmentId	= null ;	//	INT 	: 部署ID（AutoIncriment）
	public $DepartmentName	= null ;	//	VARCHAR : 部署名
	public $DepartmentNo	= null ;	//	INT     : 部署コード
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
		$this->DepartmentId		= intval( $DataRec["DepartmentId"] ) ;
		$this->DepartmentName	=         $DataRec["DepartmentName"] ;
		$this->DepartmentNo		= intval( $DataRec["DepartmentNo"]) ;
		$this->DelFlag			= intval( $DataRec["DelFlag"] ) ;
		$this->EditTime			=         $DataRec["EditTime"] ;
		$this->EditUser			=		  $DataRec["EditUser"] ;
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
		$SqlStr .=     "m_department " ;
		$SqlStr .= "WHERE " ;
		$SqlStr .=     "DepartmentId = ". $PrimaryKey ." " ;	//	PrimaryKey
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
		$SqlStr  = "UPDATE m_department SET " ;


		$SetStr = "" ;
		make_update_set_sql( $SetStr , "DepartmentName"		, $this->DepartmentName		) ;	//	部署名
		make_update_set_sql( $SetStr , "DepartmentNo"		, $this->DepartmentNo		) ;	//	部署コード
		make_update_set_sql( $SetStr , "DelFlag"			, $this->DelFlag			) ;	//	削除フラグ
		make_update_set_sql( $SetStr , "EditTime"			, $this->EditTime			) ;	//	最終更新日時
		make_update_set_sql( $SetStr , "EditUser"			, $this->EditUser			) ;	//	最終更新者
		$SqlStr .= $SetStr ;

		$SqlStr .= "WHERE " ;
		$SqlStr .=     "DepartmentId = ". $this->DepartmentId ;


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

		make_insert_value_sql( $FeildStr , $ValueStr , "DepartmentName"		, $this->DepartmentName		) ;	//	部署名
		make_insert_value_sql( $FeildStr , $ValueStr , "DepartmentNo"		, $this->DepartmentNo		) ;	//	部署コード
		make_insert_value_sql( $FeildStr , $ValueStr , "EditTime"			, $this->EditTime			) ;	//	最終更新日時
		make_insert_value_sql( $FeildStr , $ValueStr , "EditUser"			, $this->EditUser			) ;	//	最終更新者

		$SqlStr = "INSERT INTO m_department ( ". $FeildStr .") VALUES ( ". $ValueStr .")" ;


		//---------------------------------
		//	SQL発行
		//---------------------------------
		if ( !mysql_query( $SqlStr ) ) {
			return ( 0 ) ;
		}
		//	AutoInclimentの値を取得
		$this->DepartmentId = mysql_insert_id() ;

		return ( $this->DepartmentId ) ;
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
//      削除フラグが 0、DepartmentIdが異なり、かつ部署名と部署コードが一致するものを抽出する。
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
function get_department_by_same_name_or_no(	$DepartmentName	,
											$DepartmentNo,
											$DepartmentId = null	)
{

	//---------------------------------
	//	取得
	//---------------------------------
	$SqlStr  = "SELECT * " ;
	$SqlStr .= "FROM " ;
	$SqlStr .=     "m_department " ;
	$SqlStr .= "WHERE " ;
	$SqlStr .=     "( DepartmentName = '". $DepartmentName ."' OR " ;		//
	$SqlStr .=     "  DepartmentNo = '"  . $DepartmentNo   ."' ) AND " ;	//
	$SqlStr .=     "DelFlag = 0 " ;
	if( $DepartmentId ) {
		$SqlStr .=     "AND DepartmentId != '". $DepartmentId ."'" ;
	}


	//	SQL発行
	$Res = mysql_query( $SqlStr ) ;
	if ( !isset( $Res ) ) return ( null ) ;

	$DepArray = array() ;
	while ( $Row = mysql_fetch_array( $Res ) ) {
		$EntDep = new Ent_M_Department() ;
		$EntDep->set_data( $Row ) ;
		array_push( $DepArray , $EntDep ) ;
	}

	return ( $DepArray ) ;
}




/*----------------------------------------------------------------------------*/
//
//  1.概要
//      削除フラグが0のデータをすべて取得する。
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
function get_all_department( $DataBase ) {
	$SqlStr  = "SELECT * ";
	$SqlStr .= "FROM ";
	$SqlStr .=       "m_department ";
	$SqlStr .= "WHERE ";
	$SqlStr .=       "DelFlag = 0 ORDER BY DepartmentNo ASC";

	$Res = mysql_query( $SqlStr );
	if ( !isset( $Res ) ) return ( null ) ;

	$DepArray = array();
	while( $Row = mysql_fetch_array( $Res ) ) {
		$EntDep = new Ent_M_Department();
		$EntDep->set_data( $Row );
		array_push( $DepArray, $EntDep );
	}
	return( $DepArray );

}

function get_same_department ( $DataBase, $DepartmentName ) {
	$SqlStr  = "SELECT * ";
	$SqlStr .= "FROM ";
	$SqlStr .=       "m_department ";
	$SqlStr .= "WHERE ";
	$SqlStr .=       "DepartmentName = '".$DepartmentName."' AND DelFlag = 0";

	$Res = mysql_query( $SqlStr );
	if ( !isset( $Res ) ) return ( null ) ;

	$DepArray = array();
	$DepArray = mysql_fetch_array( $Res );
	return( $DepArray );
}











?>
