<?php

class scf_Options {

<<<<<<< HEAD
	private $replacedOptions;
	private $defaultOptions;

=======
	private $defaultSettings;
	private $defaultOptions;
>>>>>>> a5d31d015991a8e83f64b994d6bc76a885c0eb05
	public $inputtedfields;
	private $options;
	private $passedOptions;


	/* 
	 * Constructor functions
	 */
	public function __construct() {

		// Default settings to use in the beginning
<<<<<<< HEAD
		$this->defaultOptions = array();

		// Default options to use in the beginning
		$this->replacedOptions = array(
=======
		$this->defaultSettings = array();

		// Default options to use in the beginning
		$this->defaultOptions = array(
>>>>>>> a5d31d015991a8e83f64b994d6bc76a885c0eb05
	        'form_styling'      => 'full-width',                    // Is the form 'full-width' or 'narrow'
	        'return'            => false,                           // Return the contents or echo
	        'column_class'      => 'col-sm-12',                     // Split form over two columns
	        'labels'            => true,                            // Use labels
	        'placeholders'      => false,                           // Use placeholders
	    );

		// Dummy field data
		$this->inputtedfields = maybe_unserialize( get_option( 'scf_table_fields', array() ) );

		// Final options to return
		$this->options = array();

	}


	/* 
	 * return the options
	 */
<<<<<<< HEAD
	public function get($passedOptions) {

		// Set the passed options in frontend or shortcodes
		$this->passedOptions = $passedOptions;

		// Set the default options from the database
		$this->setDefaults();

		// Replace any defaults with passed options
		$this->replaceWithPassed();

		// Merge the options
		$this->options = $this->replacedOptions + $this->defaultOptions;

		// Return the options
		return $this->options;
=======
	private function replaceWithPassed() {

		// Use a more managable variable
		$o = $this->passedOptions;

		// Is the form styling option set
		if( isset($o['width']) ) $this->defaultOptions['form_styling'] = $o['width'];

		// Should the form be returned
		if( isset($o['return']) ) $this->defaultOptions['return'] = $o['return'];

		// Change if a button is required
		if( isset($o['button']) ) $this->defaultOptions['btn'] = $o['button'];

		// Change if the form is collapsed
		if( isset($o['form_collapse']) ) $this->defaultOptions['form_collapse'] = $o['form_collapse'];

		// Change the button text if required
		if( isset($o['btn_text']) ) $this->defaultOptions['btn_text'] = $o['btn_text'];

		// Change the form title if required
		if( isset($o['form_title']) ) $this->defaultOptions['form_title'] = isset($_POST['form_title']) ? $_POST['form_title'] : $o['form_title'];

		// Change the email subject
		if( isset($o['email_subject']) ) $this->defaultOptions['email_subject'] = isset($_POST['email_subject']) ? $_POST['email_subject'] : $o['email_subject'];
>>>>>>> a5d31d015991a8e83f64b994d6bc76a885c0eb05

	}


	/* 
	 * return the options
	 */
	private function setDefaults() {

		$arr = array();

		// Use a form?
<<<<<<< HEAD
		$arr['form'] = (boolean) get_option('scf_form', '0');

		// Location for the form to be sent to
		$arr['send_to_url'] = get_option('scf_send_to', get_permalink());
=======
		$arr['form'] = get_option('scf_form', false);

		// Location for the form to be sent to
		$arr['send_to_url'] = get_permalink();
>>>>>>> a5d31d015991a8e83f64b994d6bc76a885c0eb05

		// Get the form title
		$arr['form_title'] = get_option('scf_form_title', '<h2>Enquire now!</h2>');

		// Get the email subject
		$arr['email_subject'] = get_option('scf_email_subject', 'Website Enquiry');

		// Get the email recipients
		$arr['email_recipients'] = get_option('scf_email_recipients', get_bloginfo('admin_email'));

		// What form styling should be used?
		$arr['form_styling'] = get_option('scf_form_styling', 'bootstrap');

		// Does the Bootstrap CDN need to be included?
<<<<<<< HEAD
		$arr['include_bootstrap'] = (boolean) get_option('scf_include_bootstrap', '0' );

		// Does the FontAwesome CDN need to be included?
		$arr['include_fontawesome'] = (boolean) get_option('scf_include_fontawesome', '0' );

		// Get the extra class for the submit button
		$arr['submit_class'] = get_option('scf_submit_class', 'btn-primary');
=======
		$arr['include_bootstrap'] = get_option('scf_include_bootstrap', ($arr['form_styling'] == 'bootstrap' ? true : false) );

		// Does the FontAwesome CDN need to be included?
		$arr['include_fontawesome'] = get_option('scf_include_fontawesome', ($arr['form_styling'] == 'bootstrap' ? true : false) );
>>>>>>> a5d31d015991a8e83f64b994d6bc76a885c0eb05

		// Get the success message
		$arr['success_msg'] = get_option('scf_success_msg', 'Thanks!');

		// Use reCAPTCHA or maths test
<<<<<<< HEAD
		$arr['validation'] = get_option('scf_validation', 'recaptcha');

		// Does the reCAPTCHA script need to be loaded?
		$arr['include_recaptcha'] = (boolean) get_option('scf_include_recaptcha', '0' );

		// Use a button?
		$arr['button'] = (boolean) get_option('scf_display_button');

		// Collapse the form
		$arr['form_collapsed'] = (boolean) get_option('scf_default_collapse');
=======
		$arr['validation'] = get_option('scf_validation', 'maths');

		// Use a button?
		$arr['btn'] = (get_option('scf_display_button') == 'true' ? true : false);

		// Collapse the form
		$arr['form_collapsed'] = (get_option('scf_default_collapse') == 'true' && $arr['btn'] ? true : false);
>>>>>>> a5d31d015991a8e83f64b994d6bc76a885c0eb05

		// Get the text for the button
		$arr['btn_text'] = get_option('scf_button_text', 'Get in touch now');

<<<<<<< HEAD
		// Get the extra class for the button
		$arr['btn_class'] = get_option('scf_button_class', 'btn-primary');

=======
>>>>>>> a5d31d015991a8e83f64b994d6bc76a885c0eb05
		// Get the button icon side
		$arr['btn_icon_side'] = get_option('scf_button_side', 'left');

		// Get the button icon (FontAwesome)
		$arr['btn_icon_type'] = get_option('scf_button_icon', 'fa-comments');

		// Get the public key for reCAPTCHA
		$arr['public_key'] = get_option('scf_recaptcha_public', '');

		// Get the private key for reCAPTCHA
		$arr['private_key'] = get_option('scf_recaptcha_private', '');

		// Set the final default settings
<<<<<<< HEAD
		$this->defaultOptions = $arr;
=======
		$this->defaultSettings = $arr;
>>>>>>> a5d31d015991a8e83f64b994d6bc76a885c0eb05

	}


	/* 
	 * return the options
	 */
<<<<<<< HEAD
	private function replaceWithPassed() {

		// Use a more managable variable
		$o = $this->passedOptions;

		// Is the form styling option set
		if( isset($o['width']) ) $this->replacedOptions['form_styling'] = $o['width'];

		// Should the form be returned
		if( isset($o['return']) ) $this->replacedOptions['return'] = (boolean) $o['return'];

		// Change if a button is required
		if( isset($o['button']) ) $this->replacedOptions['button'] = (boolean) $o['button'];

		// Change if the form is collapsed
		if( isset($o['form_collapse']) ) $this->replacedOptions['form_collapsed'] = (boolean) $o['form_collapse'];

		// Change the button text if required
		if( isset($o['btn_text']) ) $this->replacedOptions['btn_text'] = $o['btn_text'];

		// Change the form title if required
		if( isset($o['form_title']) ) $this->replacedOptions['form_title'] = isset($_POST['form_title']) ? $_POST['form_title'] : $o['form_title'];

		// Change the email subject
		if( isset($o['email_subject']) ) $this->replacedOptions['email_subject'] = isset($_POST['email_subject']) ? $_POST['email_subject'] : $o['email_subject'];
=======
	public function get($passedOptions) {

		// Set the passed options in frontend or shortcodes
		$this->passedOptions = $passedOptions;

		// Set the default options from the database
		$this->setDefaults();

		// Replace any defaults with passed options
		$this->replaceWithPassed();

		// Merge the options
		$this->options = $this->defaultOptions + $this->defaultSettings;

		// Return the options
		return $this->options;
>>>>>>> a5d31d015991a8e83f64b994d6bc76a885c0eb05

	}





}