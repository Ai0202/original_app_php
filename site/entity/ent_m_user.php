<?php
/*----------------------------------------------------------------------------*/
//
//  ユーザーマスタ エンティティクラス
//
//  作成年月日・作成者
//    16/10/21 Atsushi Ikeda
//
/*----------------------------------------------------------------------------*/
require_once( $PATH_SITE_SRC_ROOT . "/common.php" ) ;


class Ent_M_User {


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
	public $UserId			= null ;	//	INT 	: ユーザーID（AutoIncriment）
	public $UserName		= null ;	//	VARCHAR : ユーザー名
	public $Email			= null ;	//	VARCHAR : ユーザーメールアドレス
	public $Password		= null ;	//	VARCHAR : パスワード
	public $Permission		= null ;	//	TINYINT : 権限
	public $PagePermit		= null ;	//	TINYINT : ページ権限
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
		$this->UserId			= intval( $DataRec["UserId"] ) ;
		$this->UserName			=         $DataRec["UserName"] ;
		$this->Email			=         $DataRec["Email"] ;
		$this->Password			=         $DataRec["Password"] ;
		$this->Permission		= intval( $DataRec["Permission"] ) ;
		$this->PagePermit		= intval( $DataRec["PagePermit"]) ;
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
		$SqlStr .=     "m_user " ;
		$SqlStr .= "WHERE " ;
		$SqlStr .=     "UserId = ". $PrimaryKey ." " ;	//	PrimaryKey
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
	//      ログイン
	//
	//  2.パラメータ説明
	//      <I> $Email		: Email
	//      <I> $Password	: パスワード
	//
	//  3.戻り値
	//       true : 成功
	//      false : 失敗
	//
	//  4.機能説明
	//      なし
	//
	/*----------------------------------------------------------------------------*/
	public function login_check(	$Email	,
									$Password	)
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
		$SqlStr .=     "m_user " ;
		$SqlStr .= "WHERE " ;
		$SqlStr .=     "Email = '" . $Email  ."' AND " ;		//	Email
		$SqlStr .=     "Password = '". $Password ."' " ;		//	パスワード
		//	SQL発行
		$Res = mysql_query( $SqlStr ) ;
		if ( !$Res ) {
			$DataBase->close() ;
			return ( false ) ;
		}

		if ( mysql_num_rows( $Res ) == 0 ) {
			$DataBase->close() ;
			return ( false ) ;
		}
		$Row = mysql_fetch_assoc( $Res ) ;

		//---------------------------------
		//	自分自身に値をセット
		//---------------------------------
		$this->set_data( $Row ) ;

		//if( !$this->State ){
		//	$DataBase->close() ;
		//	return ( false ) ;
		//}


		$DataBase->close() ;

		return ( true ) ;
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
		$SqlStr  = "UPDATE m_user SET " ;


		$SetStr = "" ;
		make_update_set_sql( $SetStr , "UserName"			, $this->UserName			) ;	//	ユーザー名
		make_update_set_sql( $SetStr , "Email"				, $this->Email				) ;	//	Email
		make_update_set_sql( $SetStr , "Password"			, $this->Password			) ;	//	パスワード
		make_update_set_sql( $SetStr , "Permission"			, $this->Permission			) ;	//	権限
		make_update_set_sql( $SetStr , "PagePermit"			, $this->PagePermit			) ;	//	ページ権限
		make_update_set_sql( $SetStr , "DelFlag"			, $this->DelFlag			) ;	//	削除フラグ
		make_update_set_sql( $SetStr , "EditTime"			, $this->EditTime			) ;	//	最終更新日時
		make_update_set_sql( $SetStr , "EditUser"			, $this->EditUser			) ;	//	最終更新者
		$SqlStr .= $SetStr ;

		$SqlStr .= "WHERE " ;
		$SqlStr .=     "UserId = ". $this->UserId ;


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

		make_insert_value_sql( $FeildStr , $ValueStr , "UserName"			, $this->UserName			) ;	//	ユーザー名
		make_insert_value_sql( $FeildStr , $ValueStr , "Email"				, $this->Email				) ;	//	Email
		make_insert_value_sql( $FeildStr , $ValueStr , "Password"			, $this->Password			) ;	//	パスワード
		make_insert_value_sql( $FeildStr , $ValueStr , "Permission"			, $this->Permission			) ;	//	権限
		make_insert_value_sql( $FeildStr , $ValueStr , "PagePermit"			, $this->PagePermit			) ;	//	ページ権限
		make_insert_value_sql( $FeildStr , $ValueStr , "EditTime"			, $this->EditTime			) ;	//	最終更新日時
		make_insert_value_sql( $FeildStr , $ValueStr , "EditUser"			, $this->EditUser			) ;	//	最終更新者

		$SqlStr = "INSERT INTO m_user ( ". $FeildStr .") VALUES ( ". $ValueStr .")" ;


		//---------------------------------
		//	SQL発行
		//---------------------------------
		if ( !mysql_query( $SqlStr ) ) {
			return ( 0 ) ;
		}
		//	AutoInclimentの値を取得
		$this->UserId = mysql_insert_id() ;

		return ( $this->UserId ) ;
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

function get_by_user_same_email( $Email, $UserId ) {

	//---------------------------------
	//	取得
	//---------------------------------
	$SqlStr  = "SELECT * ";
	$SqlStr .= "FROM ";
	$SqlStr .= "m_user ";
	$SqlStr .= "WHERE ";
	$SqlStr .= "Email = '".$Email."' AND ";
	$SqlStr .= "( DelFlag = 0 AND UserId != '".$UserId."')";

	//SQL発行
	$Res = mysql_query( $SqlStr );
	if( !isset( $Res ) ) return( null );

	$UserArray = array();
	while( $Row = mysql_fetch_array( $Res ) ) {
		$EntUser = new Ent_M_user();
		$EntUser->set_data( $Row );
		array_push( $UserArray, $EntUser );
	}
	return( $UserArray );
}




function get_all_user( $DataBase ) {
	$SqlStr  = "SELECT * ";
	$SqlStr .= "FROM ";
	$SqlStr .=       "m_user ";
	$SqlStr .= "WHERE ";
	$SqlStr .=       "DelFlag = 0 ORDER BY UserId ASC";

	$Res = mysql_query( $SqlStr );
	if ( !isset( $Res ) ) return ( null ) ;

	$UserArray = array();
	while( $Row = mysql_fetch_array( $Res ) ) {
		$EntUser = new Ent_M_User();
		$EntUser->set_data( $Row );
		array_push( $UserArray, $EntUser );
	}
	return( $UserArray );

}




function permission_full_only( $top ) {
	$EntUser = new Ent_M_User();
	$EntUser->get_by_primary( $_SESSION['UserId'] );
	if( $EntUser->Permission != 3 ) {
		header( "Location:{$top}" );
		exit();
	}
}




function permission_full_or_edit( $top ) {
	$EntUser = new Ent_M_User();
	$EntUser->get_by_primary( $_SESSION['UserId'] );
	if( $EntUser->Permission == 1) {
		header( "Location:{$top}" );
		exit();
	}
}





?>
