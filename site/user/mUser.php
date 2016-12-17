<?php

	//---------------------------------
	//	設定ファイルの読み込み
	//---------------------------------
	require_once('../config.php' ) ;	// 設定ファイル
	require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_user.php" ) ;

	$Selected = "";


	//---------------------------------
	//	権限がない場合にリダイレクト
	//---------------------------------
	$login->loginCheck();		               //ログインしてない場合にリダイレクト
	//permission_full_only( $URL_PAGE_TOP );　　 //フルコントール権限がない場合にリダイレクト



	//---------------------------------
	//	DB接続
	//---------------------------------
	$DataBase = new Data_Base_Class();
	$DataBase->connect();


	//DBから値を抽出
	$EntUserAry = get_all_user( $DataBase );


	//---------------------------------
	//	アクセス権でフィルタをかけた場合の処理
	//---------------------------------
	if( isset( $_POST['Search'] ) ) {
	    $Permission = $_POST['Permission'];      //選択された部署IDを取得
	}



	/*
	$Msg = "";
	if($Rows) {
		$Msg = $Rows."件のデータがあります";
	}else {
		$Msg = "データはありません";
	}
	*/

//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
//　HTML
//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
include($PATH_PAGE_HEADER);
include($PATH_PAGE_MENU);


?>
<div class="container">
	<h1>ユーザーマスタ</h1>

	<!-- 検索ボックス -->
	<form class="form-signin" action="mUser.php" method="post">
		<select name="Permission">
			<option value=0> </option>
			<option value=1>参照</option>
			<option value=2>編集</option>
			<option value=3>フルコントロール</option>
		</select>
		<button class="btn btn-lg btn-primary" type="submit" name="Search">検索</button>
	</form>


	<!-- ユーザー一覧 -->
	<table width= "400" border= "1">
		<tr>
			<th>　</th>
			<th>ユーザー名</th>
			<th>メールアドレス</th>
			<th>アクセス権</th>
		</tr>

		<?php foreach( $EntUserAry as $EntUser ): ?>
		<!-- アクセス権でフィルタをかけた場合に選択した権限以外はとばす-->
			<?php if( isset( $Permission ) && $Permission != 0 ): ?>
				<?php if( $Permission != $EntUser->Permission ): ?>
					<?php continue; ?>
				<?php endif; ?>
			<?php endif; ?>
			<tr>
				<td><a href="userReg.php?UserId=<?php echo $EntUser->UserId; ?>&EditFlg=1">編集</a></td>
				<td><?php echo $EntUser->UserName; ?></td>
				<td><?php echo $EntUser->Email; ?></td>
				<td>
					<?php switch( $EntUser->Permission ) {
						case 1:
							echo "参照";
							break;
						case 2:
							echo "編集";
							break;
						case 3:
							echo "フルコントロール";
							break;
						default:
							echo "エラー";
							break;
					} ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>

</div>




<?php include($PATH_PAGE_FOOTER); ?>
