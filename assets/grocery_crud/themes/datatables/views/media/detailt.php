<?php
			$counter = 0;
				foreach($fields as $field)
				{
					$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
					$counter++;
			?>
			<div class='form-field-box <?php echo $even_odd?>' id="<?php echo $field->field_name; ?>_field_box">
				<div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
					<?php echo $input_fields[$field->field_name]->display_as?><?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""?> :
				</div>
				<div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
					<?php echo $input_fields[$field->field_name]->input?>
				</div>
				<div class='clear'></div>
			</div>
			<?php }?>


<script>
                        
                        $(function(){
                            
                            
                            $("#field-media_type").width("90px").trigger('liszt:updated');
                            $("#field-media_filename_temp, #field-media_name, #field-media_desc, #field-media_checksum, #field-media_filename_temp").width("300px");
                            $("#field-media_size, #field-media_lenght").width("90px");
//                            $("#form_input").slideDown( "slow");
                        });
                    </script>
                    
                    
                    
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
            
            
            
            
        });
        
        
        function openWinAddGroup()
        {
            var options = "toolbar=no, scrollbars=yes, resizable=yes, top=500, left=500, width=550, height=240";
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
                var $delete_error_message = 'Your data was not deleted from the database.';
               
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