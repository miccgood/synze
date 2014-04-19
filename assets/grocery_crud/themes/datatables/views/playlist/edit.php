<?php

	$this->set_css($this->default_theme_path.'/datatables/css/datatables.css');
	$this->set_js_lib($this->default_theme_path.'/flexigrid/js/jquery.form.js');
	$this->set_js_config($this->default_theme_path.'/datatables/js/datatables-edit.js');
	$this->set_css($this->default_css_path.'/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS);
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS);

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
        $this->set_js($this->default_javascript_path.'/jquery_plugins/ui.multiselect.min.custom.js');
?>
<script type="text/javascript">
            <?php echo $this->customScript;?>
        </script>
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
		<?php
			$counter = 0;
			foreach($fields as $field)
			{
				$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
				$counter++;
		?>
			<div class='form-field-box <?php echo $even_odd?>' id="<?php echo $field->field_name; ?>_field_box">
				<div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
					<?php echo $input_fields[$field->field_name]->display_as?><?php echo ($input_fields[$field->field_name]->required)? " <span class='required'>*</span> " : ""?> :
				</div>
				<div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
					<?php echo $input_fields[$field->field_name]->input?>
				</div>
				<div class='clear'></div>
			</div>
		<?php }?>
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
				<input  id="form-button-clone" type='button' value='Clone' class='ui-input-button' />
			</div>
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
	var message_update_error = "<?php echo $this->l('update_error')?>";
</script>

<?php include 'scriptPlaylist.php';?>



<script type="text/javascript">
    $(function(){
        $("#form-button-clone").unbind().bind("click", function(){
            if(!validate()){
//                alert("validate Error");
                return false;;
            }
            var url = "<?php echo base_url('index.php/playlist/cloning'); ?>";
            var dataToBeSent = new Array();
             
            dataToBeSent["playlist"] = getPlaylistFromPage();
            dataToBeSent["media"] = $("#field-Media").val();
//            dataToBeSent["storyItem"] = getDataFromTable();
//            var data = { name: "John", location: "Boston" };

            dataToBeSent = arrayToObject(dataToBeSent);
            
            $("#FormLoading").show();
            $.post(url, dataToBeSent, function(data, textStatus) {
               console.log( "success" );
              }, "json")
            .done(function(data) {
                
                form_success_message("<p>Your data has been successfully Clone. into the database. <a href='<?php echo base_url("index.php/playlist/index/edit");?>/" + data.pl_ID + " '>Edit Playlist</a> or <a href='<?php echo base_url("index.php/playlist");?>'>Go back to list</a></p>") ;
//                $("#report-success").fadeIn(1000).delay(3000).fadeOut(1000);
//                location.reload();
            })
            .fail(function(data) {
                form_error_message(data);
//                $("#report-error").fadeIn(1000).delay(3000).fadeOut(1000);
            })
            .always(function() {
                $("#FormLoading").hide();
                console.log( "complete" );
            });
        });
    });
    
    function validate(){
        
        
        var storyNameId = "#storyName";
        var selectLayoutId = "#selectLayout";
        var message = "";
        var ret = true;
        $(storyNameId + ', ' + selectLayoutId).removeClass('field_error');
        
        var $storyName = $(storyNameId).val();
        
        if($storyName === null || $storyName === ""){
//            $("#storyName").focus().css({'border-color': "red"});
            
            $(storyNameId).addClass('field_error');
            message += "<p>The Story Name field is required.</p>";
            ret = false;
        }
        var $selectLayout = $(selectLayoutId).val();
        
        if($selectLayout === null || $selectLayout === "" || $selectLayout === "0"){
            $(selectLayoutId).addClass('field_error');//$("#selectLayout").focus().css('border-color', "red");
            message += "<p>The Layout field is required.</p>";
            ret = false;
        }
        
        if(!ret){
            form_error_message(message);
        }
        
        return ret;
    }
    
    function getPlaylistFromPage(){
        var $playlist = new Array();
        $playlist["pl_name"] = $("#field-pl_name").val();
        $playlist["pl_desc"] = $("#field-pl_desc").val();
        $playlist["pl_lenght"] = $("#field-pl_lenght").val();
        $playlist["pl_usage"] = $("#field-pl_usage").val();
        $playlist["pl_type"] = $("#field-pl_type").val();
        $playlist["pl_expired"] = $("#field-pl_expired").val();
        return arrayToObject($playlist);
    }
    
//    function arrayToObject($ret){
//        var jObject={};
//        for(var i = 0 ; i < $ret.length ; i++)
//        {
//            jObject[i] = $ret[i];
//        }
//        return  jObject;
//    }
    
    function arrayToObject($ret){
        var jObject={};
        for(var i in $ret)
        {
            jObject[i] = $ret[i];
        }
        return  jObject;
    }
</script>

