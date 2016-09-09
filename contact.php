<?php
require_once '../../../wp-config.php';
// We should respond appropriately to malicious code in our form
function is_malicious($input) {
	$is_malicious = false;
	// Content we will consider malicious to check for
	$bad_inputs = array( "\r", "\n", "mime-version", "content-type", "bcc:", "cc:", "to:", "<", ">", "&lt;", "&rt;", "a href", "/a", "http:", "/URL", "URL=" );
	// And if we have some nasty input . . .
	foreach ( $bad_inputs as $bad_input ) {
		if ( strpos(strtolower($input), strtolower($bad_input) ) !== false ) {
			$is_malicious = true;
			// Boom!
			break;
		}
	}
	// Houston, we have a problem.
	return $is_malicious;
}
// Keep our form data clean
$name    	=  stripslashes(trim($_POST['contact_name']));
$email    	=  stripslashes(trim($_POST['contact_email']));
$url      	=  stripslashes(trim($_POST['contact_url']));
$subject  	=  stripslashes(trim($_POST['contact_subject']));
$message  	=  stripslashes(trim($_POST['contact_message']));
$cc			=  stripslashes(trim($_POST['contact_cc']));
// Clear any prior errors
$proceed = true;
// Do not proceed reasons
// 1: empty field
// 2: invalid email
// 3: incorrect answer
// 4: malicious code
// Check the name input for problems.
if ( empty( $name ) || is_malicious( $name ) ) {
	$proceed = false;
	$name_error = 1;
}
// Check the e-mail input for problems.
if ( !is_email( $email ) || is_malicious( $email ) ) {
	$proceed = false;
	$email_error = 1;
}
// Check the subject input for problems.
if ( empty( $subject ) || is_malicious( $subject) ) {
	$proceed = false;
	$subject_error = 1;
}
// Check the message input for problems.
if ( empty( $message ) ) {
	$proceed = false;
	$message_error = 1;
}

//$sent = check_input();
if ( $proceed ) {

	// Let's get some variables we're going to use multiple times
	$recipient     =  '<' . get_bloginfo('admin_email') . '>';
	$user          =  $name . ' <' . $email . '>';
	// Start our email with its headers
	$headers       =  "MIME-Version: 1.0\r\n";
	// Our form has to match the encoding the user is typing it in, i.e., your blog charset
	$headers      .=  'Content-Type: text/plain; charset="' . get_option('blog_charset') . "\"\r\n";
	// Our generic mailer-daemon is just going to be WordPress@EXAMPLE.COM, where EXAMPLE.COM is your domain in lowercase
	$sitename      =  strtolower($_SERVER['SERVER_NAME']);
	// If we have the www., let's drop it safely
	if ( substr( $sitename, 0, 4 ) == 'www.' ) {
		$sitename  =  substr( $sitename, 4 );
	}
	// Our from email address
	$from_email    =  apply_filters( "wp_mail_from", "noreply@$sitename" );
	// Our from email name
	$from_name     =  apply_filters( 'wp_mail_from_name', 'Kreativ Kenn' );
	// And we begin the headers
	$headers      .=  "From: $from_name <$from_email>\r\n";
	$headers      .=  "Reply-To: $user\r\n";
	// Since we allow CC'ing, we can smartly only send one email
	if ( $cc ) {
		// If CC'ing, we'll BCC: you and TO: the user.
		$to        =  $user;
		$headers  .=  "Bcc: $recipient\r\n";
	} else {
		// If no, just TO: you.
		$to        =  $recipient;
	}
	// We should include X data for the mailer version
	$headers      .=  'X-Mailer: PHP/' . phpversion() . "\r\n";
	// Build our subject line for the email
	$subject       =  '' . $_POST['contact_subject'];
	// And our actual message with extra stuff
	$message       =  strip_tags(trim($_POST['contact_message'])) . "\n\n---\n";
	if ( !empty( $url ) ) {
		if ( strpos( $url, 'www.') === false && strpos( $url, 'http://') === false && strpos( $url, 'https://') === false ) {
			$url = 'www.' . $url;
		}
		$message      .=  'My website: ' . strip_tags(trim($url)) . "\n\n---\n";
	}
	// Don't show keywords in the email unless we have some
	if ($keywords) {
		$message  .=  __( 'Keywords: ', 'easy_contact' ) . $keywords . "\n\n";
	}
	$message	  .=  '© ' . date('Y') . ' ' . get_bloginfo('name') . ' | ' . str_replace('http://', '',get_bloginfo('url')) . ' | All Rights Reserved.';
	//$message      .=  __( 'Form referrer: ', 'easy_contact' ) . strip_tags(trim($_POST['ec_referer'])) . "\n";
	//$message      .=  __( 'Orig. referrer: ', 'easy_contact' ) . $orig_referer . "\n";
	//$message      .=  __( 'User agent: ', 'easy_contact' ) . trim($_SERVER['HTTP_USER_AGENT']) . "\n";
	// Let's build our email and send it
	$mail = wp_mail( $to, $subject, $message, $headers );
	// Make sure it sent
	if ($mail) $sent = true;
	$sent = true;
}

$result = array("name_error" => $name_error, "email_error" => $email_error, "subject_error" => $subject_error, "message_error" => $message_error, "sent" => $sent);
echo json_encode($result);
?>