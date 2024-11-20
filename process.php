<?php

//open stdout for debugging
$stdout = fopen('php://stdout', 'w');

//method to write to stdout for debugging
function debug($level, $message):void {
	fwrite($level, "PHPDEBUG: " . $message . "\n");
}

// the ONLY requirements for an email to be valid
function validEmail($email)
{
    return preg_match('/@/', $email);
}

//Retrieve form data.
//GET - user submitted data using AJAX
//POST - in case user does not support javascript, we'll use POST instead
$name = ($_GET['contactName'])? $_GET['contactName'] : $_POST['contactName'];
$email = ($_GET['email'])? $_GET['email'] : $_POST['email'];
$message = ($_GET['comments'])? $_GET['comments'] : $_POST['comments'];
$errors = array();

//debug data
//debug($stdout, 'begin contact form post');
//debug($stdout, $name);
//debug($stdout, $email);
//debug($stdout, $message);

//Simple server side validation for POST data, of course, you should validate the email
if (!$name) $errors[count($errors)] = 'Please enter your name.';
if (!validEmail($email)) $errors[count($errors)] = 'Please enter your email.';
if (!$message) $errors[count($errors)] = 'Please enter your message.';

// end execution for any errors
if ($errors) {
	http_response_code(400); //use http status 400 to indicate a bad request!
	echo join("\n", $errors);
	die();
}

$message = wordwrap($message, 70, "\r\n");
$result = mail('bronson@flashbloom.com', 'message from ' . $name . "<" . $email . ">", $message);

//different ways of handling POST vs GET
switch($_SERVER['REQUEST_METHOD']) {
	case 'POST': {
		// if ($result) {
		// 	echo 'Excellent choice! Your one step closer to growing online. Talk soon';
		// } else {
		// 	http_response_code(500); //indicate internal server error
		// 	echo 'Sorry, but an unexpected error occurred. Please try again later.';
		// }
		echo $result? 1 : 0;
		break;
	}
	case 'GET': {
		// I would indicate internal server error here but it would be annoying to refactor :)
		echo $result? 1 : 0;
		break;
	}
}

?>
