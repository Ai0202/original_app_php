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



//---------------------------------
//	権限がない場合にリダイレクト
//---------------------------------
$login->loginCheck();		               //ログインしてない場合にリダイレクト
permission_full_or_edit( $URL_PAGE_TOP );  //フルコントールか編集権限がない場合にリダイレクト


//---------------------------------
//	DB接続
//---------------------------------
$DataBase = new Data_Base_Class();
$DataBase->connect();

$Msg            = "";
$EditFlg        = 0;
$LendId         = "";
$Ctgry          = "";
$Serial         = "";
$Host           = "";
$StaffNo        = "";
$StaffName      = "";
$DepartmentName = "";
$LendDay        = "";
$RtnDay         = "";
$RtnPlan        = "";
$Status         = "";
$Note           = "";
$MachineId      = "";
$StaffId        = "";
$Selected       = "";


//---------------------------------
//	一覧を取得
//---------------------------------
//DBから値を抽出
$ChildCtgryAry  = get_child( $DataBase );
$AllMachineAry  = get_all_machine( $DataBase );



//---------------------------------
//	indexの編集ボタンが押された場合
//---------------------------------
if( isset( $_GET['EditFlg'] ) ) {
    $EditFlg        = intval( $_GET['EditFlg'] );

    //machineテーブルの情報取得
    $EntMachine     = new Ent_T_Machine();
    $EntMachine->get_by_primary( $_GET['MachineId'] );
    $MachineId      = $EntMachine->MachineId;
    $Ctgry          = $EntMachine->CategoryId ;
    $Serial         = $EntMachine->Serial;
    $Host           = $EntMachine->Host;
    $Status         = $EntMachine->Status;
    $Note           = $EntMachine->Note;


    //indexの一覧に貸出情報が入っている場合の処理
    if( !$_GET['LendId'] == "" ) {

        //lendテーブルの情報取得
        $EntLend        = new Ent_T_Lend();
        $EntLend->get_by_primary( $_GET['LendId'] );
        $LendId         = $EntLend->LendId;
        $LendDay        = $EntLend->LendDay;
        $RtnDay         = $EntLend->RtnDay;
        $RtnPlan        = $EntLend->RtnPlan;
        $StaffId        = $EntLend->StaffId;


        //indexの一覧に社員情報が入っている場合の処理
        if( isset( $EntLend->StaffId ) ) {

            //staffマスタの情報取得
            $EntStaff       = new Ent_M_Staff();
            $EntStaff->get_by_primary( $EntLend->StaffId );
            $StaffNo        = $EntStaff->StaffNo;
            $StaffName      = $EntStaff->StaffName;

            //departmentマスタの情報取得
            if( isset( $EntStaff->Departmentid ) ) {
                $EntDep         = new Ent_M_Department();
                $EntDep->get_by_primary( $EntStaff->DepartmentId );
                $DepartmentName = $EntDep->DepartmentName;
            }
        }
    }
}


//---------------------------------
// 登録ボタン・編集ボタンが押された場合
//---------------------------------
if( isset( $_POST['edit'] ) ) {

    //---------------------------------
    //	POSTの値を取得
    //---------------------------------
    $EditFlg = $_POST['EditFlg'];
    $EntLend = new Ent_T_Lend();
    $EntLend->MachineId = intval( $_POST['MachineId'] );
    $EntLend->StaffId   = intval( $_POST['StaffId'] );
    $EntLend->LendDay   = $_POST['LendDay'];
    $EntLend->RtnDay    = $_POST['RtnDay'];
    $EntLend->RtnPlan   = $_POST['RtnPlnDay'];

    //LendIdはとらず毎回insertされるようにする。
    //$EntLendId->LendId  = $_POST['LendId'];

    $EntMachine = new Ent_T_Machine();
    $EntMachine->get_by_primary( $EntLend->MachineId );
    $EntMachine->Status = $_POST['Status'];
    $EntMachine->Note   = $_POST['Note'];


    //削除ボタンが押された場合に削除フラグを1にする。
    if( $_POST['edit'] == "delete") {
        $EntMachine->DelFlag = 1;
    }


    //---------------------------------
    //	DBに値を追加
    //---------------------------------
    if( !$Msg ) {

        $EntLend->update_or_insert( $DataBase );

        $EntMachine->update( $DataBase );

        //insert（新規登録)が正常に完了した場合の処理
        if( $EditFlg == 0 ) {
            $Msg = "正常に登録されました";
        }

        //update(更新・削除)が正常に完了した場合の処理
        if( $EditFlg == 1 ) {
            header("location:{$URL_SITE_SRC_ROOT}" );
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
    <h3>貸出状況入力</h3>
    <div class="msg"><?php echo $Msg ?></div>

    <form class="form-signin" action="lendReg.php" method="post">

        <input type="hidden" name="LendId" class="form-control" value="<?php echo $LendId; ?>">

        <label for="CategoryId" class="sr-only">カテゴリ</label>
        <select name="CategoryId" class="form-control">
    		<?php foreach( $ChildCtgryAry as $ChildCtgry ): ?>
                <?php if( $Ctgry == $ChildCtgry->CtgryId ): ?>
                    <?php $Selected = "Selected"; ?>
                <?php endif; ?>
    			<option value="<?php echo $ChildCtgry->CtgryId ?>" <?php echo $Selected; ?> ><?php echo $ChildCtgry->CtgryName; ?></option>
                <?php $Selected = "";?>
    		<?php endforeach; ?>
    	</select>

        <!-- machineId -->
        <input type="hidden" name="MachineId" id="MachineId" value="<?php echo $MachineId; ?>">

        <label for="Serial" class="sr-only">シリアル<span class="required">必須</span></label>
        <input type="text" name="Serial" id="Serial" class="form-control" value="<?php echo $Serial; ?>"><span><input type="button" id="Search" value="検索"></span>

        <label for="Host" class="sr-only">ホスト名</label>
        <input type="text" name="Host" id="Host" class="form-control" value="<?php echo $Host; ?>">

        <!-- StaffId -->
        <input type="hidden" name="StaffId" id="StaffId" class="form-control" value="<?php echo $StaffId; ?>">

        <label for="StaffNo" class="sr-only">社員コード</label>
        <input type="text" name="StaffNo" id="StaffNo" class="form-control" value="<?php echo $StaffNo; ?>">　<span><input type="button" id="StaffSearch" value="検索"></span></span>

        <label for="StaffName" class="sr-only">氏名</label>
        <input type="text" name="StaffName" id="StaffName" class="form-control" value="<?php echo $StaffName; ?>">

        <label for="DepartmentName" class="sr-only">部署名</label>
        <input name="DepartmentName" id="DepartmentName" class="form-control" value="<?php echo $DepartmentName; ?>">

        <label for="LendDay" class="sr-only">貸与日</label>
        <input type="date" name="LendDay" class="form-control" value="<?php echo $LendDay; ?>">

        <label for="RtnDay" class="sr-only">返却日</label>
        <input type="date" name="RtnDay" class="form-control" value="<?php echo $RtnDay; ?>">

        <label for="RtnPlnDay" class="sr-only">返却予定日</label>
        <input type="date" name="RtnPlnDay" class="form-control" value="<?php echo $RtnPlan; ?>">

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
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script>
    $(function () {

        //社員
        $("#StaffSearch").click(function() {
            var wStaffNo = $("#StaffNo").val();
				var FormObj	;

			FormObj = new FormData() ;
			FormObj.append( "StaffNo" , wStaffNo ) ;

            $.ajax({
                url 		: "ajax.php",
                type		: "POST",
                datatype	: "json",
                processData	: false ,
                contentType	: false ,
                data		: FormObj ,
                success		: Staff,

                error		:
                	function() {
       		        	alert("errorです。")
                	}

            });
        });


        //関数
        //社員情報取得
        function Staff( response )　{

            $('#StaffId').val( response['StaffId'] );
            $('#StaffName').val( response['StaffName'] );
            $('#DepartmentName').val( response['DepartmentName']);

        }

    });

</script>

<?php include($PATH_PAGE_FOOTER); ?>
