<?php

class scf_FormValidation {



	public static $errors = array();



	/* 
	 * Constructor functions
	 */
	public function __construct() {

		// Do anything first

	}



	/* 
	 * Check if the form has been completed
	 */
	public static function isFormCompleted($fields) {

		// Has the required email or name field been completed?
		$completed = true;

		// Prove me wrong
        foreach($fields as $field) {
			if( $field['required'] && empty($field['value']) && $completed) {
		        $completed = false;
		    };
		};

	    return $completed;

	}



	/* 
	 * Check if the form values are valid
	 */
	public static function isFormValid($fields, $formcompleted) {

		// Check if the form has been completed first. Return false if not.
		if(!$formcompleted) return false;

		// Has the required email or name field been completed?
        $errors = array();

        // Is the email valid
        foreach($fields as $field) {

        	// Check if the required fields have been completed
			if( !$field['value'] && $field['required'] ) {

				// Add this error to the error array
				$errors[] = (isset($field['error_title']) ? $field['error_title'] : $field['title']) . ' is required.';

			} else {

	        	switch ($field['type']) {
	        		case 'email':
						if (!filter_var($field['value'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid format in the ' . $field['title'] . ' field.';
	        			break;
	        		
	        		case 'name':
						if (!preg_match("/^[a-zA-Z ]*$/",$field['value'])) $errors[] = 'Only letters and white space allowed in the ' . $field['title'] . ' field.';
	        			break;
	        	}

			}

        }

	    // Return the validity if it is
	    if(!empty($errors)) return $errors;
	    return true;

	}



	/* 
	 * Check if the form verification is ok
	 */
	public static function isFormVerified($options = array(), $fields = array(), $formcompleted = false) {

		// Check if the form has been completed first. Return false if not.
		if(!$formcompleted) return false;

		// Set the default variable
		$verified = false;

		// Has the verification been completed properly?
		if( $options['validation'] === 'recaptcha' ) {

			$answer = isset($_POST["g-recaptcha-response"]) ? $_POST["g-recaptcha-response"] : false;

			if(!$answer) return "The reCAPTCHA wasn't completed. Please try it again";

			$secret_key = $options['private_key'];

			$fields = array(
				'secret'    =>  $secret_key,
				'response'  =>  $answer,
				'remoteip'  =>  $_SERVER['REMOTE_ADDR']
			);

		    $ch = curl_init("https://www.google.com/recaptcha/api/siteverify");
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
		    $response = json_decode(curl_exec($ch));
		    curl_close($ch);

			if ( !$response->success ) return "The reCAPTCHA wasn't entered correctly. Please try it again";

		} else {

			// Form is probably using maths. 
			foreach($fields as $field) {

				if( $field['slug'] == 'maths' && $field['value'] != 11 ) return "The maths test was incorrect. Please try it again";

			}

		}

		// Return true if an error hasn't already been returned. You've passed the test. Good job.
		return true;

	}



} 

?>