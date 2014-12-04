<div class="wrap">
	<h2><?php echo $string ?></h2> 

	<form name="theme_settings" method="post" action="">
		
		<input type="hidden" name="scaffold_submit_hidden" value="Y">
	
		<?php foreach($array as $key => $value):
			
			echo '<h3>'.$key.'</h3>';
				
			foreach($value as $option => $instruction):
						
				$option_escaped = strtolower($option);
				$option_escaped = str_replace(' ','_', $option_escaped);
				$option_saved = get_option($option_escaped);
				
				echo'
				<table>
					<tr>
						<td>
							<h4>'.$option.'</h4>
							<label>'.$instruction.'</label>
							<br/>
							<input type="text" name="'.$option_escaped.'" value="'.$option_saved.'" size="40" maxlength="255">
						</td>
					</tr>
				</table>';
			
			endforeach;
		
		endforeach; ?>
		
		<p class="submit"><input type="submit" name="Submit" class="button-primary" value="Save Changes" /></p>

	</form>
</div>