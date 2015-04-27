<?php 

if(!function_exists('getcontrols'))
{
	function getcontrols($key, $value, $settings_obj, $langfile='settings')
	{
		//die($langfile);
	
	    if(is_array($value) && sizeof($value))
		foreach($value as $name=>$input):  
		
		$type = $input[0];
	    $value = (isset($settings_obj->$name)) ? $settings_obj->$name : $input[1];
	    $html_options = (isset($input[2])) ? $input[2] : array();
	    $help = (isset($input['help'])) ? $input['help'] : '';
		    	
			switch($type)
		    	{
		    		case 'checkbox':
		    			?>
		    			<div class="row">
		    				<?php echo CHtml::checkBox($name, $value, $html_options); ?>
		    				<?php echo CHtml::label(Yii::t($langfile, $key.'_'.$name),$name); ?>	
		    				<?php if(!empty($help)):?><div class="help"><?php echo $help; ?></div> <?php endif;?>    				
		    			</div>
		    			<?php 
		    		break;
		    		
		    		case 'text':
		    			?>
		    			<div class="row">
		    				<?php echo CHtml::label(Yii::t($langfile, $key.'_'.$name),$name); ?>
		    				<p><?php echo CHtml::textField($name, $value, $html_options); ?></p>
		    				<?php if(!empty($help)):?><div class="help"><?php echo $help; ?></div> <?php endif;?> 
		    			</div>
		    			<?php 
		    		break;
		    		
		    		case 'static-text':
		    			?>
		    			<div class="row">	  
		    				<?php echo CHtml::label(Yii::t($langfile, $key.'_'.$name),$name); ?>  
		    				<?php echo CHtml::hiddenField($name, $value, $html_options); ?>
		    							
		    				<p><?php echo $value; ?></p>		    				
		    			</div>
		    			<?php 
		    		break;
		    		
		    		case 'password':
		    			?>
		    			<div class="row">
		    				<?php echo CHtml::label(Yii::t($langfile, $key.'_'.$name),$name); ?>
		    				<p><?php echo CHtml::PasswordField($name, $value, $html_options); ?></p>
		    				<?php if(!empty($help)):?><div class="help"><?php echo $help; ?></div> <?php endif;?> 
		    			</div>
		    			<?php 
		    		break;
		    		
		    		
		    		case 'textarea':
		    			?>
		    			<div class="row">
		    				<?php echo CHtml::label(Yii::t($langfile, $key.'_'.$name),$name); ?>
		    				<p><?php echo CHtml::textArea($name, $value, $html_options); ?></p>
		    				<?php if(!empty($help)):?><div class="help"><?php echo $help; ?></div> <?php endif;?> 
		    			</div>
		    			<?php 
		    		break;
		    		
					case 'dropdown':
						$items = (isset($input['items'])) ? $input['items'] : array();
						$data = (isset($input['data'])) ? $input['data'] : array();	
					
						$id_field = isset($input['id_field']) ? $input['id_field'] : 'id';
						$name_field = isset($input['name_field']) ? $input['name_field'] : 'name';
						$items =CHtml::listData($items, $id_field,  $name_field);
						
						?>
						<div class="row">
							<?php echo CHtml::label(Yii::t($langfile, $key.'_'.$name),$name); ?>
							<p><?php echo CHtml::dropDownList($name,
						    $value,
						    (empty($data)) ? $items : $data,
						    $html_options); ?></p>
						    <?php if(!empty($help)):?><div class="help"><?php echo $help; ?></div> <?php endif;?> 
						</div>				
					<?php 
					break;
					
					case 'fieldset':
						$items = (isset($input['items'])) ? $input['items'] : array();
							?>		
								<fieldset>
										<legend>
										<?php echo CHtml::label(Yii::t($langfile, $key.'_'.$name),$name); ?>
										</legend>
										<?php getcontrols($key, $items, $settings_obj); ?>
								</fieldset>
						
							<?php 
					break;
					
					case 'listbox':
						?>
						<div class="row">
						<?php echo CHtml::label(Yii::t($langfile, $key.'_'.$name),$name); ?>
							<p>
							<?php 
								$items = (isset($input['items'])) ? $input['items'] : array();
								echo CHtml::listBox($name, $value ,$items ,$html_options);
							?>
							</p>
							<?php if(!empty($help)):?><div class="help"><?php echo $help; ?></div> <?php endif;?> 
						</div>
						<?php 
					break;
					
						case 'checkboxes':
						?>
						<div class="row">
							<div id="<?php echo $name;?>">
								<?php echo CHtml::label(Yii::t($langfile, $key.'_'.$name),$name); ?>
									<p>
									<?php 
										$items = (isset($input['items'])) ? $input['items'] : array();
										echo CHtml::checkBoxList($name, $value ,$items ,$html_options);
									?>
									</p>
									<?php if(!empty($help)):?><div class="help"><?php echo $help; ?></div> <?php endif;?> 
								</div>
							</div>	
								<?php 
					break;
					
					
		    	}
		    	
		    	endforeach;
	}	    	
}
?>