<?php
session_start();

function send_email($from, $from_name, $to, $to_name, $subject, $message, $plain_text_message='', $cc_emails=null, $bcc_emails=null) {
	
	$msgTo = $to;
	$to = array($to);
	
	if($cc_emails != NULL) {
		$to = array_merge($to, $cc_emails);
	}
	
	if($bcc_emails != NULL) {
		$to = array_merge($to, $bcc_emails);
	}
	
	$mailstatus = _send_api($from, $from_name, $to, $to_name, $subject, $message, $plain_text_message, $cc_emails, $bcc_emails, TRUE, $msgTo);
	$mailstatus = json_decode($mailstatus, TRUE);
	
	if($mailstatus['success'] != 1) {
	    
	    $cc_string = '';
		if( (is_array($cc_emails)) AND (count($cc_emails > 0)) ) {
			$cc_string  = 'Cc: ';
			$cc_string .= implode(',', $cc_emails);
		}
		$bcc_string = '';
		if( (is_array($bcc_emails)) AND (count($bcc_emails > 0)) ) {
			$bcc_string  = 'Bcc: ';
			$bcc_string .= implode(',', $bcc_emails);
		}
		$headers = array("MIME-Version: 1.0",	"Content-type: text/html", "From: {$from}", "Reply-To: {$from}");
		if($cc_string != '') {
			array_push($headers, $cc_string);
		}
		if($bcc_string != '') {
			array_push($headers, $bcc_string);
		}
		array_push($headers, "X-mailer: PHP/" . PHP_VERSION);
		$headers = implode("\r\n", $headers);
		mail($msgTo , $subject , $message, $headers);
		return TRUE;
	}
}

function _send_api($from, $from_name, $to, $to_name, $subject, $message, $plain_text_message, $cc_emails=null, $bcc_emails=null, $isTransactional=TRUE, $msgTo) {
	
	$url = 'https://api.elasticemail.com/v2/email/send';
	
	try {
		
		$post = array(
			'from'            => $from,
			'fromName'        => $from_name,
			'apikey'          => '49ED4332BB20611540C62D669E7FB21FF69688C01ED543F72522F21199C313A6A58EA9CF0B49281C2F08301B3FFBD43E',
			'subject'         => $subject,
			'to'              => implode(',', $to),
			'msgTo'           => $msgTo,
			//'msgCC'           => implode(',', $cc_emails),
			//'msgBcc'          => implode(',', $bcc_emails),
			'bodyHtml'        => $message,
			'bodyText'        => $plain_text_message,
			'isTransactional' => $isTransactional,
		);
		
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL            => $url,
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => $post,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER         => false,
			CURLOPT_SSL_VERIFYPEER => false,
		));
		
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	catch(Exception $ex){
		return $ex->getMessage();
	}
}

function get_field($field_name) {
	if( ! isset($_POST[$field_name])) {
		return NULL;
	}
	$value = trim(addslashes($_POST[$field_name]));
	$_SESSION[$field_name] = $value;
	return $value;
}

function get_form_page_url() {
	$url_parts = parse_url($_SERVER['HTTP_REFERER']);
	if(isset($url_parts['query'])) {	
		$queries = explode('&', $url_parts['query']);
		foreach($queries as $key => $query) {
			if(strpos($query, 'error_') !== FALSE) {
				unset($queries[$key]);
			}
		}
		$queries = implode('&', $queries);
		$query_part = '?'.$queries.'&';
	} else {
		$query_part = '?';
	}
	return strtok($_SERVER['HTTP_REFERER'],'?') . $query_part;
}

function valid_email($email) {
	if( ! filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
		return TRUE;
	}
	
	return False;
}

function valid_indian_mobile($phone_number) {
	$matches = null;
	preg_match('/^(((\+?91|0)\-?)?)?\d{10}$/', $phone_number, $matches);
	if ($matches) {
		return true;
	}
	return false;
}

function valid_mobile($phone_number) {
	$matches = null;
	preg_match('/^(((\+?[0-9]{1,2}|0)\-?)?)?\d{10}$/', $phone_number, $matches);
	if ($matches) {
		return true;
	}
	return false;
}

function passed_reCaptcha() {
	require_once "recaptchalib.php";
	
	$secret     = "6LexLgoUAAAAAJIwhvobnqgFx-316Uo49H_nyv2x";
	$response   = NULL;
	$reCaptcha  = new ReCaptcha($secret);
	
	if ( (isset($_POST["g-recaptcha-response"])) AND ( ! empty($_POST["g-recaptcha-response"])) ) {
		$response = $reCaptcha->verifyResponse(
			$_SERVER["REMOTE_ADDR"],
			$_POST["g-recaptcha-response"]
		);
	} else {
		return FALSE;
	}
	
	if( ($response != NULL) AND ($response->success) ) {
		return TRUE;
	}
	return TRUE;
}
