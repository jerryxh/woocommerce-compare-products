<?php
class WOO_Compare_Class{	
	public static $default_types = array(
									'input-text' => 'Input Text',
									'text-area' => 'Text Area', 
									'checkbox' => 'CheckBox', 
									'radio' => 'Radio button', 
									'drop-down' => 'Drop Down', 
									'multi-select' => 'Multi Select');
	
	function woocp_set_setting_default($reset=false){
		$comparable_settings = get_option('woo_comparable_settings');
		if(!is_array($comparable_settings) || count($comparable_settings) < 1){
			$comparable_settings = array();	
		}
		
		if(!isset($comparable_settings['popup_width']) || trim($comparable_settings['popup_width']) == '' || $reset){
			$comparable_settings['popup_width'] = 1000;
		}
		if(!isset($comparable_settings['popup_height']) || trim($comparable_settings['popup_height']) == '' || $reset){
			$comparable_settings['popup_height'] = 650;
		}
		if(!isset($comparable_settings['compare_container_height']) || trim($comparable_settings['compare_container_height']) == '' || $reset){
			$comparable_settings['compare_container_height'] = 500;
		}
		if(!isset($comparable_settings['auto_add']) || trim($comparable_settings['auto_add']) == '' || $reset){
			$comparable_settings['auto_add'] = 'yes';
		}
		if(!isset($comparable_settings['button_text']) || trim($comparable_settings['button_text']) == '' || $reset){
			$comparable_settings['button_text'] = 'Compare This*';
		}
		if(!isset($comparable_settings['compare_featured_tab']) || trim($comparable_settings['compare_featured_tab']) == '' || $reset){
			$comparable_settings['compare_featured_tab'] = 'Technical Details';
		}
		if(!isset($comparable_settings['auto_compare_featured_tab']) || trim($comparable_settings['auto_compare_featured_tab']) == '' || $reset){
			$comparable_settings['auto_compare_featured_tab'] = '29';
		}
		if(!isset($comparable_settings['button_type']) || trim($comparable_settings['button_type']) == '' || $reset){
			$comparable_settings['button_type'] = 'button';
		}
		update_option('woo_comparable_settings', $comparable_settings);
	}
	
	function woo_compare_manager(){
		global $wpdb;	
		$result_msg = '';	
		$comparable_setting_msg = '';
		
		if(isset($_REQUEST['bt_save_settings'])){
			$comparable_settings = get_option('woo_comparable_settings');
			if(!isset($_REQUEST['auto_add'])) $comparable_settings['auto_add'] = 'no';
			$comparable_settings = array_merge((array)$comparable_settings, $_REQUEST);
			update_option('woo_comparable_settings', $comparable_settings);
			$comparable_setting_msg = '<div class="updated" id="comparable_settings_msg"><p>'.__('Save Comparable Settings successfully','woo_cp').'.</p></div>
						<script type="text/javascript">
						jQuery(document).ready(function($) {
						jQuery("#comparable_settings_msg").fadeOut(20000);
						});	
						</script>';
		}elseif(isset($_REQUEST['bt_reset_settings'])){
			WOO_Compare_Class::woocp_set_setting_default(true);
			$comparable_setting_msg = '<div class="updated" id="comparable_settings_msg"><p>'.__('Reset Comparable Settings successfully','woo_cp').'.</p></div>
						<script type="text/javascript">
						jQuery(document).ready(function($) {
						jQuery("#comparable_settings_msg").fadeOut(20000);
						});	
						</script>';
		}

		if(isset($_REQUEST['bt_save_field'])){
			if(isset($_REQUEST['field_id']) && $_REQUEST['field_id'] > 0){
				if(trim($_REQUEST['field_key']) == '' || WOO_Compare_Data::check_field_key_for_update($_REQUEST['field_id'], $_REQUEST['field_key'])){
					$result = WOO_Compare_Data::update_row($_REQUEST);
					$result_msg = '<div class="updated" id="result_msg"><p>'.__('Edit Compare field successfully','woo_cp').'.</p></div>
						<script type="text/javascript">
						jQuery(document).ready(function($) {
						jQuery("#result_msg").fadeOut(20000);
						});	
						</script>';
				}else{
					$result_msg = '<div class="error" id="result_msg"><p>'.__('This Field Key is existed, please enter other Field Key','woo_cp').'.</p></div>
						<script type="text/javascript">
						jQuery(document).ready(function($) {
						jQuery("#result_msg").fadeOut(20000);
						});	
						</script>';
				}
			}else{
				if(trim($_REQUEST['field_key']) == '' || WOO_Compare_Data::check_field_key($_REQUEST['field_key'])){
					$result = WOO_Compare_Data::insert_row($_REQUEST);	
					if($result > 0){
						$result_msg = '<div class="updated" id="result_msg"><p>'.__('Create Compare field successfully','woo_cp').'.</p></div>
						<script type="text/javascript">
						jQuery(document).ready(function($) {
						jQuery("#result_msg").fadeOut(20000);
						});	
						</script>';
					}else{
						$result_msg = '<div class="error" id="result_msg"><p>'.__('Create Compare field error','woo_cp').'.</p></div>
						<script type="text/javascript">
						jQuery(document).ready(function($) {
						jQuery("#result_msg").fadeOut(20000);
						});	
						</script>';
					}
				}else{
					$result_msg = '<div class="error" id="result_msg"><p>'.__('This Field Key is existed, please enter other Field Key','woo_cp').'.</p></div>
						<script type="text/javascript">
						jQuery(document).ready(function($) {
						jQuery("#result_msg").fadeOut(20000);
						});	
						</script>';
				}
			}
		}
		
		if(isset($_REQUEST['act']) && $_REQUEST['act'] == 'delete'){
			$field_id = trim($_REQUEST['field_id']);
			WOO_Compare_Data::delete_row($field_id);
			$result_msg = '<div class="updated" id="result_msg"><p>'.__('Delete Compare field successfully','woo_cp').'.</p></div>
					<script type="text/javascript">
					jQuery(document).ready(function($) {
					jQuery("#result_msg").fadeOut(20000);
					});	
					</script>';
		}
		?>
        <div class="wrap">
        <h2></h2>
        <div class="updated" style="padding:10px"><?php _e('You are using WOO Compare Product FREE VERSION. Upgrade to the','woo_cp'); ?> <a href="http://a3rev.com/products-page/wooecommerce/woo-compare-products/" target="_blank">PRO VERSION</a> <?php _e('to create unlimited Compare Feature Category Sets.','woo_cp'); ?></div>
        <script type="text/javascript">
			(function($){
				$(function(){
					$("#field_type").change( function() {
						var field_type = $(this).val();
						if(field_type == 'checkbox' || field_type == 'radio' || field_type == 'drop-down' || field_type == 'multi-select'){
							$("#field_value").show();	
						}
					});
				});
			})(jQuery);
		</script>
        <div id="icon-themes" class="icon32">&nbsp;</div><h2><?php if(isset($_REQUEST['act']) && $_REQUEST['act'] == 'edit'){ _e('COMPARE FEATURE CATEGORY FIELDS','woo_cp'); }else{ _e('COMPARE FEATURE CATEGORY FIELDS','woo_cp'); } ?></h2>
        <p><?php _e('Upgrade to the PRO VERSION to be able to create unlimited number of Compare Feature Category Sets.','woo_cp'); ?></p>
        <div style="clear:both;height:12px;"></div>
        <?php echo $result_msg; ?>
        <form action="admin.php?page=woo-comparable-settings" method="post" name="form_add_compare" id="form_add_compare">
        <?php
			if(isset($_REQUEST['act']) && $_REQUEST['act'] == 'edit'){
				$field_id = $_REQUEST['field_id'];
				$field = WOO_Compare_Data::get_row($field_id);
			}
			$have_value = false;
		?>
        <?php
			if(isset($_REQUEST['act']) && $_REQUEST['act'] == 'edit'){
		?>
        	<input type="hidden" value="<?php echo $field_id; ?>" name="field_id" id="field_id" />
        <?php		
			}
		?>
        	<table cellspacing="0" class="widefat post fixed">
            	<thead>
                	<tr><th class="manage-column" scope="col"><?php if(isset($_REQUEST['act']) && $_REQUEST['act'] == 'edit'){ _e('Edit Compare Feature Field','woo_cp'); }else{ _e('Create New Compare Feature Field','woo_cp'); } ?></th></tr>
                </thead>
                <tbody>
                	<tr>
                    	<td>
                        	<div style="width:200px; float:left"><label for="field_name"><?php _e('Field Name','woo_cp'); ?></label></div> <input type="text" name="field_name" id="field_name" value="<?php echo stripslashes($field->field_name); ?>" style="width:400px" />
                            <div style="clear:both"></div>
                            <div style="width:200px; float:left"><label for="field_key"><?php _e('Field Key','woo_cp'); ?></label></div> <input type="text" name="field_key" id="field_key" value="<?php echo stripslashes($field->field_key); ?>" style="width:400px" /><?php _e('Please do not enter space character.','woo_cp'); ?>
                            <div style="clear:both"></div>
                            <div style="width:200px; float:left"><label for="field_type"><?php _e('Field Type','woo_cp'); ?></label></div> 
                            <select style="width:400px;" name="field_type" id="field_type">
                            <?php
								foreach(WOO_Compare_Class::$default_types as $type => $type_name){
									if($type == $field->field_type){
										echo '<option value="'.$type.'" selected="selected">'.$type_name.'</option>';	
									}else{
										echo '<option value="'.$type.'">'.$type_name.'</option>';
									}
								}
								if(in_array($field->field_type, array('checkbox' , 'radio', 'drop-down', 'multi-select'))){
									$have_value = true;	
								}
							?>
                            </select>
                            <div style="clear:both"></div>
                            <div id="field_value" <?php if(!$have_value){ echo 'style="display:none"';} ?>>
                                <div style="width:200px; float:left"><label for="default_value"><?php _e('Field Value','woo_cp'); ?></label><br /><?php _e('Each option in a line','woo_cp'); ?></div> <textarea style="width:400px;height:100px" name="default_value" id="default_value"><?php echo stripslashes($field->default_value); ?></textarea>
                                <div style="clear:both"></div>
                            </div>
                        	<div style="width:200px; float:left"><label for="field_unit"><?php _e('Field Unit','woo_cp'); ?></label></div> <input type="text" name="field_unit" id="field_unit" value="<?php echo stripslashes($field->field_unit); ?>" style="width:400px" />
                            <div style="clear:both"></div>
                            <div style="width:200px; float:left"><label for="field_description"><?php _e('Field Description','woo_cp'); ?></label></div> <textarea style="width:400px;height:100px" name="field_description" id="field_description"><?php echo stripslashes($field->field_description); ?></textarea>
                            <div style="clear:both"></div>
                    	</td>
                    </tr>
                    <tr>
                    	<td><input type="submit" name="bt_save_field" id="bt_save_field" class="button-primary" value="<?php if(isset($_REQUEST['act']) && $_REQUEST['act'] == 'edit'){ _e('Save','woo_cp'); }else{ _e('Create','woo_cp'); } ?>"  /> <?php if($_REQUEST['act'] == 'edit'){ ?><a href="admin.php?page=woo-comparable-settings"><input type="button" name="cancel" value="<?php _e('Cancel','woo_cp'); ?>" class="button-primary" /></a><?php } ?></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <div style="clear:both; margin-bottom:20px;"></div>
        <?php if($_REQUEST['act'] != 'edit'){ ?>
		<div id="icon-themes" class="icon32"><br></div><h2><?php _e('WOO Compare Feature Fields Order','woo_cp'); ?></h2>
        <style>
				.update_order_message{color:#06C; margin:5px 0;}
				ul.compare_orders{float:left; margin:0; }
				ul.compare_orders li{padding-top:8px; border-top:1px solid #DFDFDF; margin:5px 0; line-height:20px;}
				ul.compare_orders .c_field_name{width:275px; float:left; padding-left:20px; background:url(<?php echo WOOCP_IMAGES_FOLDER; ?>/icon_sort.png) no-repeat 0 center;}
				ul.compare_orders .c_field_type{width:95px; float:left;}
				ul.compare_orders .c_field_edit{background:url(<?php echo WOOCP_IMAGES_FOLDER; ?>/icon_edit.png) no-repeat 0 0; width:16px; height:16px; display:inline-block;}
				ul.compare_orders .c_field_delete{background:url(<?php echo WOOCP_IMAGES_FOLDER; ?>/icon_del.png) no-repeat 0 0; width:16px; height:16px; display:inline-block;}
		</style> 
        <form action="" method="post" name="form_compare_order" id="form_compare_order">      
          <div class="update_order_message">&nbsp;</div>
  		  <table cellspacing="0" class="widefat post fixed" style="width:536px">
            <thead>
            <tr>
              <th width="45" class="manage-column" scope="col"><?php _e('Order','woo_cp'); ?></th>
              <th width="280" class="manage-column" scope="col"><?php _e('Field Name','woo_cp'); ?></th>
              <th width="80" class="manage-column" scope="col"><?php _e('Type','woo_cp'); ?></th>
              <th width="75" class="manage-column" scope="col"><?php _e('Action','woo_cp'); ?></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
              <th class="manage-column" scope="col"><?php _e('Order','woo_cp'); ?></th>
              <th class="manage-column" scope="col"><?php _e('Field Name','woo_cp'); ?></th>
              <th class="manage-column" scope="col"><?php _e('Type','woo_cp'); ?></th>
              <th class="manage-column" scope="col"><?php _e('Action','woo_cp'); ?></th>
            </tr>
            </tfoot>          
            <tbody>
            	<tr>
				  <td class="tags column-tags" colspan="4">
               	<?php 
				  	$compare_fields = WOO_Compare_Data::get_results('','field_order ASC');
					if(is_array($compare_fields) && count($compare_fields)>0){
				?>
                <?php wp_enqueue_script('jquery-ui-sortable'); ?>
                <script type="text/javascript">
					(function($){
						$(function(){
							$("#compare_orders").sortable({ placeholder: "ui-state-highlight", opacity: 0.6, cursor: 'move', update: function() {
								var order = $(this).sortable("serialize") + '&action=update_orders';
								$.post("<?php echo WOOCP_URL; ?>/compare_process_ajax.php", order, function(theResponse){
									$(".update_order_message").html(theResponse);
								});
							}
							});
						});
					})(jQuery);
				</script>
                <?php
					echo '<ul class="compare_orders" style="width:60px;">';
					for($i=1; $i<=count($compare_fields);$i++){
						echo '<li>'.$i.'</li>';
					}
					echo '</ul>';
				?>
                	<ul class="compare_orders" id="compare_orders" style="width:460px;">
                <?php
					foreach($compare_fields as $field_data){
				?>
                		<li id="recordsArray_<?php echo $field_data->id; ?>"><div class="c_field_name"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $field_data->field_name; ?></div><div class="c_field_type"><?php echo WOO_Compare_Class::$default_types[$field_data->field_type]; ?></div><div class="c_field_action"><a href="admin.php?page=woo-comparable-settings&act=edit&field_id=<?php echo $field_data->id; ?>" class="c_field_edit">&nbsp;</a> | <a href="admin.php?page=woo-comparable-settings&act=delete&field_id=<?php echo $field_data->id; ?>" class="c_field_delete">&nbsp;</a></div></li>
                <?php
					}
				?>
                    </ul>	
                <?php
					}else{
						_e('Do not have the compare field','woo_cp');
					}
				?>
                  </td>
				</tr>
            </tbody>          
          </table>
       	</form>
        <div style="clear:both; margin-bottom:20px;"></div>
        <?php } ?>     
        <div id="icon-themes" class="icon32"><br></div><h2><?php _e('WOO Comparable Settings','woo_cp'); ?></h2>
        <?php $comparable_settings = get_option('woo_comparable_settings'); ?>
        <?php echo $comparable_setting_msg; ?>
        <div style="clear:both;height:12px;"></div>
        <form action="admin.php?page=woo-comparable-settings" method="post" name="form_comparable_settings" id="form_comparable_settings">      
  		  <table cellspacing="0" class="widefat post fixed">
            	<thead>
                	<tr><th class="manage-column" scope="col"><?php _e('Comparable Settings','woo_cp'); ?></th></tr>
                </thead>
                <tbody>
                	<tr>
                    	<td>
                        	<div style="width:200px; float:left"><label for="compare_logo"><?php _e('Compare Popup Logo URL','woo_cp'); ?></label></div> <input type="text" name="compare_logo" id="compare_logo" value="<?php echo $comparable_settings['compare_logo'] ?>" style="width:400px" />
                            <div style="clear:both; height:20px;"></div>
                            <div style="width:200px; float:left"><label for="popup_width"><?php _e('Compare Popup Width','woo_cp'); ?></label></div> <input type="text" name="popup_width" id="popup_width" value="<?php echo $comparable_settings['popup_width'] ?>" style="width:100px" />px
                            <div style="clear:both; height:20px;"></div>
                            <div style="width:200px; float:left"><label for="popup_height"><?php _e('Compare Popup Height','woo_cp'); ?></label></div> <input type="text" name="popup_height" id="popup_height" value="<?php echo $comparable_settings['popup_height'] ?>" style="width:100px" />px
                            <div style="clear:both; height:20px;"></div>
                            <div style="width:200px; float:left"><label for="compare_container_height"><?php _e('Compare Container Height','woo_cp'); ?></label></div> <input type="text" name="compare_container_height" id="compare_container_height" value="<?php echo $comparable_settings['compare_container_height'] ?>" style="width:100px" />px
                            <div style="clear:both; height:20px;"></div>
                        	<div style="width:200px; float:left"><label for="auto_add1"><?php _e('Auto Add Compare button','woo_cp'); ?></label></div> <input type="radio" name="auto_add" id="auto_add1" value="yes" <?php if($comparable_settings['auto_add'] == 'yes'){ echo 'checked="checked"';} ?> /> <?php _e('Yes','woo_cp'); ?>  <br />
                            <div style="margin-left:200px;"><?php _e("This feature must be set at YES in the FREE version for Compare Products to work. You can manually deactivate the Compare Button and features from any individual products page edit screen. If you'd prefer to be just able to just activate the Plugin and then add the Compare Products Button and Features to individual Products rather than ALL Products (and then have to deactivate on individual product pages) you can do that by upgrading to the PRO version. IMORTANT! If your theme does not auto show the Campare button on each product page you will need to activate the next option and take the necessary steps.", 'woo_cp'); ?></div>
                            <div style="clear:both; height:20px;"></div>
                        	<div style="width:200px; float:left"><label for="auto_add2"><?php _e('Manually set Show Compare button and/or Button Position','woo_cp'); ?></label></div> <input type="radio" name="auto_add" id="auto_add2" value="no" <?php if($comparable_settings['auto_add'] == 'no'){ echo 'checked="checked"';} ?> /> <?php _e('Yes','woo_cp'); ?>  <br />
                            <div style="margin-left:200px;"><?php _e('If your theme does not support not auto show the compare button on product pages - or if you want to change the default position of the Button on the product page - Set this option to YES. Then use this function','woo_cp'); ?> <code>&lt;?php if(function_exists('woo_add_compare_button')) echo woo_add_compare_button(); ?&gt;</code> <?php _e('to put into your theme code to allow your theme to show the button. Set it in your theme where you want the Compare button to show on the product pages.','woo_cp'); ?></div>
                            <div style="clear:both; height:20px;"></div>
                            <div style="width:200px; float:left"><label for="button_text"><?php _e('Button Text','woo_cp'); ?></label></div> <input type="text" name="button_text" id="button_text" value="<?php echo $comparable_settings['button_text']; ?>" />
                            <div style="clear:both; height:20px;"></div>
                            <div style="width:200px; float:left"><label for="button_type"><?php _e('Compare Style','woo_cp'); ?></label></div> <input type="radio" name="button_type" value="button" <?php if($comparable_settings['button_type'] == 'button'){ echo 'checked="checked"';} ?> /> <?php _e('Button','woo_cp'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="button_type" value="link" <?php if($comparable_settings['button_type'] == 'link'){ echo 'checked="checked"';} ?> /> <?php _e('Link','woo_cp'); ?> 
                            <div style="clear:both; height:20px;"></div>
                            <div style="width:200px; float:left"><label for="compare_featured_tab"><?php _e('Compare Featured Tab','woo_cp'); ?></label></div> <input type="text" name="compare_featured_tab" id="compare_featured_tab" value="<?php echo $comparable_settings['compare_featured_tab']; ?>" />
                            <div style="clear:both; height:20px;"></div>
                            <div style="width:200px; float:left"><label for=""><?php _e('Auto Show Compare Featured fields on Product Page Tabs','woo_cp'); ?></label></div> 
                            <div style="margin-left:200px;"><input type="radio" name="auto_compare_featured_tab" value="9" <?php if($comparable_settings['auto_compare_featured_tab'] == '9'){ echo 'checked="checked"';} ?> /> <?php _e('Before Description tab','woo_cp'); ?>  <br />
                            <input type="radio" name="auto_compare_featured_tab" value="19" <?php if($comparable_settings['auto_compare_featured_tab'] == '19'){ echo 'checked="checked"';} ?> /> <?php _e('Between  Description and Additional tabs','woo_cp'); ?>  <br />
                            <input type="radio" name="auto_compare_featured_tab" value="29" <?php if($comparable_settings['auto_compare_featured_tab'] == '29'){ echo 'checked="checked"';} ?> /> <?php _e('Between  Additional and Reviews tabs','woo_cp'); ?>  <br />
                            <input type="radio" name="auto_compare_featured_tab" value="31" <?php if($comparable_settings['auto_compare_featured_tab'] == '31'){ echo 'checked="checked"';} ?> /> <?php _e('After Reviews tab','woo_cp'); ?>  <br />
                            <input type="radio" name="auto_compare_featured_tab" value="0" <?php if($comparable_settings['auto_compare_featured_tab'] == '0'){ echo 'checked="checked"';} ?> /> <?php _e('Do not auto show','woo_cp'); ?> <br /><br /></div>
                            <div style="margin-left:200px;">
                            <?php _e('You can use this function','woo_cp'); ?> <code>&lt;?php if(function_exists('woo_show_compare_fields')) echo woo_show_compare_fields(); ?&gt;</code> <?php _e('to put into your theme code where you want to show Compare Featured fields','woo_cp'); ?>
                            </div>
                            <div style="clear:both; height:20px;"></div>
                    	</td>
                    </tr>
                    <tr>
                    	<td><input type="submit" name="bt_save_settings" id="bt_save_settings" class="button-primary" value="<?php _e('Save Settings','woo_cp'); ?>"  /> <input type="submit" name="bt_reset_settings" id="bt_reset_settings" class="button-primary" value="<?php _e('Reset Settings','woo_cp'); ?>"  /></td>
                    </tr>
                </tbody>
            </table>
       	</form>
        <div style="clear:both; margin-bottom:20px;"></div>
          
    </div>  
	<?php
	}
		
}
?>