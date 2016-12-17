<?php

//---------------------------------
//	設定ファイルの読み込み
//---------------------------------
require_once( '../config.php' ) ;// 設定ファイル
require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_join.php" ) ;
require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_department.php" ) ;



//---------------------------------
//	権限がない場合にリダイレクト
//---------------------------------
$login->loginCheck();		               //ログインしてない場合にリダイレクト
permission_full_or_edit( $URL_PAGE_TOP );  //フルコントールか編集権限がない場合にリダイレクト



$Selected = "";

//---------------------------------
//	DB接続
//---------------------------------
$DataBase = new Data_Base_Class();
$DataBase->connect();

//DBから値を抽出
$EntStaffDepAry = get_staff_and_department( $DataBase );
$EntDepAry      = get_all_department( $DataBase );


//---------------------------------
//	部署名でフィルタをかけた場合の処理
//---------------------------------
if( isset( $_POST['Search'] ) ) {
    $DepId = $_POST['Department'];      //選択された部署IDを取得
}



//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
//　HTML
//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
include($PATH_PAGE_HEADER);		// ヘッダー
include($PATH_PAGE_MENU);		//サイドメニュー

?>

<div class="container">
    <h1>社員マスタ</h1>

    <!-- 検索ボックス -->
    <form class="form-signin" action="mStaff.php" method="post">
		<select name="Department">
			<option value=0> </option>
			<?php foreach( $EntDepAry as $DepAry ): ?>
				<?php if( $DepId == $DepAry->DepartmentId ): ?>
					<?php $Selected = "selected"; ?>
				<?php endif; ?>
				<option value="<?php echo $DepAry->DepartmentId; ?>" <?php echo $Selected; ?> ><?php echo $DepAry->DepartmentName; ?></option>
				<?php $Selected = ""; ?>
			<?php endforeach; ?>
		</select>
		<button class="btn btn-lg btn-primary" type="submit" name="Search">検索</button>
	</form>

    <!-- 社員一覧 -->
    <table width = "400" border ="1">
        <tr>
            <th>　</th>
            <th>社員コード</th>
            <th>氏名</th>
            <th>部署名</th>
        </tr>
        <?php foreach( $EntStaffDepAry as $EntStaffDep ) : ?>
            <!-- 部署名でフィルタをかけた場合に選択した部署以外はとばす-->
            <?php if( isset( $DepId ) && $DepId != 0 ): ?>
                <?php if( $DepId != $EntStaffDep->EntDep->DepartmentId ): ?>
                    <?php continue; ?>
                <?php endif; ?>
            <?php endif; ?>
            <tr>
                <td>
                    <a href="staffReg.php?StaffId=<?php echo $EntStaffDep->EntStaff->StaffId; ?>&EditFlg=1">編集</a>
                </td>
                <td>
                    <?php echo $EntStaffDep->EntStaff->StaffNo; ?>
                </td>
                <td>
                    <?php echo $EntStaffDep->EntStaff->StaffName; ?>
                </td>
                <td>
                    <?php echo $EntStaffDep->EntDep->DepartmentName; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>


<?php include($PATH_PAGE_FOOTER); ?>
