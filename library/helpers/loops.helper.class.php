<?php
namespace library\helpers;

class loop{
	
	/** 
	 * Takes the loop array and caches it in class
	 * @params array array
	 * @return array
	 *
	 */
	private $loops = false;
	
	function __construct(){
		$this->cache_loops();		
	}
	
	/** 
	 * Takes the loop array and caches it in class
	 * @params void
	 * @return void
	 *
	 */
	private function cache_loops(){
		global $theme_loops;
		if (isset($theme_loops)):
			$this->loops = $theme_loops;
		endif;
	}
	
	/** 
	 * Compares to return array to loop array
	 * Creates a new array which included only desired loops
	 * @params array array
	 * @return array
	 *
	 */
	private function restrict_loops($loop, $loops){
		$return = array();
		foreach ($loops as $key => $value):
			$pos = array_search( $key, array_keys($loops) );
			if ( !in_array($pos, $loop) ) continue;
			$return[$key] = $value;
		endforeach;
		return $return;
	}
	
	/** 
	 * Iterates through the loop array to compile output
	 * The 'Router' - Uses '$loop' to pick which
	 * method to choose for each loop call
	 * @params array array
	 * @return array
	 *
	 */
	public function retrieve($loop, $data = false, $orderby = "DESC", $xtra = ''){
		$recompiled_loops = $this->restrict_loops($loop, $this->loops);
		$return = array();
		foreach ($recompiled_loops as $key => $value):
			switch (true):
				case ($key == 'default'):
					$out = $this->loop_default($value, $data, $orderby, $xtra);
					array_push($return,$out);
				break;
			endswitch;
		endforeach;
		return $return;
	}
	
	/** 
	 * Carries out a query on a post type
	 * Using WP standards
	 * @params string array
	 * @return array
	 *
	 */
	private function wp_loop(){
		global $wp_query, $post;
		$Query = new WP_Query( $args );
	}
	
	/** 
	 * Carries out a custom SQL query on a db
	 * Using WP standards
	 * @params string array
	 * @return array
	 *
	 */
	private function default_loop($args, $num, $orderby, $xtra){
		global $wpdb;
		return $out;
	}
}

/** 
 * Returns 8 Instagram entries for the social section
 * @params string array
 * @return array
 *
 */
$theme_loops = array(
	'default_loop' => array(
		'table' => 'default',
	),
);

?>