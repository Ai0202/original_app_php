<?php
/*----------------------------------------------------------------------------*/
//
//  変数定義
//
//  作成年月日・作成者
//    15/04/01 Mayu Kanechiku
//
/*----------------------------------------------------------------------------*/

	mb_language( "uni" ) ;
	mb_internal_encoding( "utf-8" ) ; //内部文字コードを変更
	mb_http_input( "auto" ) ;
	mb_http_output( "utf-8" ) ;

	// Noticeを非表示
	error_reporting( E_ALL & ~E_NOTICE);

	// mysqliのエラーを出さないようにする
	error_reporting( E_ALL ^ E_DEPRECATED);

	// Warningを非表示
	//error_reporting(0);






	// エラー出力する場合
	ini_set( 'display_errors', 1 );

	// タイムゾーンの設定
	date_default_timezone_set( 'Asia/Tokyo' ) ;

	// キャッシュの有効期限切れを表示させない
	session_cache_limiter( 'no-cache' ) ;	//	残さない
	header( "Last-Modified: ". gmdate("D, d M Y H:i:s"). " GMT" ) ;

	// ブラウザを終了させるとクッキーを消す
	session_set_cookie_params( 0 , '/' ) ;

	// セッションスタート
	session_start() ;


	//---------------------------------
	//	ディレクトリパス
	//---------------------------------
	$URL_SERVER_NAME	= $_SERVER["SERVER_NAME"] ;							//	サーバー名（）
	$PATH_DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] ;						//	ルートパス（）
//	$PATH_REQUEST_URI	= $_SERVER['REQUEST_URI'] ;							//	リクエストURI（）

	//	サイトのルート
	$URL_SITE_ROOT		= "http://". $URL_SERVER_NAME ."/original" ;			//	サイトルートURL
	$PATH_SITE_ROOT     = $PATH_DOCUMENT_ROOT . "/original" ;				//	サイトルートパス

	//	実装したソースのルート
	$URL_SITE_SRC_ROOT	= $URL_SITE_ROOT ."/site" ;							//	ソースルートURL
	$PATH_SITE_SRC_ROOT = $PATH_SITE_ROOT . "/site" ;						//	ソースルートパス


	//---------------------------------
	//	ページのURL
	//---------------------------------
	$URL_PAGE_TOP		= $URL_SITE_SRC_ROOT ."/index.php" ;				//	トップページ  URL
	$URL_PAGE_LOGIN		= $URL_SITE_SRC_ROOT ."/login.php" ;				//	ログインページURL


	//---------------------------------
	//	ページのパス
	//---------------------------------
	$PATH_PAGE_HEADER	= $PATH_SITE_SRC_ROOT.'/header.php';		//共通ヘッダー
	$PATH_PAGE_FOOTER	= $PATH_SITE_SRC_ROOT.'/footer.php';		//共通フッター
	$PATH_PAGE_MENU		= $PATH_SITE_SRC_ROOT.'/menu_left.php';			//共通メニュー


	//---------------------------------
	//	共通グローバル定義
	//---------------------------------
	$HTML_CRLF			= "&#13;&#10;" ;							//	HTMLでの改行



	//---------------------------------
	//	classの読み込みと呼び出し
	//---------------------------------
	// class読み込み
	require_once( $PATH_SITE_SRC_ROOT."/class/database_class.php" ) ;
	require_once( $PATH_SITE_SRC_ROOT."/class/login_class.php" ) ;		
	require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_user.php" ) ;

	// class呼び出し
	$login		= new Login();		// ログイン

	// 共通関数ファイル
	require_once( $PATH_SITE_SRC_ROOT."/common.php" ) ;

	//



?>
