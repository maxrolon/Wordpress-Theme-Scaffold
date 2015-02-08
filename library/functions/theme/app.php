<?php
namespace Library\Theme;

class App{
	
	private static 
		$instance,
		$statuses,
		$location,
		$action,
		$data,
		$return = Array();
	
	/** 
	 * 
	 *
	 */
	public static function instance() {
		if ( !isset(self::$instance ) ) self::$instance = new App();
		return self::$instance;
	}
	
	function __construct(){
		$is_action = self::cache();
		if ($is_action):
			self::header();
			self::route();
			self::end();
		endif;
	}
	
	/**
	 * When the class is instantiated, set header to
	 * return JSON
	 *
	 * @params void
	 * @returns void
	 *
	 */
	private static function header(){
		header('Content-Type: application/json');
	}
	
	/**
	 * Analyse the URL to see if it includes 'actions'
	 * and if it does, capture the location and action
	 * which will be used later load action class files
	 *
	 * @params void
	 * @returns bool if both action and location could be set
	 *
	 */
	private static function cache($custom=Array()){
		if (!$custom || empty($custom)):
			$uri = $_SERVER['REQUEST_URI'];
			preg_match('/actions\/(.[^\/]*)\/(.[^\/?]*)/',$uri,$matches);
			if (!$matches){return false;};
			$location = $matches[1];
			$action = $matches[2];
			self::$data = NULL;
		else:	
			if (isset($custom['location']) && isset($custom['action'])):	
				$location = $custom['location'];
				$action = $custom['action'];
				if (isset($custom['data'])):
					self::$data = $custom['data'];
				else:
					self::$data = NULL;
				endif;
			else:
				return false;
			endif;
		endif;
		self::$location = $location;
		self::$action = $action;
		return true;
	}
	
	/**
	 * Now with the action and location ascertained, 
	 * we see if the particular class file exists and if so, 
	 * lets load it up. If not, set headers to 404 not found.
	 *
	 * @params void
	 * @returns void
	 *
	 */
	private static function route(){
		$l = self::$location;
		$a = self::$action;
		try{
			$file = __DIR__.'/'.$l.'/'.$a.'.class.php';
			$err = file_exists($file);
			if (!$err){
				throw new \Exception('Action Does Not Exist');
			}
			include_once($file);
			if (self::$data):
				$class = '\\'.__NAMESPACE__.'\\'.$l.'\\'.$a;
				$res = $class::$instance
					->set_data(self::$data)
					->execute(self::$data);
				 array_push(self::$return,$res);
			endif;
		} catch (\Exception $e) {
			print_r($e);
			self::header_404();
		}
	}
	
	/**
	 * Set headers to 404 not found.
	 * Retun an array to communicate to the receiver 
	 * that the request ended in 404
	 *
	 * @params void
	 * @returns void
	 *
	 */
	private static function header_404(){
		header("HTTP/1.0 404 Not Found");
		$response = Array(
			'status' => 404
		);
		self::end($response);
	}
	
	
	/**
	 * Convert a supplied data to JSON, print
	 * and end the request.
	 *
	 * @params arr data to show to the user
	 * @returns void
	 *
	 */
	private static function end($data=NULL){
		if($data){
			$json = json_encode($data);
			print $json;
		};
		exit(1);
	}
	
	/**
	 * The 'Cache' method deals with new HTTP requests to
	 * interpret which class (action) to initialize. This method
	 * provides a way to integrate with other WP processes
	 * and return data to other parts of the App after an action 
	 * has been executed.
	 *
	 * @params arr settings and data provisions
	 * @returns mixed data to return at the end of the request
	 *
	 */
	public static function execute_actions($data=Array()){
		if ($data && is_array($data)):
			foreach ($data as $key => $arr):
				self::cache($arr);
				self::route();
			endforeach;
			if (self::$return) return self::$return;
			return false;
		endif;
	}
	
	public static function execute_wp_action($data=Array()){
		
	}
	
}

App::instance();

?>