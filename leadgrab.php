<?php

date_default_timezone_set("Asia/Kolkata");
require_once('functions.php');

$full_name  = get_field('fullname');
$email      = get_field('email');
$phone      = get_field('phone');
$stage      = get_field('stage');

$leadutmsource   = get_field('leadutmsource');
$leadutmmedium   = get_field('leadutmmedium');
$leadutmcampaign = get_field('leadutmcampaign');
$leadutmcontent  = get_field('leadutmcontent');
$leadutmterm     = get_field('leadutmterm');

$date 			= date('Y-m-d H:i:s', time());
$post_dump		= $_POST;
$post_dump 		= json_encode($post_dump);
$post_dump 		= $post_dump;
$form_page 		= get_form_page_url();

$type = "Zoi Antenatal LP | New Enquiry";

$error = 0;
$error_messages = array();
$output = array(
	'error' => TRUE,
	'error_messages' => array(),
	'success' => FALSE,
);

if($full_name == '') {
	$error_messages['fullname'] = "Please enter your Full Name";
	$error = 1;
}

if( ($email == '') OR  ( ! valid_email($email)) ) {
	$error_messages['email'] = "Please enter your Email Address";
	$error = 1;
}

if($phone == '') {
	$error_messages['phone'] = "Please enter your Phone Number";
	$error = 1;
}

if($phone == '') {
	$error_messages['stage'] = "Please select field";
	$error = 1;
}

if($error == 1) {
	$output['error'] = TRUE;
	$output['error_messages'] = $error_messages;
	$output['success'] = FALSE;
	echo json_encode($output);
	exit();
	//header("companySize:{$form_page}#leadForm");
	//exit();
}

ini_set("log_errors", 1);
$log_name = date('M-d-Y', time());
ini_set("error_log", "./logs/{$log_name}.log");
error_log("\nNew Enquiry | full_name: '{$full_name}', phone: '{$phone}', email: '{$email}', stage: '{$stage}', lead_type: '{$type}', date_captured: '{$date}'\n\n");

$ip = $_SERVER['REMOTE_ADDR'];
$page_url = $form_page;




/******** CURL method for Leadstore *********/ 		
$input_params=array(				
	'firstname'=> $full_name,
	'email'=> $email,
	'phone'=> $phone,
	'city'=> '',
	'message'=>'',
	'udf1'=> $stage,
	'udf2'=> '',
	'udf3'=>'',
	'udf4'=>'',	
	'udf5'=>'',	
	'udf6'=>'',							
	'udf7'=>'',
	'udf8'=>'',
	'udf9'=>'',
	'udf10'=>'',																					
	'ua_ip'=> $ip,
	'ua_device'=> '',
	'ua_query_url'=> $page_url,
	'landing_page_title'=> $type,
	'utm_source'=> $leadutmsource,
	'utm_medium'=> $leadutmmedium,
	'utm_campaign'=> $leadutmcampaign,
	'utm_content'=> $leadutmcontent,
	'utm_term'=> $leadutmterm,
	'ua_city'=>'',
	'ua_country'=>'',
	'form_data'=> $post_dump,
);	
$fields = $input_params;
$postvars = '';
	foreach($fields as $key=>$value) {
		$postvars .= $key . "=" . $value . "&";
	}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://leadstore.in/capture_leads/capture/18");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$postvars);
// in real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS, 
//http_build_query(array('postvar1' => 'value1')));
// receive server response ...

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec ($ch);
curl_close ($ch);  
/****************End CURL CALL**********************/









ob_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Zoi Hospitals</title>
</head>
<body style="font-family:Arial, sans-serif;font-size:13px;color:#000;background: #f4f4f4;line-height:1.5;padding: 30px;">
	<div style="background: #fff;max-width:550px;display: block;margin: 15px auto;padding: 30px;border-bottom: 6px solid #015788;">
		<img src="https://i.imgur.com/G5djjMb.png" alt="Zoi Hospitals" title="Zoi Hospitals" width="90" style="display: block;margin-bottom:9px;">
		
		<h1 style="font-size: 21px;display:block;margin-bottom: 0;">New Enquiry<br></h1>
		<p style="font-size: 15px;font-weight: bold;margin: 0;">Origin: <a href="<?php echo $page_url; ?>"><?php echo $page_url; ?></a></p>
		
		<div style="font-size: 13px;color: #333;display:block;margin: 15px 0 15px;max-width:360px;">
			<p style="font-size: 12px; color: #888;margin: 0 0 6px;"><?php echo date('M d, Y', strtotime($date)); ?></p>
			<p style="margin: 0 0 6px;"><strong>Name: </strong><?php echo ucwords($full_name); ?></p>
			<p style="margin: 0 0 6px;"><strong>Phone: </strong><?php echo $phone; ?></p>
			<p style="margin: 0 0 6px;"><strong>Email: </strong><?php echo $email; ?></p>
			<p style="margin: 0 0 6px;"><strong>Stage: </strong><?php echo $stage; ?></p>
		</div>
		
		<p style="margin-bottom: 0px;">Thanks,<br><strong>Admin</strong></p>
	</div>
</body>
</html>


<?php
$email_message = ob_get_clean();
$subject = 'New Lead - ' . ucwords($type);

//require_once('emails_list_testing.php');
require_once('emails_list_production.php');

send_email($from, $from_name, $to, $to_name, $subject, $email_message, '', $cc_emails, $bcc_emails);

ob_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Zoi Hospitals</title>
</head>
<body style="font-family:Arial, sans-serif;font-size:13px;color:#000;background: #f4f4f4;line-height:1.5;padding: 30px;">
<div style="background: #fff;max-width:550px;display: block;margin: 15px auto;padding: 30px;border-bottom: 6px solid #015788;">
		<img src="https://i.imgur.com/G5djjMb.png" alt="Zoi Hospitals" title="Zoi Hospitals" width="90" style="display: block;margin-bottom:9px;">
		
		<p style="font-size: 15px;font-weight: normal;margin: 15px 0px 0px;">Hi <strong><?php echo ucwords($full_name); ?></strong>,</p>
		
		<p>Thank you for visiting us. We’re so glad that you have taken some time out to fill the form!</p>

		<p><b>Why is Zoi the best hospital for you</b></p>

		<p>Zoi Hospitals, Somajiguda is centrally located and has treated over 84000 plus patients in the past 3 years and prides itself in 4000 plus successful surgeries.</p>

		<p>The team at Zoi Hospitals consists of expert doctors and excellent orthopaedic and gynaecology treatment with emergency care for critical cases.</p>

		<p>To explore further, click here - <a href="https://zoihospitals.com/about-us/">https://zoihospitals.com/about-us/</a></p>

		<p><b>Get an expert opinion for</b></p>

		<p>You can visit Zoi if you are facing any of the issues listed below for expert care and opinion,</p>

		<ol>
			<li>Normal pregnancy, preterm labour</li>
			<li>High risk Pregnancy, Pregnancy complicated by any medical condition</li>
			<li>Heavy menstrual bleeding</li>
			<li>Polycystic ovarian syndrome (PCOS)</li>
			<li>Endometriosis</li>
			<li>Uterine fibroids</li>
			<li>Adenomyosis</li>
			<li>Infertility issues, Fertility counselling</li>
			<li>Family planning methods both temporary and permanent, Medical and Surgical methods</li>
		</ol>

		<p>To know more, click here - <a href="https://zoihospitals.com/">https://zoihospitals.com/</a></p>

		<p><b>We care for your health</b></p>

		<p>We believe in ‘Prevention is better than cure’. We offer preventive health check-up packages which include tests for a number of conditions across different age groups, including arthritis, anaemia, bone profile, cancer, hypertension, infertility, and more.</p>

		<p>All the packages are designed to detect illness and disease early on, helping in early diagnosis and treatment</p>

		<p>To know more about these packages, click here - <a href="https://zoihospitals.com/packages/">https://zoihospitals.com/packages/</a></p>

		<p>Stay Healthy and Stay Happy!</p>

		<p style="margin-bottom: 0px;">Thanks,<br><strong>Zoi Hospitals</strong></p>
	</div>
</body>
</html>


<?php
$email_message = ob_get_clean();
$subject = 'New Lead - ' . ucwords($type);

//require_once('emails_list_testing.php');
//require_once('emails_list_production.php');

send_email($from, $from_name, $email, ucwords($full_name), 'Thank You', $email_message, '', NULL, NULL);

//mysql_close();
//$success_message['success_message'] = "Thank you! Your registration was successfull.";
//header("companySize:thankyou.html");

$output['error'] = FALSE;
$output['error_messages'] = array();
$output['success'] = TRUE;
$output['success_message'] = "Thank you for your interest.<br>We will get in touch with you soon.";
echo json_encode($output);
exit();
?>
