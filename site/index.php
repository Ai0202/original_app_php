<?php

//---------------------------------
//	設定ファイルの読み込み
//---------------------------------
	require_once('./config.php' ) ;// 設定ファイル
	require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_join.php" ) ;

$StaffDep       = "";
$StaffNo        = "";
$StaffName      = "";
$DepartmentName = "";
$LendDay        = "";
$RtnDay         = "";
$RtnPlan        = "";


//---------------------------------
//	権限がない場合にリダイレクト
//---------------------------------
$login->loginCheck();		               //ログインしてない場合にリダイレクト



//---------------------------------
//	DB接続
//---------------------------------
$DataBase = new Data_Base_Class();
$DataBase->connect();

//DBから値を抽出
$MachineAry = get_all_status( $DataBase );



//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
//　HTML
//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
include($PATH_PAGE_HEADER);		// ヘッダー
include($PATH_PAGE_MENU);		//サイドメニュー

?>

<div class="container">
	<h1>貸出状況一覧</h1>

	<form class="form-signin" action="index.php" method="post">
		<select name="Status">
			<option value=0> </option>
			<?php ?>

		</select>
		<button class="btn btn-lg btn-primary" type="submit" name="Search">検索</button>
	</form>

	<table width = "800" border = "1">
		<tr>
			<th>　</th>
            <th>カテゴリ</th>
			<th>シリアル</th>
            <th>ホスト名</th>
			<th>社員コード</th>
			<th>氏名</th>
            <th>部署名</th>
			<th>貸与日</th>
			<th>返却日</th>
			<th>返却予定日</th>
			<th>ステータス</th>
			<th>備考</th>
		</tr>
		<?php foreach( $MachineAry as $Machine ): ?>
			<?php
				//貸出日・返却日・返却予定日を取得
				if( get_same_Id_lend( $DataBase, $Machine['MachineId'] ) !== null ) {
					$Lend = get_same_Id_lend( $DataBase, $Machine['MachineId'] );
					$LendDay = $Lend['LendDay'];
					$RtnDay  = $Lend['RtnDay'];
					$RtnPlan = $Lend['RtnPlan'];
				}
				//社員コード・氏名・部署マスタを取得
				if( isset( $Lend['StaffId'] ) ) {
					$StaffDep     = get_same_Id_staff( $DataBase, $Lend['StaffId'] );
					$StaffId        = $StaffDep['StaffId'];
					$StaffNo        = $StaffDep['StaffNo'];
					$StaffName      = $StaffDep['StaffName'];
					$DepartmentName = $StaffDep['DepartmentName'];
				}
			?>
		<tr>
			<td>
				<a href="./lend/lendReg.php?LendId=<?php echo $Lend['LendId']; ?>&MachineId=<?php echo $Machine['MachineId'] ?>&EditFlg=1">編集</a>
			</td>
			<td>
				<?php echo $Machine['CtgryName']; ?>
			</td>
			<td>
				<?php echo $Machine['Serial']; ?>
			</td>
			<td>
				<?php echo $Machine['Host']; ?>
			</td>
			<td>
				<?php echo $StaffNo; ?>
			</td>
			<td>
				<?php echo $StaffName; ?>
			</td>
			<td>
				<?php echo $DepartmentName; ?>
			</td>
			<td>
				<?php echo $LendDay; ?>
			</td>
			<td>
				<?php echo $RtnDay; ?>
			</td>
			<td>
				<?php echo $RtnPlan; ?>
			</td>
			<td>
				<?php echo $Machine['Status']; ?>
			</td>
			<td>
				<?php echo $Machine['Note']; ?>
			</td>
			<?php
				$StaffNo        = "";
				$StaffName      = "";
				$DepartmentName = "";
				$LendDay        = "";
				$RtnDay         = "";
				$RtnPlan        = "";
			?>
		</tr>
		<?php endforeach; ?>
	</table>

</div>

<?php include($PATH_PAGE_FOOTER); ?>
