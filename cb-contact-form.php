<?php

/*
Plugin Name: CB Contact Form
Plugin URI: https://github.com/corey-benson/wp-contact-form
Description: A very simple plugin that generates a set contact form for users to add to their theme
Author: Corey Benson
Version: 1.0
Author URI: https://github.com/corey-benson/
*/

define('CB_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
define('CB_NAME', "CB Contact Form");
define ("CB_VERSION", "1.0");
define ("CB_SLUG", 'cb-contact-form');


function cb_build_form($sendTo, $subject) {

	$error_name = false;
	$error_email_empty = false;
	$error_email_invaild = false;
	$error_message = false;
	$spam_catcher = false;
	
	if(isset($_POST['cb-submit'])){

		include('cb-process-form.php');
		$cbProcessor= new CBProcessForm($sendTo);

		if ($cbProcessor->checkIfEmpty($_POST['cb-name'])) {
			print "<h6>What's your name?</h6>";
			$error_name = true;
		}
		if ($cbProcessor->checkIfEmpty($_POST['cb-email'])) {
			print "<h6>What's your email?</h6>";
			$error_email_empty = true;
		}
		if (!isset($_POST['cb-email'])) {
			if ($cbProcessor->isEmail($_POST['cb-email'])) {
				print "<h6>Please enter a valid email?</h6>";
				$error_email_invaild = true;
			}
		}
		if ($cbProcessor->checkIfEmpty($_POST['cb-contact'])) {
			print "<h6>message is empty?</h6>";
			$error_message = true;
		}
		if ($_POST['cb-address'] != "") {
			$spam_catcher = true;
		}

		// Check if we have errors
		if ($error_name || $error_email_empty || $error_email_invaild || $error_message || $spam_catcher) {
			print "<h3>Uh Oh! Please fill in all fields correctly.</h3>";
		} else {
			$message= $cbProcessor->email($subject, $cbProcessor->buildMsg($_POST), $_POST['cb-email']);
			print "<h3>$message</h3>";
		}
		
	}
	
	$form = '<div id="contact-form" class="contact-form">
						<form name="'. CB_SLUG .'" method="POST">
		          <h1>Contact Boisht</h1>
		          <div>
		            <input type="text" name="cb-name" id="name" class="name" value="" placeholder="Name">
		          </div>
		          <div>
		            <input type="email" name="cb-email" id="email" class="email" value="" placeholder="Email">
		          </div>
		          <div>
		            <textarea rows="10" name="cb-contact" id="contact" class="contact" placeholder="Message"></textarea>
		          </div>
		          <div style="display: none;">
		            <input type="address" name="cb-address" id="address" class="address" value="" placeholder="Address">
		            <p>Spam Catcher: If you are human please leave this field blank.</p>
		          </div>
		          <div>
		            <input type="submit" name="cb-submit" class="submit" value="Submit">
		          </div>
	          </form>
        	</div>';  	
	
	return $form;
}


/* Shortcode */

function cb_insert_form($atts, $content=null){

	extract(shortcode_atts( array('sendto' => get_bloginfo('admin_email'), 'subject' => 'Contact Form from '. get_bloginfo('name')), $atts));

	$form = cb_build_form($sendto, $subject);

	return $form;

}
add_shortcode('cb_form', 'cb_insert_form');


/** add template tag- for use in themes **/

function cb_get_form($sendTo="", $subject=""){
	$sendTo= ($sendTo == "") ? get_bloginfo('admin_email') : $sendTo;
	$subject= ($subject == "") ? 'Contact Form from'. get_bloginfo('name') : $subject;
	
	print cb_build_form($sendTo, $subject);
}

?>