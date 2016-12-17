<?php

require_once( $PATH_SITE_SRC_ROOT . "/common.php" ) ;
require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_staff.php" ) ;
require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_department.php" ) ;
require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_t_machine.php" ) ;
require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_category.php" ) ;




/*----------------------------------------------------------------------------*/
//
//  概要   社員と部署の内部結合
//
/*----------------------------------------------------------------------------*/

class Ent_JOIN_Staff_Department {

    /*--------------------------------------------------------*/
    //  メンバー変数定義
    /*--------------------------------------------------------*/
    public $EntStaff = null ;	//	Ent_M_Staff
    public $EntDep   = null ;	//	Ent_M_Department



    /*----------------------------------------------------------------------------*/
    //
    //  1.イベント名
    //      コンストラクタ
    //
    //  2.機能説明
    //      なし
    //
    /*----------------------------------------------------------------------------*/
    public function __construct()
    {
        $this->EntStaff = new Ent_M_Staff() ;
        $this->EntDep   = new Ent_M_Department() ;
    }



}
// end of class




/*----------------------------------------------------------------------------*/
//
//  1.概要
//
//
//  2.パラメータ説明
//      <I>
//
//  3.戻り値
//      -1 : 失敗
//
//  4.機能説明
//      なし
//
/*----------------------------------------------------------------------------*/
function get_staff_and_department( $DataBase )
{
    $SqlStr  = "SELECT * ";
    $SqlStr .= "FROM ";
    $SqlStr .=     "m_staff INNER JOIN m_department ON m_staff.DepartmentId = m_department.DepartmentId ";
    $SqlStr .= "WHERE ";
    $SqlStr .=     "m_staff.DelFlag = 0 ORDER BY StaffNo ASC";

    $Res = mysql_query( $SqlStr );
	if( !isset( $Res ) ) return( null );

    $StaffDepArray = array();
	while( $Row = mysql_fetch_array( $Res ) ) {
        $EntStaffDep = new Ent_JOIN_Staff_Department() ;
        $EntStaffDep->EntStaff->set_data( $Row );
        $EntStaffDep->EntDep->set_data( $Row );

		array_push( $StaffDepArray, $EntStaffDep );
	}
	return ( $StaffDepArray ) ;
}



function get_same_no_staff( $DataBase, $StaffNo ) {
    $SqlStr  = "SELECT m_staff.StaffId, m_staff.StaffNo, m_staff.StaffName, m_department.DepartmentName ";
    $SqlStr .= "FROM ";
    $SqlStr .=     "m_staff LEFT JOIN m_department ON m_staff.DepartmentId = m_department.DepartmentId ";
    $SqlStr .= "WHERE ";
    $SqlStr .=     "m_staff.DelFlag = 0 AND m_staff.StaffNo = '". $StaffNo ."'";

    $Res = mysql_query( $SqlStr );
    if( !isset( $Res ) ) return( null );

    $StaffDepAry = mysql_fetch_assoc( $Res );
    return( $StaffDepAry );
}



function get_machine_and_ctgry( $DataBase, $Serial ) {
    $SqlStr  = "SELECT * ";
    $SqlStr .= "FROM ";
    $SqlStr .=     "t_machine INNER JOIN m_category ON t_machine.CategoryId = m_category.CtgryId ";
    $SqlStr .= "WHERE ";
    $SqlStr .=     "t_machine.DelFlag = 0 AND t_machine.Serial = '". $Serial ."' ";

    $Res = mysql_query( $SqlStr );
	if( !isset( $Res ) ) return( null );

	$MachineAry = mysql_fetch_assoc( $Res );
	return( $MachineAry );
}




function get_all_status( $DataBase ) {
    $SqlStr  = "SELECT * ";
    $SqlStr .= "FROM ";
    $SqlStr .=      "m_category RIGHT JOIN t_machine ON m_category.CtgryId = t_machine.CategoryId ";
    //$SqlStr .=                 "LEFT JOIN t_lend ON t_machine.MachineId = t_lend.MachineId ";
    //$SqlStr .=                 "INNER JOIN m_staff ON t_lend.StaffId = m_staff.StaffId ";
    //$SqlStr .=                 "INNER JOIN m_department ON m_staff.DepartmentId = m_department.DepartmentId ";
    $SqlStr .= "WHERE ";
    $SqlStr .=      "t_machine.DelFlag = 0";

    $Res = mysql_query( $SqlStr );
    if ( !isset( $Res ) ) return( null );

    $MachineAry = array();
    while( $Row = mysql_fetch_assoc( $Res ) ) {
        array_push( $MachineAry, $Row );
    }
    return( $MachineAry );
}




function get_same_Id_staff( $DataBase, $StaffId ) {
    $SqlStr  = "SELECT m_staff.StaffId, m_staff.StaffNo, m_staff.StaffName, m_department.DepartmentName ";
    $SqlStr .= "FROM ";
    $SqlStr .=     "m_staff LEFT JOIN m_department ON m_staff.DepartmentId = m_department.DepartmentId ";
    $SqlStr .= "WHERE ";
    $SqlStr .=     "m_staff.DelFlag = 0 AND m_staff.StaffId = '". $StaffId ."'";

    $Res = mysql_query( $SqlStr );
    if( !isset( $Res ) ) return( null );

    $StaffDepAry = array();
    $StaffDepAry = mysql_fetch_assoc( $Res );
    return( $StaffDepAry );
}



function get_same_Id_lend( $DataBase, $MachineId ) {
    $SqlStr  = "SELECT * ";
    $SqlStr .= "FROM ";
    $SqlStr .=     "t_machine LEFT JOIN t_lend ON t_machine.MachineId = t_lend.MachineId ";
    $SqlStr .= "WHERE ";
    $SqlStr .=     "t_machine.DelFlag = 0 AND t_lend.MachineId = '". $MachineId ."' ";
    $SqlStr .= " ORDER BY t_lend.EditTime DESC";

    $Res = mysql_query( $SqlStr );
    if( !isset( $Res ) ) return( null );

    $LendAry = array();
    $LendAry = mysql_fetch_assoc( $Res );
    return( $LendAry );
}

?>
