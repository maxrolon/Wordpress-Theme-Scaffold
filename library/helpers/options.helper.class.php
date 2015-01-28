<?php

namespace library;

interface bootstrap_options{
	
	/** 
	 * Ensures that the admin user has sufficient privilages
	 * @params void
	 * @return void	
	 *
	 */	
	public function check_capabilities();
	
	/** 
	 * Call the necessary WP functions based on $_POST
	 * @params string, array
	 * @return void
	 *	
	 */	
	public function trigger_actions($title,$arg);
	
	/** 
	 * Renders the from regardless of actions
	 * @params $string, array
	 * @return array
	 *	
	 */	
	public function render_form($title,$arg);
	

}

class options_group implements bootstrap_options{

	function __construct($title,$arg){
		 
		$this->check_capabilities();
		$this->trigger_actions($title,$arg);
		$this->render_form($title,$arg);
		
	 }
	
	 /** 
	 * Ensures that the admin user has sufficient privilages
	 * @params void
	 * @return void	
	 *
	 */
	function check_capabilities(){
		
			if ( !current_user_can( 'delete_pages' ) )	{
					wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
	}
	
	/** 
	 * Call the necessary WP functions based on $_POST
	 * @params string, array
	 * @return void
	 *	
	 */	
	function trigger_actions($title,$arg){
	
		if( isset( $_POST['scaffold_submit_hidden'] ) && $_POST['scaffold_submit_hidden'] == 'Y' ) {

			if ( $_POST['Submit'] == 'Save Changes' ) {
				
				foreach ($arg as $value):
				
					foreach ($value as $option => $instruction):
					
						$option_escaped = strtolower($option);
	 					$option_escaped = str_replace(' ','_', $option_escaped);
					
						$new_option = $_POST[$option_escaped];
						update_option( $option_escaped, $new_option );
					
					endforeach;
				
				endforeach;
				
			}
		
		}
	
	}
	
	/** 
	 * Renders the from regardless of actions
	 * @params array
	 * @return array
	 *	
	 */	
	function render_form($string, $array){
		 
		include(locate_template('templates/partials/settings.php')); 
		
	}
		
}

$arg = array(
	'Google Analytics' => array(
		'Analytics Code' => '',
		'Analytics Coded' => '',
	),
	'Google Analyticss' => array(
		'Analytics Codess' => '',
		'Analytics Coded' => '',
	),
);
?>