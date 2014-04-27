<?php
//            $counter = 0;
//            foreach ($fields as $field) {
//                $even_odd = $counter % 2 == 0 ? 'odd' : 'even';
//                $counter++;
                ?>



<!--ต้นแบบ-->
<!--                <div class='form-field-box <?php echo $even_odd ?>' id="<?php echo $field->field_name; ?>_field_box">
                    <div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
    <?php echo $input_fields[$field->field_name]->display_as ?><?php echo ($input_fields[$field->field_name]->required) ? "<span class='required' style=\"color: red;\"> * </span> " : "" ?> :
                    </div>
                    <div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
    <?php echo $input_fields[$field->field_name]->input ?>
                    </div>
                    <div class='clear'></div>
                </div>-->
<?php 
    $ci = &get_instance();
    $state = $ci->crud->getState();

?>

    
    <?php $field = $fields[3]; //type?>

                <div class='form-field-box <?php echo ($state == "edit" ? "even" : "odd"); ?>' id="<?php echo $field->field_name; ?>_field_box">
                    <div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
    <?php echo $input_fields[$field->field_name]->display_as ?><?php echo ($input_fields[$field->field_name]->required) ? "<span class='required' style=\"color: red;\"> * </span> " : "" ?> :
                    </div>
                    <div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
    <?php echo $input_fields[$field->field_name]->input ?>
                    </div>
                    <div class='clear'></div>
                </div>
    
    
    

    
<?php $field = $fields[0]; //File Name?>

                <div class='form-field-box even' id="<?php echo $field->field_name; ?>_field_box">
                    <div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box" style="">
    <?php echo $input_fields[$field->field_name]->display_as ?><?php echo ($input_fields[$field->field_name]->required) ? "<span class='required' style=\"color: red;\"> * </span> " : "" ?> :
                    </div>
                    <div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
    <?php echo $input_fields[$field->field_name]->input ?>
                    </div>
                    <div class='clear'></div>
                </div>

<?php if($state != "edit"){ ?>    
    <?php $field = $fields[13]; //File Name Temp?>

    <div class='form-field-box even' id="<?php echo $field->field_name; ?>_field_box" style="display: none;">
                    <div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box" style="">
    <?php echo $input_fields[$field->field_name]->display_as ?><?php echo ($input_fields[$field->field_name]->required) ? "<span class='required' style=\"color: red;\"> * </span> " : "" ?> :
                    </div>
                    <div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
    <?php echo $input_fields[$field->field_name]->input ?>
                    </div>
                    <div class='clear'></div>
                </div>
    
    
<?php } ?>  
    
    
<?php $field = $fields[1]; //Name?>

                <div class='form-field-box odd' id="<?php echo $field->field_name; ?>_field_box">
                    <div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
    <?php echo $input_fields[$field->field_name]->display_as ?><?php echo ($input_fields[$field->field_name]->required) ? "<span class='required' style=\"color: red;\"> * </span> " : "" ?> :
                    </div>
                    <div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
    <?php echo $input_fields[$field->field_name]->input ?>
                    </div>
                    <div class='clear'></div>
                </div>
    
    
<?php $field = $fields[2]; //desc?>

                <div class='form-field-box even' id="<?php echo $field->field_name; ?>_field_box">
                    <div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
    <?php echo $input_fields[$field->field_name]->display_as ?><?php echo ($input_fields[$field->field_name]->required) ? "<span class='required' style=\"color: red;\"> * </span> " : "" ?> :
                    </div>
                    <div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
    <?php echo $input_fields[$field->field_name]->input ?>
                    </div>
                    <div class='clear'></div>
                </div>
    

    <?php $field = $fields[7]; //cat_ID?>

                <div class='form-field-box odd' id="<?php echo $field->field_name; ?>_field_box">
                    <div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box" style="">
    <?php echo $input_fields[$field->field_name]->display_as ?><?php echo ($input_fields[$field->field_name]->required) ? "<span class='required' style=\"color: red;\"> * </span> " : "" ?> :
                    </div>
                    <div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
    <?php echo $input_fields[$field->field_name]->input ?>
                    </div>
                    
                    <span class="datatables-add-button" style="position: relative;" onclick="openWinAddGroup()">
            <a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="/" onclick="return false;" >
                    <span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span>
                    <span class="ui-button-text">Add</span>
            </a>
        </span>
        
        <span class="datatables-add-button" style="position: relative;" onclick="deleteGroup()" >
            <a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="/" onclick="return false;">
                    <span class="ui-button-icon-primary ui-icon ui-icon-circle-minus"></span>
                    <span class="ui-button-text">Delete</span>
            </a>
        </span>
                    
                    <div class='clear'></div>
                </div>
<!--     <?php $field = $fields[10]; //checksum?>

                <div class='form-field-box even' id="<?php echo $field->field_name; ?>_field_box">
                    <div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box" style="">
    <?php echo $input_fields[$field->field_name]->display_as ?><?php echo ($input_fields[$field->field_name]->required) ? "<span class='required' style=\"color: red;\"> * </span> " : "" ?> :
                    </div>
                    <div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
    <?php echo $input_fields[$field->field_name]->input ?>
                    </div>
                    <div class='clear'></div>
                </div>-->
    


<?php $fieldSize = $fields[4]; //size?>
                <div class='form-field-box even' id="<?php echo $fieldSize->field_name; ?>_field_box">
                    <div class='form-display-as-box' id="<?php echo $fieldSize->field_name; ?>_display_as_box">
    <?php echo $input_fields[$fieldSize->field_name]->display_as ?><?php echo ($input_fields[$fieldSize->field_name]->required) ? "<span class='required' style=\"color: red;\"> * </span> " : "" ?> :
                    </div>
                    <div class='form-input-box' id="<?php echo $fieldSize->field_name; ?>_input_box">
    <?php echo $input_fields[$fieldSize->field_name]->input ?> 
                    </div>
                    <div class='clear'></div>
                  </div>  
    
    
    
   <?php $fieldLenght = $fields[5]; //lenght?> 
                <div class='form-field-box odd' id="<?php echo $fieldSize->field_name; ?>_field_box">
                    <div class='form-display-as-box' id="<?php echo $fieldLenght->field_name; ?>_display_as_box">
    <?php echo $input_fields[$fieldLenght->field_name]->display_as ?><?php echo ($input_fields[$fieldLenght->field_name]->required) ? "<span class='required' style=\"color: red;\"> * </span> " : "" ?> :
                    </div>
                    <div class='form-input-box' id="<?php echo $fieldLenght->field_name; ?>_input_box">
    <?php echo $input_fields[$fieldLenght->field_name]->input ?> 
                    </div>
                    <div class='clear'></div>
                    
                    <script>
                        
                        $(function(){
                            
                            
                            $("#field-media_type").width("90px").trigger('liszt:updated');
                            $("#field-media_filename_temp, #field-media_name, #field-media_desc, #field-media_checksum, #field-media_filename_temp").width("300px");
                            $("#field-media_size, #field-media_lenght").width("90px");
//                            $("#form_input").slideDown( "slow");
                        });
                    </script>
                    
                </div>



<?php $field = $fields[5]; //lenght?>
<!--                <div class='form-field-box <?php echo $even_odd ?>' id="<?php echo $field->field_name; ?>_field_box">
                    <div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
    <?php echo $input_fields[$field->field_name]->display_as ?><?php echo ($input_fields[$field->field_name]->required) ? "<span class='required' style=\"color: red;\"> * </span> " : "" ?> :
                    </div>
                    <div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
                            
                    </div>
                    <div class='clear'></div>
                </div>-->



<?php $field = $fields[6]; //Media expire?>
                <div class='form-field-box even' id="<?php echo $field->field_name; ?>_field_box">
                    <div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
    <?php echo $input_fields[$field->field_name]->display_as ?><?php echo ($input_fields[$field->field_name]->required) ? "<span class='required' style=\"color: red;\"> * </span> " : "" ?> :
                    </div>
                    <div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
    <?php echo $input_fields[$field->field_name]->input ?>
                    </div>
                    <div class='clear'></div>
                </div>

    
<?php $field = $fields[12]; //Media expire?>
            <div class='form-field-box odd' id="<?php echo $field->field_name; ?>_field_box" style="display: none;">
                <div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
<?php echo $input_fields[$field->field_name]->display_as ?><?php echo ($input_fields[$field->field_name]->required) ? "<span class='required' style=\"color: red;\"> * </span> " : "" ?> :
                </div>
                <div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
<?php echo $input_fields[$field->field_name]->input ?>
                </div>
                <div class='clear'></div>
            </div>



<?php // } ?>

    
    <script type="text/javascript">
        $(function(){
//            $(".form-content, .form-div").slideDown(2000);
            $("#text_input_field_box").hide();
            $("#media_filename_temp_field_box").hide();
            
            $("#field-media_type").bind("change", function(e){
                var $type = $(e.target).val()
                if($type === "scrolling text"){
                    $("#text_input_field_box, #media_filename_temp_field_box").show();
                    $("#media_filename_field_box").hide();
                    $("#field-media_size").prop({readonly : true});
                }else{
                    $("#text_input_field_box, #media_filename_temp_field_box").hide();
                    $("#media_filename_field_box").show();
                }
                
                $("#field-media_type_temp").val($type);
                
            }).change(); 
            
            $("#crudForm").bind("submit", function(){
                $('#field-media_type').change().prop('disabled', false).trigger('liszt:updated');
            });
            
            $("#field-media_filename_temp").keyup(function(e){
                $("[name=media_filename]").val($(e.target).val());
            });
            
            $("#field-media_size").change(function(e){
                $("#size").html($(this).val());
            });
            
            
        });
        
        
        function openWinAddGroup()
        {
            var options = "toolbar=no, scrollbars=yes, resizable=yes, top=200, left=500, width=500, height=240";
                var newwindow = window.open("<?php echo base_url("index.php/group/index/add") ; ?>","_blank",options);
                newwindow.focus();
//              return false;
        }


        function deleteGroup()
        {
            var catId = $("#field-cat_id").val();
            if(catId === ""){
                alert("Pleace Select Group To Delete");
                return ;
            }else{
                var delete_url = "<?php echo base_url("index.php/group/index/delete") ; ?>";
                delete_url += "/" + catId;
                 var $delete_success_message = 'Your data has been successfully deleted from the database.';
                var $delete_error_message = 'Can’t be delete, the entity is being used.';
               
                $.ajax({
			url: delete_url,
			dataType: 'json',
			success: function(data)
			{
				if(data.success)
				{
                                    success_message($delete_success_message);
//					$("#field-cat_id").append("<option value='" + id + "'> " + name + " </option>").trigger('liszt:updated'); 

                                        $("#field-cat_id :selected").remove()
                                        $("#field-cat_id").trigger('liszt:updated');
				}
				else
				{
					error_message($delete_error_message);
				}
			}
		});
                
            }
            
        }
        
        function refreshGroup(id, name){
//            
            
            $("#field-cat_id").append("<option value='" + id + "'> " + name + " </option>").trigger('liszt:updated'); 

        }
        
    </script>