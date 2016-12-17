<?php
/*----------------------------------------------------------------------------*/
//
//  1.概要
//      SQLのINSERTの文字列を作成する
//
//  2.パラメータ説明
//      <I/O> FeildStr	: フィールド文字列
//      <I/O> ValueStr	: 値文字列
//      <I>   Key		: キー文字列
//      <I>   Val		: 値の変数
//
//  3.戻り値
//      なし
//
//  4.機能説明
//      $Valが文字列    の場合：「$Key='$Val' 」
//      $Valが文字列以外の場合：「$Key=$Val 」
//
/*----------------------------------------------------------------------------*/
function make_insert_value_sql(	&$FeildStr	,
								&$ValueStr	,
								$Key		,
								$Val		)
{
	if ( is_null( $Val ) ) return ;

	if ( strlen( $FeildStr ) > 0 ) { $FeildStr .= ", " ; }
	if ( strlen( $ValueStr ) > 0 ) { $ValueStr .= ", " ; }

	$FeildStr .= $Key ." " ;
	if ( is_string( $Val ) ) { $ValueStr .= "'". $Val ."' " ; }
	else                     { $ValueStr .=      $Val . " " ; }
}




/*----------------------------------------------------------------------------*/
//
//  1.概要
//      SQLのUPDATEのSET句の文字列を作成する
//
//  2.パラメータ説明
//      <I/O> Str	: SET句文字列
//      <I>   Key	: キー文字列
//      <I>   Val	: 値の変数
//
//  3.戻り値
//      なし
//
//  4.機能説明
//      $Valが文字列    の場合：「$Key='$Val' 」
//      $Valが文字列以外の場合：「$Key=$Val 」
//
/*----------------------------------------------------------------------------*/
function make_update_set_sql(	&$Str	,
								$Key	,
								$Val	)
{
	if ( is_null( $Val ) ) return ;

	if ( strlen( $Str ) > 0 ) { $Str .= ", " ; }

	if ( is_string( $Val ) ) { $Str .= $Key ."='" . $Val . "' " ; }
	else                     { $Str .= $Key ."="  . $Val .  " " ; }
}




/*----------------------------------------------------------------------------*/
//
//  1.概要
//      ページの閲覧権限がない場合にtopページにリダイレクトする。(参照)
//
/*----------------------------------------------------------------------------*/
function access_deny_read( $Permission ) {
	if( $Permission != 2 || $Permission != 3 ) {
		header("../location:index.php");
		exit();
	}
}




/*----------------------------------------------------------------------------*/
//
//  1.概要
//      ページの閲覧権限がない場合にtopページにリダイレクトする。（編集）
//
/*----------------------------------------------------------------------------*/
function access_deny_read_write( $Permission ) {
	if( $Permission != 3 ) {
		header("../location:index.php");
		exit();
	}
}




?>
