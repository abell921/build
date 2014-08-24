<?php
/**
*		For details on the regex used for filter(var)
*		http://stackoverflow.com/questions/12026842/how-to-validate-an-email-address-in-php
*
**/

class secureTicket
{
	private $email;
	public function __construct($key) {  //THIS CLASS TAKES THE EMAIL FROM THE POST DATA AS A VARIABLE
		$this->email = $key;
	}

	public function getEmail() {
		return $this->email_key;
	}
	
	private function validateEmail() {
		if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
			//invalid email address
			return false;
		} else {
			return true;
		}
	}
	
	public function attempt_to_clear() {
		$num_gates = 1;
		$passed_gates = 0;
		$errors = array();
		
		/******GATES*****/
		if ($this->validateEmail()) { 
			//GATE PASSED:  email given fits regex for given e-mail
			$passed_gates=$passed_gates+1;
		} else {
			array_push($errors, "Failed regex check");
		}
		/***************/
		return $errors;
	}
}

$email_key = $_POST['email'];
echo $email_key;
$ticket = new secureTicket($email_key);
$error_log = $ticket->attempt_to_clear();

/****IF THERE ARE NO ERRORS, RUN THE SCRIPT TO ADD IT TO THE DB****/
if (!empty($error_log)) {
	file_put_contents( dirname(__FILE__) . '/log/failed_tickets.txt', date("d/m : H:i :") . print_r($error_log,true) . ' | ',FILE_APPEND);
	header('Location: http://127.0.0.1:8080/ctv_build/index.php');
} else {
	echo "Passed security clearance";
	include_once( dirname( __FILE__ ) . '/include/db_connect.php' );
	$query = $DBH->prepare( 'INSERT INTO `email_log` (datetime, email) VALUES (Now(), :email)');
	if($query->execute( array(':email' => $email_key))) {
		header('Location: http://127.0.0.1:8080/ctv_build/index.php');
	} else {
		echo "failed";
		header('Location: http://127.0.0.1:8080/ctv_build/index.php');
	}
}


?>