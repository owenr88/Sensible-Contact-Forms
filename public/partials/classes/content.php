<?php

class scf_Content {



	public $emailContent;
	public $pageContent;
	private $successMessageReady;
	private $form_id;



	/* 
	 * Constructor functions
	 */
	public function __construct() {

		// Do anything first
		$this->successMessageReady = false;

		// Set the form ID
		$this->form_id = "form_".rand(0,10000);

	}



	/* 
	 * Constructor functions
	 */
	public function setVendors($options) {

		// Does it need bootstrap
		if( $options['include_bootstrap'] ) {

			// Include Bootstrap styles
			wp_enqueue_style('scf_bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css');

			// Include Bootstrap scripts
			wp_enqueue_script('scf_bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js');

		}

		// Does it need FontAwesome?
		if( $options['include_fontawesome'] ) {

			// Include FontAwesome styles
			wp_enqueue_style('scf_fontawesome-css', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');

		}

		// Does it need reCAPTCHA?
		if( $options['include_recaptcha'] ) {

			// Include reCAPTCHA styles
			wp_enqueue_script('scf_recaptcha-js', 'https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit#asyncload#deferload');

		}

	}



	/* 
	 * Add the form to the page content
	 */
	public function addForm($options = array(), $fields = array(), $errors = array(), $isresp = false, $formcompleted = false) {

		// Return false if the success message has already been created - we don't need the form!
		if( $this->successMessageReady ) return false;

		// Open the wrapping divs - .in SHOWS the content
	    $content = '<div id="'.$this->form_id.'" class="bs-component ' . ($options['form_collapsed'] && $options['button'] ? 'collapse' : 'collapse in' ) . '">';

		    // Open the row
		    $content .= '<div class="row">';

		        // Set the styling div around the form
		        $content .= '<div class="'.( $options['form_styling'] != "full-width" ? "col-sm-6 col-sm-offset-3" : "col-xs-12" ).'">';

		        	// Set the form title
		        	$content .= '<h2 class="text-center">'.$options['form_title'].'</h2><br>';

			    	// Print out the errors if there are any
			        foreach($errors as $error) {

			        	// Add the error to the content
			        	$content .= '<div class="alert alert-danger" role="alert">'.$error.'</div>';

			        }

		        	// Add the validation error to the content and hide it
		        	$content .= '<div class="alert alert-danger error-notice" role="alert">There are some errors on the form. Please correct and re-submit.</div>';

		        	// Open the actual form
		            $content .= '<form class="form-horizontal row" name="simple_contact_form" method="post" action="" id="form-holder" onsubmit="return validateForm(\''.$this->form_id.'\')" >';

		            	// Start outputting each field
		                foreach($fields as $item) {

		                	// Continue the loop if 'exclude' is selected
		                	if($item['exclude']) continue;

		                	// Start each field off with a default wrapper and check if it's required
		                    $content .= '<div class="'.($item['required'] ? 'validation' : '').' '.$item['slug'].' '.$options['column_class'].'">';

	                    		// Open the form group
	                    		$content .= '<div class="form-group">';

	                    			// Check if the field is a checkbox and if it uses labels
				                    if($options['labels'] && $item['type'] != 'checkbox') {

				                    	// Set the label
				                        $content .= '<label class="row-item col-md-3" for="'.$item['slug'].'"><span>'.$item['title'].($item['required'] ? ' *' : '').'</span></label>';

				                        // Set the next classes and offsets
				                        $nextclass = 'col-md-9';
				                        $offset = 'col-md-offset-3';

				                    } elseif(!$options['labels']) {

				                    	// Check if the field is a selectbox
				                        if($item['type'] == 'select') {

				                        	// Open the label
				                            $content .= '<label class="row-item col-md-12" for="'.$item['slug'].'"><span>'.$item['title'].($item['required'] ? ' *' : '').'</span></label>';

				                        } else {

				                        	// Set the next class
				                            $nextclass = 'col-md-12';

				                        }

				                    };

				                    // Are we using placeholders, precious?
				                    $placeholder = ($options['placeholders'] ? ' placeholder="'.$item['title'].($item['required'] ? ' *' : '').'"' : '');

				                    // Is the type a checkbox? Set it's field with a label
				                    if($item['type'] == 'checkbox') {

				                        $content .= '<div class="row-item">';

				                        	$content .= '<label class="row-item '.$nextclass.' '.$offset.' checkbox" for="'.$item['slug'].'">';

				                        		$content .= '<input name="'.$item['slug'].'" id="'.$item['slug'].'" type="checkbox" value="Yes" checked="'.$item['value'].'">';

			                        			$content .= '<span>'.$item['title'].($item['required'] ? ' *' : '').'</span>';

		                        			$content .= '</label>';

	                        			$content .= '</div>';

				                    };

				                    // Open the row item
				                    $content .= '<div class="row-item '.$nextclass.'">';

				                    	// Check what type this is and output the field
			                            if($item['type'] == 'text' || $item['type'] == 'email' || $item['type'] == 'name') {

			                            	// Set this as a text box
			                                $content .= '<input name="'.$item['slug'].'" id="'.$item['slug'].'" class="form-control" type="text" value="'.$item['value'].'"'.$placeholder.'>';

			                            } else if($item['type'] == 'textarea') {

			                            	// Set this as a textarea
			                                $content .= '<textarea name="'.$item['slug'].'" id="'.$item['slug'].'" class="form-control" '.$placeholder.'>'.$item['value'].'</textarea>';

			                            } else if($item['type'] == 'select') {

			                            	// Set this as a select box
			                                $content .= '<select name="'.$item['slug'].'" id="'.$item['slug'].'" class="form-control">';

			                                	// Cycle through each option for the select box
			                                    foreach($item['options'] as $opt) {

			                                		// Add the option to the select box
			                                        $content .= '<option value="'.sanitize_title($opt).'">'.$opt.'</option>';

			                                    }

		                                    // Close the select box
			                                $content .= '</select>';

			                            };

		                            // Close the row item
			                        $content .= '</div>';

			                        // Clear any floats
			                        $content .= '<div class="clearfix"></div>';

		                        // Close the form group
			                    $content .= '</div>';

		                        // Clear any floats
		                        $content .= '<div class="clearfix"></div>';

		                    // Close the field wrapper
		                    $content .= '</div>';

		                };

		                // Check if we need recaptcha. Maths is already added as a field if it's selected.
		                if($options['validation'] === 'recaptcha' && $options['public_key'] && $options['private_key']) {

		                	// Open the validation wrapper
		                    $content .= '<div class="' . $options['column_class'] . '">';

		                    	// Open the row item
		                    	$content .= '<div id="row-item" class="form-group">';

		                    		// Open the row
		                    		$content .= '<div class="'.$nextclass.' '.$offset.'">';

		                    			// Add the content
		                    			$content .= '<div id="recaptcha_'.$this->form_id.'" class="recaptcha-box" site-key="'.$options['public_key'].'"></div>';

	                    			// Close the row
	                    			$content .= '</div>';

	            				// Close the row item
	                			$content .= '</div>';

	        				// Close the validation wrapper
	            			$content .= '</div>';
		                
		                };

		                // Clear the floating for the form so far
		                $content .= '<div class="clearfix"></div>';

		                // Open the column
		                $content .= '<div class="'.$options['column_class'].'">';

			                // Add the hidden items and submit page. Opening the form group
			                $content .= '<div class="form-group">';

			                	// Opening the column
			                    $content .= '<div class="col-md-6 col-md-offset-3">';

			                		// Set the previous page hidden input
			                        $content .= '<input type="hidden" name="prevpage" value="'.get_permalink().'">';

			                		// Set the email subject hidden input
			                        $content .= '<input type="hidden" name="email_subject" value="'.$options['email_subject'].'">';

			                		// Set the form title hidden input
			                        $content .= '<input type="hidden" name="form_title" value="'.htmlentities($options['form_title']).'">';

			                		// Set the form ID hidden input
			                        $content .= '<input type="hidden" name="form_id" value="'.$this->form_id.'">';

			                		// Set the submit button
			                        $content .= '<button type="submit" class="btn btn-block ' . $options['submit_class'] . '">Submit</button>';

		                        // Close the column
			                	$content .= '</div>';

			                	// Clear the floats
			                	$content .= '<div class="clearfix"></div>';

			            	// Close the form group
			            	$content .= '</div>';

		            	// Close the col
		            	$content .= '</div>';

	            	// Close the form
		            $content .= '</form>';

	            // Close the form wrapper
		        $content .= '</div>';

		        // Clear the floats
		        $content .= '<div class="clearfix"></div>';

	        // Close the row
		    $content .= '</div>';

	    // Close the wrapping div
	    $content .= '</div>';

		// Send this content to the page content to be executed
    	$this->addToPageContent($content);
		
	}



	/* 
	 * Add the button to the page content
	 */
	public function addButton($options = array()) {

		// Return false if the success message has already been created
		if( $this->successMessageReady ) return false;

	    // Check if it needs a url to link to or if it collapses
	    if($options['form']) {
	    	$actions = 'data-toggle="collapse" data-target="#'.$this->form_id.'" href="javascript:void(0);"';
	    } else {
	    	$actions = 'href="'.$options['send_to_url'].'"';
	    }

    	$content = '<a class="btn btn-block ' . $options['btn_class'] . '" '. $actions . '>';

		    // Set the button contents
		    $content .= $options['btn_text'];

		    // Check if the button has a side icon. Set the side and icon if so.
		    if($options['btn_icon_side'] != "none") {
		    	$content .= '<i class="fa '.$options['btn_icon_type'].' pull-'.$options['btn_icon_side'].'" style="font-size: 14pt;"></i>';
		    }

	    $content .= '</a>';

		// Send this content to the page content to be executed
	    $this->addToPageContent($content);


	}



	/* 
	 * Create the validation script for required items
	 */
	public function addValidationScript($items) {

		global $scf_inserted_validation_javascript;

		// Return if it's already been defined
		if( isset($scf_inserted_validation_javascript) && $scf_inserted_validation_javascript === true ) return false;

	    $script = '<script>
	        function validateForm(form_id) {

	            var submit = true,
	            	$ = jQuery,
	            	form = "#" + form_id;

                $(form + " .error-notice").removeClass( "show" );

            	';

	            foreach($items as $item) {

	                if( !isset($item['required']) || !$item['required'] || !isset($item['slug']) ) continue;

                    $script .= '

                    var input_el = $(form + " #'.$item['slug'].'[name=\''.$item['slug'].'\']");
                    var valid_el = $(form + " .'.$item['slug'].'");
                    var is_checkbox = input_el.is(":checkbox");

                    valid_el.removeClass( "has-error" );

                    if( input_el.val() == "" || (is_checkbox && !input_el.prop( "checked")) ) {
                        valid_el.addClass( "has-error" );
                        submit = false;
                    }';

	            };

	            $script .= '

                if(!submit) $(form + " .error-notice").addClass( "show" );

	            return submit;
	        };
	    </script>
	    ';

		$scf_inserted_validation_javascript = true;

	    $this->addToPageContent($script);
	}



	/* 
	 * Add the success message to the content
	 */
	public function addSuccessMessage($options) {

		// Let the other bits know the form is successful
		$this->successMessageReady = true;

		// Add the success message to the content
		$this->addToPageContent($options['success_msg']);

	}



	/* 
	 * Add content to the page body
	 */
	public function addToPageContent($content) {

		// Add the new content to the overall page content
		$this->pageContent .= $content;

	}



	/*
	 * Add the script for recaptcha if it hasn't already been defined
	 */
	public function checkRecaptchaScript() {

		global $scf_inserted_recaptcha_javascript;

		// Return if it's already been defined
		if( isset($scf_inserted_recaptcha_javascript) && $scf_inserted_recaptcha_javascript === true ) return false;

		// Add the script to the content
		$script = <<<EOD

			<script type="text/javascript">
			    var CaptchaCallback = function(){
			    	var $ = jQuery;
			    	$(document).find('.recaptcha-box').each(function(i,val,array) {
			    		var id = $(this).attr('id');
			    		var site_key = $(this).attr('site-key');
			        	grecaptcha.render(id, {'sitekey' : site_key});
			    	})
			    };
			</script>

EOD;

		$this->addToPageContent($script);

		$scf_inserted_recaptcha_javascript = true;

	}
	


} 

?>