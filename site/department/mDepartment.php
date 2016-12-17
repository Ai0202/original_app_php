<?php

//---------------------------------
//	設定ファイルの読み込み
//---------------------------------
require_once( '../config.php' ) ;	// 設定ファイル
require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_department.php" ) ;



//---------------------------------
//	権限がない場合にリダイレクト
//---------------------------------
$login->loginCheck();		               //ログインしてない場合にリダイレクト



$Selected = "";


//---------------------------------
//	DB接続
//---------------------------------
$DataBase = new Data_Base_Class();
$DataBase->connect();

//DBから値を抽出
$EntDepAry = get_all_department( $DataBase );



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
    <h1>部署マスタ</h1>

    <!-- 検索ボックス -->
    <form class="form-signin" action="mDepartment.php" method="post">
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


    <a href="departmentReg.php">新規登録</a></br>

    <!-- 部署一覧 -->
	<table width = "300" border ="1">
	    <tr>
	    	<th>　</th>
	    	<th>部署名</th>
	    	<th>部署コード</th>
	    </tr>

	       <?php foreach( $EntDepAry as $EntDep ): ?>
               <!-- 部署名でフィルタをかけた場合に選択した部署以外はとばす-->
               <?php if( isset( $DepId ) && $DepId != 0 ): ?>
                   <?php if( $DepId != $EntDep->DepartmentId ): ?>
                       <?php continue; ?>
                   <?php endif; ?>
               <?php endif; ?>
        		<tr>
        			<td>
        				<a href="departmentReg.php?DepartmentId=<?php echo $EntDep->DepartmentId; ?>&EditFlg=1">編集</a>
        			</td>
        			<td class="depName">
        				<?php echo $EntDep->DepartmentName; ?>
        			</td>
        			<td class="depNo">
        				<?php echo $EntDep->DepartmentNo; ?>
        			</td>
        		</tr>
            <?php endforeach; ?>
	</table>
</div>


<?php include($PATH_PAGE_FOOTER); ?>
