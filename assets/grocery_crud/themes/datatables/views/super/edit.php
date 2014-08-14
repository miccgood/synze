<?php

	$this->set_css($this->default_theme_path.'/datatables/css/datatables.css');
	$this->set_js_lib($this->default_theme_path.'/flexigrid/js/jquery.form.js');
	$this->set_js_config($this->default_theme_path.'/datatables/js/datatables-edit.js');
	$this->set_css($this->default_css_path.'/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS);
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS);

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
?>
<div class='ui-widget-content ui-corner-all datatables'>
	<h3 class="ui-accordion-header ui-helper-reset ui-state-default form-title">
		<div class='floatL form-title-left'>
			<a href="#"><?php echo $this->l('form_edit'); ?> <?php echo $subject?></a>
		</div>
		<div class='clear'></div>
	</h3>
<div class='form-content form-div'>
	<?php echo form_open( $update_url, 'method="post" id="crudForm" autocomplete="off" enctype="multipart/form-data"'); ?>
		<div>
                        <?php include 'detail.php';?>
			<!-- Start of hidden inputs -->
				<?php
					foreach($hidden_fields as $hidden_field){
						echo $hidden_field->input;
					}
				?>
			<!-- End of hidden inputs -->
			<?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>
			<div class='line-1px'></div>
			<div id='report-error' class='report-div error'></div>
			<div id='report-success' class='report-div success'></div>
		</div>
		<div class='buttons-box'>
			<div class='form-button-box'>
				<input  id="form-button-save" type='submit' value='<?php echo $this->l('form_update_changes'); ?>' class='ui-input-button' />
			</div>
			<?php 	if(!$this->unset_back_to_list) { ?>
			<div class='form-button-box'>
				<input type='button' value='<?php echo $this->l('form_update_and_go_back'); ?>' class='ui-input-button' id="save-and-go-back-button"/>
			</div>
			<div class='form-button-box'>
				<input type='button' value='<?php echo $this->l('form_cancel'); ?>' class='ui-input-button' id="cancel-button" />
			</div>
			<?php }?>
			<div class='form-button-box loading-box'>
				<div class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
			</div>
			<div class='clear'></div>
		</div>
	</form>
</div>
</div>
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
//	var message_update_error = "<?php echo $this->l('update_error')?>";
        
        var message_update_error = "You don't have permissions for this operation";
        
//        $(function(){
//            $('<span class="datatables-add-button" style="position: relative;" onclick="openWinAddGroup()">'+
//                    '<a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="/" onclick="return false;" >'+
//                        '<span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span>'+
//                        '<span class="ui-button-text">Add</span>'+
//                    '</a>'+
//                '</span>'+
//            '<span class="datatables-add-button" style="position: relative;" onclick="deleteGroup()" >'+
//                '<a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="/" onclick="return false;">'+
//                '   <span class="ui-button-icon-primary ui-icon ui-icon-circle-minus"></span>'+
//                '    <span class="ui-button-text">Delete</span>'+
//                '</a>'+
//            '</span>    '
//            
//            ).insertBefore( $("#cpn_ID_field_box").find("div.clear"));
//        });
//        
//        
//        function callbackAfterEdit(){
////          alert();  
//        //$('#field-media_type').change().prop('disabled', true).trigger('liszt:updated');
//
//            return false;
//        }
//        
//        function openWinAddGroup()
//        {
//            var w = 750;
//            var h = 240;
//            var left = (screen.width/2)-(w/2);
//            var top = (screen.height/2)-(h/2);
//            var options = "toolbar=no, scrollbars=yes, resizable=no, top="+top+", left="+left+", width="+w+", height=" + h;
//                var newwindow = window.open("<?php echo base_url("index.php/company/index/add") ; ?>","_blank",options);
//                newwindow.focus();
////              return false;
//        }
//
////function popupwindow(url, title, w, h) {
////
////  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
////} 
//
//
//        function deleteGroup()
//        {
//            var cpnId = $("#field-cpn_ID").val();
//            if(cpnId === ""){
//                alert("Pleace Select Company To Delete");
//                return ;
//            }else{
//                var delete_url = "<?php echo base_url("index.php/company/index/delete") ; ?>";
//                delete_url += "/" + cpnId;
//                 var $delete_success_message = 'Your data has been successfully deleted from the database.';
//                var $delete_error_message = 'Can’t be delete, the entity is being used.';
//               
//                $.ajax({
//			url: delete_url,
//			dataType: 'json',
//			success: function(data)
//			{
//				if(data.success)
//				{
//                                    success_message($delete_success_message);
////					$("#field-cat_id").append("<option value='" + id + "'> " + name + " </option>").trigger('liszt:updated'); 
//
//                                        $("#field-cpn_ID :selected").remove()
//                                        $("#field-cpn_ID").trigger('liszt:updated');
//				}
//				else
//				{
//					error_message($delete_error_message);
//				}
//			}
//		});
//                
//            }
//            
//        }
//        
//        function refreshGroup(id, name){
////            
//            
//            $("#field-cpn_ID").append("<option value='" + id + "'> " + name + " </option>").trigger('liszt:updated'); 
//
//        }
        
               
</script>