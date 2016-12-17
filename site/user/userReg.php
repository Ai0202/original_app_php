<?php

	//---------------------------------
	//	設定ファイルの読み込み
	//---------------------------------
	require_once( '../config.php' ) ;	// 設定ファイル
	require_once( $PATH_SITE_SRC_ROOT . "/entity/ent_m_user.php" ) ;


	//---------------------------------
	//	権限がない場合にリダイレクト
	//---------------------------------
	$login->loginCheck();		               //ログインしてない場合にリダイレクト
	//permission_full_only( $URL_PAGE_TOP );　　 //フルコントール権限がない場合にリダイレクト


	$Msg      = "";
	$UserId   = "";
	$UserName = "";
	$Email    = "";
	$Row      = "";
	$EditFlg  = 0;

	//---------------------------------
	//	DB接続
	//---------------------------------
	$DataBase = new Data_Base_Class() ;
	$DataBase->connect() ;



	//---------------------------------
	//	mUserの編集ボタンが押された場合
	//---------------------------------
	if(isset($_GET['EditFlg'])) {
		$EditFlg = intval( $_GET['EditFlg'] );
		$EntUser = new Ent_M_User();
		$EntUser->get_by_primary( $_GET['UserId'] );

		$UserId   = $EntUser->UserId;
		$UserName = $EntUser->UserName;
		$Email    = $EntUser->Email;
	}


	//---------------------------------
	// 登録ボタンが押された場合
	//---------------------------------
	if( isset($_POST["edit"] ) ) {

		//パスワードの長さが8文字以上か確認
		if( strlen( $_POST['Password'] ) < 8 ) {
			$Msg .="パスワードが短すぎます。8文字以上で入力してください <br>";
		}

		//パスワードが半角英数のみで指定されているか確認
		if( !preg_match( "/^[a-zA-Z0-9]+$/", $_POST['Password'] ) ) {
			$Msg .="パスワードは半角英数字のみ有効です。<br>";
		}

		//パスワードとパスワード確認の欄が等しいか確認
		if( !$Msg ) {
			if( $_POST['Password'] = $_POST['passwordConfirm'] ) {
				$Msg .= "パスワードとパスワード確認欄の値が一致しません。<br>";
			}
		}

		//---------------------------------
		//	POSTの値を取得
		//---------------------------------
		if( !$Msg ) {
			$EntUser             = new Ent_M_User();
			$EntUser->UserId     = $_POST['UserId'];
			$EntUser->UserName   = $_POST['UserName'];
			$EntUser->Email      = $_POST['Email'];
			$EntUser->Password   = $_POST['Password'];
			$EntUser->Permission = intval($_POST['Permission']);

			//削除ボタンが押された場合に削除フラグを1にする。
			if( $_POST['edit'] =="delete" ) {
				$EntUser->DelFlag =1;
			}

			//---------------------------------
			//	Emailに重複がないかチェック
			//---------------------------------
			$UserAry = get_by_user_same_email( $EntUser->Email, $EntUser->UserId );
			if( isset( $UserAry ) ) {
				foreach( $UserAry as $EntU ) {
					//部署名チェック
					if( $EntU->Email == $EntUser->Email ) {
						$Msg .="入力されたメールアドレスは既に登録されています。";
					}
					$DataBase->close();
				}
				//エラーでも入力されたユーザー名・Emailが登録画面から消えないようにセット
				$UserName = $EntUser->UserName;
				$Email    = $EntUser->Email;
			}
		}

		//---------------------------------
		//	DBに値を追加
		//---------------------------------
		if ( !$Msg ) {
			$EntUser->update_or_insert( $DataBase );

			//insert（新規登録)が正常に完了した場合の処理
			if( $EditFlg == 0) {
				$Msg      = "正常に登録されました";
				$UserName = "";
				$Email    = "";
			}

			//update(更新・削除)が正常に完了した場合の処理
			if( $EditFlg == 1) {
				header("location:{$URL_SITE_SRC_ROOT}"."/user/userReg.php");
				exit();
			}
		}
	}




//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
//　HTML
//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
// ヘッダー
include($PATH_PAGE_HEADER);
include($PATH_PAGE_MENU);


?>

	<div class="container">
		<h3>ユーザーマスタ　登録・編集</h3>
			<div class="msg"><?php echo $Msg ?></div>
			<form class="form-signin" action="userReg.php" method="post">

				<input type="hidden" id="UserId" name="UserId" value="<?php echo $UserId; ?>">

				<label for="UserName" class="sr-only">ユーザー名<span class="required">必須</span></label>
				<input type="text" id="UserName" class="form-control" name="UserName" value="<?php echo $UserName; ?>" required maxlength="10" >

				<label for="Email" class="sr-only">メールアドレス<span class="required">必須</span></label>
				<input type="email" id="Email" class="form-control" name="Email" value="<?php echo $Email; ?>" required autofocus></br>

				<label for="Password" class="sr-only">パスワード<span class="required">必須</span><span> ※8文字以上の半角英数</span></label>
				<input type="password" id="Password" class="form-control" name="Password" required>

				<label for="passwordConfirm" class="sr-only">パスワード確認<span class="required">必須</span></label>
				<input type="password" id="passwordConfirm" class="form-control" name="passwordConfirm" required>

				<label for="Permission" id="Permission" class="form-control">Access Level<span class="required">必須</span></label>
				<select  name="Permission" required>
					<option value=1>参照</option>
					<option value=2>編集</option>
					<option value=3>フルコントロール</option>
				</select></br>

				<!-- 新規登録のときのボタン -->
				<?php if( $EditFlg ==0 ): ?>
					<button class="btn btn-lg btn-primary" type="submit" name="edit">登録</button>
				<?php endif; ?>

				<!-- 編集のときのボタン -->
				<?php if( $EditFlg == 1 ): ?>
					<input type='hidden' name='EditFlg' value='1' >
					<button class="btn btn-lg btn-primary" type="submit" name="edit">更新</button>
					<button class="btn btn-lg btn-primary" type="submit" name="edit" value="delete">削除</button>
				<?php endif; ?>
			</form>
	</div>
	<script>
		var form = document.forms[0];
		form.onsubmit = function() {
			//エラーメッセージをクリアする。
			form.password.setCustomValidity("");
			//パスワードの一致確認
			if( form.password.value != form.passwordConfirm.value ) {
				form.password.setCustomValidity("パスワードが一致しません");
			}
		};
	</script>

<?php include($PATH_PAGE_FOOTER); ?>
