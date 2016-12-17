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
require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_join.php" ) ;



//---------------------------------
//	DB接続
//---------------------------------
$DataBase = new Data_Base_Class();
$DataBase->connect();

//シリアルからホスト名を取得
if( isset( $_POST['Serial'] ) ) {
    $MachineAry = get_machine_and_ctgry( $DataBase, $_POST['Serial'] );

    header('Content-Type: application/json; charset=utf-8' );
    echo( json_encode( $MachineAry ) );
}

//社員コードから社員名と部署を取得
if( isset( $_POST['StaffNo'] ) ) {
    $StaffDepAry = get_same_no_staff( $DataBase, intval( $_POST['StaffNo'] ) );

    header('Content-Type: application/json; charset=utf-8' );
    echo( json_encode( $StaffDepAry ) );
}

?>
