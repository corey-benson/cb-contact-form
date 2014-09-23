<?php
/**
* @package procForm
* @author Corey Benson
* @description Class that processes HTML forms and emails them to a specified address.
**/


class CBProcessForm{

	var $email;
	var $mess;
	

	/**
	* constructor
	* @param string $email the email address to send the form 
	* results to.
	**/
	function __construct($email){
		$this->email= $email;
	}
	

	/**
	*function isEmail checks for a valid email address
	* @param $verify_email an email address to verified
	* @return a boolean based on the verification of the email address
	**/
	function isEmail($verify_email) {
	
		return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$verify_email));
	
	}

	
	/**
	*function clean removes HTML entitiles, slashs, and extraineous spaces from
	* the string passed to it
	* @param $item a string to be cleaned
	* @return a cleaned version of item
	**/
	function clean($item){
		return trim(htmlentities(strip_tags($item)));
	}


	/**
	* function handleChecks creates a comma separated string
	* from an array of checkboxes.
	* @param $info an array containing checked off boxes from a form
	* @return a comma separated string of the checkbox results
	**/
	function handleChecks($info){
		return implode(", ", $info);
	}

	/**
	* function strToArray creates an array from a delimited string
	* @param $str is a string
	* @return a separated string of results
	**/
	function strToArray($str) {
		return explode(":", $str);
	}


	/**
	* function checkIfEmpty checks whether a string is empty
	* @param $str is a string
	* @return a boolean
	**/
	function checkIfEmpty($str) {
		if (!isset($str) || $str == '') {
			return true;
		} else {
			return false;
		}
	}

	
	/**
	* function email uses PHP mail() to send an email to the address specified 
	* from the constructor and prints message to user.
	* @param $subject is the subject of the email
	* @param $message is the body of the email
	* @param $from is the from address for the email. Defaults to null
	* @param $msg is the message to display to the user. Defaults to 
	* "Thanks! Your message has been sent."
	**/
	function email($subject, $message, $from=NULL, $msg='') {

		$msg_array = $this->strToArray($message);

		// print_r($msg_array);
		// print_r(count($msg_array));
		
		if (mail($this->email, $subject, $message, "From: $from")) {
		
			if ($msg != '') { 
				$this->mess= $msg;
			}else{
				$this->mess= "Thanks! Your message has been sent.";
			}
		
		} else {
			$this->mess= "Uh Oh! There was a problem processing your message.";
		}

		// } else {
		// 	$this->mess= "Uh Oh! Please fill in all fields.";
		// }
	
	
		return $this->mess;
	}



	/**
	* function buildMsg builds a string that will serve as the email body.
	* @param $info is array from HTML form.
	* @return $message a string produced from processing the array
	**/
	function buildMsg($info) {

		$message= "";

		$message = $message . "Name: " . $this->clean($info["cb-name"]) ." \n"; 
		$message = $message . "Email: " . $this->clean($info["cb-email"]) ." \n"; 
		$message = $message . "Message: " . $this->clean($info["cb-contact"]) ." \n"; 
	
		return $message;

	}


}

?>