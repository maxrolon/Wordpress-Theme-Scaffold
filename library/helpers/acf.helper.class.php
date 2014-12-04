<?php

namespace library\helpers;

/**
 *
 * @package Bootstrap WP Theme
 * @author Max Rolon <max.rolon@barrelny.com>
 * @version 0.1
 *
 *  
 */

interface bootstrap_fields{
	
	/** 
	 * Automatically prefixes 'field' to all keys and names 
	 *	
	 */	
	const acf_prefix = 'field';
	
	/** 
	 * Changes this to be theme specific
	 * Escapes the name of all fields
	 * @example 'amex'
	 *	
	 */	
	const theme_prefix = 'bootstrap';
	
	/** 
	 * The function that constructs the necessary array, ready for ACF
	 * Compiles the outputs of all other functions
	 * @params string, array
	 * @return void
	 *	
	 */	
	public function construct_array($group_name,$array);
	
	/** 
	 * Concats the above to constants
	 * @params string name of feild group
	 * @return void	
	 *
	 */	
	public function prefix($group_name);
	
	/** 
	 * Constructs the array of fields
	 * @params array
	 * @return array
	 *	
	 */	
	public function construct_fields($array);
	
	/** 
	 * Constructs the array of location information
	 * @params array
	 * @return array
	 *	
	 */	
	public function construct_location($array);

}


class field_group implements bootstrap_fields{
	
	/** 
	 * Stores the unique name for each field group
	 *	
	 */	
	private $name;
	
	/** 
	 * Feeds arguments into the necessary functions
	 *	
	 */	
	function __construct($group_name, $array){
		
		$this->prefix($group_name);
		$this->construct_array($group_name,$array);
	}
	
	/** 
	 * The function that constructs the necessary array, ready for ACF
	 * Compiles the outputs of all other functions
	 * @params string, array
	 * @return void	
	 *	
	 */	
	function construct_array($group_name,$array){
		
		$temp_id = strtolower($group_name);
		$temp_id = str_replace(' ','_',$temp_id);
		
		$id = $temp_id;
		
		$title = $group_name;
		
		$fields = $this->construct_fields($array);
		
		$location = $this->construct_location($array);
		
		$options = $array['options'];
		
		$menu_order = $array['menu_order'];
		
		$output = array(
			'id' => $id,
			'title' => $title,
			'fields' => $fields,
			'location' => $location,
			'options' => $options,
			'menu_order' => $menu_order,
		);
		
		register_field_group($output);
	}
	
	/** 
	 * Concats the above to constants
	 * @params string name of feild group
	 * @return void
	 *	
	 */	
	function prefix($group_name){
		
		$temp_group_name= strtolower($group_name);
		$escaped_group_name = str_replace(' ','_',$temp_group_name);
		
		$this->name = self::acf_prefix.'_'.self::theme_prefix.'_'.$escaped_group_name;
	}
	
	/** 
	 * Constructs the array of fields
	 * @params array
	 * @return array
	 *	
	 */	
	/** 
	 * Constructs the array of fields
	 * @params array
	 * @return array
	 *	
	 */	
	function construct_fields($array){
		$fields = $array['fields'];
		$output = array();
		$sub_fields = array();
		
		foreach ($fields as $key => $value):
			
			if (isset($value['sub_fields']))
			{
			
				$temp_key = strtolower($key);
				$field_key = str_replace(' ','_',$temp_key);
				
				foreach($value['sub_fields'] as $sub_key => $sub_value):
				
					$temp_sub_key = strtolower($sub_key);
					$field_sub_key = str_replace(' ','_',$temp_sub_key);
					
					$sub_field = array(
						'key' => $this->name.'_'.$field_sub_key,
						'label' => $sub_key,
						'name' => $this->name.'_'.$field_sub_key,
						'type' => $sub_value[0],
						'instructions' => $sub_value[1],
					);
					
					array_push($sub_fields, $sub_field);
				
				endforeach;
				
				$field = array(
					'key' => $this->name.'_'.$field_key,
					'label' => $key,
					'name' => $this->name.'_'.$field_key,
					'type' => 'repeater',
					'sub_fields' => $sub_fields,
					'row_min' => 0,
					'row_limit' => '',
					'layout' => isset($value['layout']) ? $value['layout'] : 'row',
					'button_label' => $value['add_label'],
					'default_value' => '',
					'toolbar' => 'full',
					'media_upload' => 'yes',
					
				);
				
				if (isset($value['conditional'])):
					if ( is_array($value['conditional']) && sizeof($value['conditional']) == 3):
					
						$temp_conditional_field = strtolower($value['conditional'][0]);
						$conditional_field = str_replace(' ','_',$temp_conditional_field);
							
						$conditional_logic = array(
							'status' => 1,
							'allorany' => 'all',
							'rules' => array(
								array(
									'field' => $this->name.'_'.$conditional_field,
									'operator' => $value['conditional'][1],
									'value' => $value['conditional'][2],
								),
							),
						);	
					
						$field['conditional_logic']	= $conditional_logic;
					
					endif;
				endif;
			
				$sub_fields = array();
			
			} elseif ($value[0] == 'select'){
			
				$temp_key = strtolower($key);
				$field_key = str_replace(' ','_',$temp_key);
				
				$choices = array();
				$i = 0;
				
				foreach ($value[2] as $choice):
				
					$temp_choice_key = strtolower($choice);
					$choice_key = str_replace(' ','_',$temp_choice_key);
				
					$choices[$choice_key] = $choice;
					
					if ($i == 0):
						$default = $choice_key;
					endif;
					
					$i++;
				endforeach;
				
				$field = array(
					'key' => $this->name.'_'.$field_key,
					'label' => $key,
					'name' => $this->name.'_'.$field_key,
					'type' => $value[0],
					'instructions' => $value[1],
					'choices' => $choices,
					'default_value' => $default,
				);
				
			} else {
			
				$temp_key = strtolower($key);
				$field_key = str_replace(' ','_',$temp_key);
				
				$field = array(
					'key' => $this->name.'_'.$field_key,
					'label' => $key,
					'name' => $this->name.'_'.$field_key,
					'type' => $value[0],
					'instructions' => $value[1],
				);
				
			}
			
			if (isset($value[2])):
				if ( is_array($value[2]) && sizeof($value[2]) == 3):
				
				$temp_conditional_field = strtolower($value[2][0]);
				$conditional_field = str_replace(' ','_',$temp_conditional_field);
					
				$conditional_logic = array(
					'status' => 1,
					'allorany' => 'all',
					'rules' => array(
						array(
							'field' => $this->name.'_'.$conditional_field,
							'operator' => $value[2][1],
							'value' => $value[2][2],
						),
					),
				);	
				
				$field['conditional_logic']	= $conditional_logic;
				
				endif;
			endif;
			
			array_push($output, $field);
	
		endforeach;
		
		return $output;
	}
	
	/** 
	 * Constructs the array of fields
	 * @params array
	 * @return array
	 *	
	 */	
	function construct_location($array){
		
		$location = $array['location'];
		
		$type = ( strpos($location, '.php') ? 'page_template' : 'post_type');
		
		$output = array(array(array(
			'param' => $type,
			'operator' => '==',
			'value' => $location,
		),),);
		
		return $output;
	}
	
}

/** 
 * Example of new input array
 *	
 */	

$args = array(
	'fields' => array(
		'field 0' => array('image','Enter instructions if needed'),
		'field 1' => array('text','Enter instructions if needed'),
		'field 2' => array('image','Enter instructions if needed'),
		'field 3' => array('text','Enter instructions if needed'),
		'field 4' => array('image','Enter instructions if needed'),
		'field 6' => array(
			'add_label' => 'Add Stuff',
			'sub_fields' => array(
				'field 5' => array('image','Enter instructions if needed'),
				'field 6' => array('text','Enter instructions if needed'),
				'field 7' => array('image','Enter instructions if needed'),
				'field 8' => array('image','Enter instructions if needed'),
			),
		),
	),
	'location' => 'page',
	'options' => array(
		'position' => 'normal',
		'layout' => 'default',
		'hide_on_screen' => array('the_content'),
	),
	'menu_order' => 0,
);
?>