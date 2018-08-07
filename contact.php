<?PHP
//********************************************************************
//* File name	contact.php
//* @return		なし
//*
//* Code author	K.mineoka<mineoka0222@gmail.com>
//* History		2018/08/02	create
//********************************************************************
	//----------------------------------------------------------------
	// プログラム定数
	//----------------------------------------------------------------
	// 送信者名
	define( 'CONST_EML_SENDNM' , '会社名' );

	// メールタイトル
	define( 'CONST_EML_TITLE' , '【会社名】お問い合わせ受付メール' );

	// メールアドレス(TO/CC/BCC)
	define( 'CONST_EMAIL_TO'   , 'to_xxx@abc.com' );
	define( 'CONST_EMAIL_CC'   , 'cc_xxx@abc.com' );
	define( 'CONST_EMAIL_BCC'  , '' );
	define( 'CONST_EMAIL_FROM' , 'from_xxx@abc.com' );

	//----------------------------------------------------------------
	// プログラム変数
	//----------------------------------------------------------------
	// 初期化(formの各項目のclass名をここに記載する)
	$ary_form_element = array(
		'name' 			=> 'お名前',
		'sex' 			=> '性別',
		'tel' 			=> '電話番号',
		'post' 			=> '役職',
		'address' 	=> '住所'
	);

	//----------------------------------------------------------------
	// メイン処理
	//----------------------------------------------------------------
	// 初期化
	$str_log = "";

	// メールフォーマット読み込み
	$str_mailform = file_get_contents( './mailform/mailformat.txt' );

	// メールフォーマット読み込み判定
	if ( $str_mailform == FALSE ){
		// メールフォーマットなし
		$str_err = "メールフォーマット読み込み失敗";
	} else {
		// 項目編集
		foreach ($ary_form_element as $key => $value) {
			// メールフォーマットセット
			$str_tmp = $_POST[$key];
			$str_tmp = mb_convert_encoding( $str_tmp , "EUC-JP" , "UTF-8" );
			$str_mailform = str_replace( "**" . $key . "**" , $str_tmp , $str_mailform );

			// ログ出力用に退避
			$str_log .= $value . ":" . $_POST[$key] . ",";
		}

		// 編集
		// 送信先
		// メールアドレス
		$str_to = CONST_EMAIL_TO;

		// ｃｃ
		$str_cc = CONST_EMAIL_CC;

		// ｂｃｃ
		$str_bcc = CONST_EMAIL_BCC;

		// 送信者名
		$str_tmp = CONST_EML_SENDNM;
		$str_tmp = mb_convert_encoding( $str_tmp , "EUC-JP" , "UTF-8" );
		$str_name = $str_tmp;

		// 送信元
		$str_from = CONST_EMAIL_FROM;

		// 返信先
		$str_reply = "";

		// タイトル
		$str_tmp = CONST_EML_TITLE;
		$str_tmp = mb_convert_encoding( $str_tmp , "EUC-JP" , "UTF-8" );
		$str_subject = $str_tmp;

		// 内容
		$str_body = $str_mailform;

		// 送信
		if ( cmph_send_mail( $str_to , $str_cc , $str_bcc , $str_name , $str_from , $str_reply , $str_subject , $str_body ) == FALSE ) {
			// 送信失敗
			$str_err = "メール送信に失敗しました。";
		}

		// ログ出力
		fnc_mail_log( $str_err . $str_log );

		// 画面遷移
		if( $str_err == "" ){
			// 完了画面へ遷移
			$str_url  = "Location:./contact_end.php";
			header( $str_url , TRUE );
		} else {
			// エラー画面へ遷移
			$str_url  = "Location:./contact_err.php";
			header( $str_url , TRUE );
		}
	}

	//----------------------------------------------------------------
	// 関数
	//----------------------------------------------------------------
	// メール送信関数
	function cmph_send_mail( $arg_to , $arg_cc , $arg_bcc , $arg_name , $arg_from , $arg_reply , $arg_subject , $arg_body ){
		// メール送信処理
		// 文字コード退避
		$enc_sav = mb_internal_encoding();

		// メール内容編集
		// 文字コード設定
		mb_language("Ja");
		mb_internal_encoding("EUC-JP");

		// ヘッダ作成
		// 送信者
		$str_from = mb_encode_mimeheader( $arg_name ) . '<' . $arg_from . '>';
		$str_header  = "From: $str_from \n";

		// ＣＣ
		if ( $arg_cc != "" ) {
			$str_header .= "Cc: $arg_cc \n";
		}

		// ＢＣＣ
		if ( $arg_bcc != "" ) {
			$str_header .= "Bcc: $arg_bcc \n";
		}

		// Ｒｅｐｌｙ－ｔｏ
		if ( $arg_reply != "" ) {
			$str_header .= "Reply-To: $arg_reply \n";
		}

		// メーラー
		$str_mailer = "PHP/" . CONST_SYSTEM_ID_SYS;
		$str_header .= "X-Mailer: $str_mailer \n";

		// 送信（戻り値）
		$ret_tmp = @mb_send_mail( $arg_to , $arg_subject , $arg_body , $str_header );

		// 文字コード復元
		mb_internal_encoding( $enc_sav );

		// 送信（戻り値）
		return $ret_tmp;
	}

	// ログ出力
	function fnc_mail_log( $str_msg , $arg_log_fnm = 'fnc_mail_log' ){
		//□ログ出力
		$fnm_log = './log_mail/' . $arg_log_fnm . date( 'Ymd' ) . '.log';
		$fp = fopen( $fnm_log , "a" );
		$str_log = date( "Y/m/d\tH:i:s\t" ) . $str_msg . "\n";
		fwrite( $fp , $str_log );
		fclose( $fp );
	}
?>
