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
    <script type="text/javascript">
        $(function(){
//            $(".form-content, .form-div").slideDown(2000);
            $("#text_input_field_box").hide();
            $("#media_filename_temp_field_box").hide();
            
            $("#field-media_type").bind("change", function(e){
                var $type = $(e.target).val()
                if($type === "scrolling text"){
//                    $("#text_input_field_box, #media_filename_temp_field_box").show();

                    $("#text_input_field_box").show();
                    $("#media_filename_field_box").hide();
                    $("#field-media_size").prop({readonly : true}).val("");
                    $("#field-media_lenght").val("");
                    $("#media_type_field_box").removeClass("odd").addClass("even");
                }else if($type === "Web Page" || $type === "RSS feed" || $type === "Streaming"){
                    
//                    $("#text_input_field_box, #media_filename_temp_field_box").show();
                    $("#text_input_field_box").show();
                    $("#media_filename_field_box").hide();
                    $("#field-media_size").prop({readonly : true}).val("0");
                    $("#field-media_lenght").prop({readonly : true}).val("-1");
                    $("#media_type_field_box").removeClass("odd").addClass("even");
                    
                } else {
                    $("#text_input_field_box, #media_filename_temp_field_box").hide();
                    $("#media_filename_field_box").show();
                    $("#media_type_field_box").removeClass("even").addClass("odd");
                    $("#field-media_size").val("");
                    $("#field-media_lenght").val("");
                }
                
                $("#field-media_type_temp").val($type);
                
            }).change(); 
            
            $("#crudForm").bind("submit", function(){
                $('#field-media_type').change().prop('disabled', false).trigger('liszt:updated');
                
                if($('#field-media_type').val() === "scrolling text"){
                    if($('field-media_filename_temp').val() == ""){
                        $('field-media_filename_temp').val("gen");
                    }
                    if($("input[name=media_filename]").val() == ""){
                        $("input[name=media_filename]").val("gen");
                    }
                    
                }
            });
            
            $("#field-media_filename_temp").keyup(function(e){
                $("[name=media_filename]").val($(e.target).val());
            });
            
            $("#field-media_size").change(function(e){
                $("#size").html($(this).val());
            });
            
            $('<span class="datatables-add-button" style="position: relative;" onclick="openWinAddGroup()">'+
                    '<a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="/" onclick="return false;" >'+
                        '<span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span>'+
                        '<span class="ui-button-text">Add</span>'+
                    '</a>'+
                '</span>'+
            '<span class="datatables-add-button" style="position: relative;" onclick="deleteGroup()" >'+
                '<a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="/" onclick="return false;">'+
                '   <span class="ui-button-icon-primary ui-icon ui-icon-circle-minus"></span>'+
                '    <span class="ui-button-text">Delete</span>'+
                '</a>'+
            '</span>    '
            
            ).insertBefore( $("#cat_id_field_box").find("div.clear"));

            
        });
        
        function callbackAfterEdit(){
            $('#field-media_type').change().prop('disabled', true).trigger('liszt:updated');

            return false;
        }
        
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
        
                        
        $(function(){


            $("#field-media_type").width("90px").trigger('liszt:updated');
            $("#field-media_filename_temp, #field-media_name, #field-media_desc, #field-media_checksum, #field-media_filename_temp").width("300px");
            $("#field-media_size, #field-media_lenght").width("90px");
//                            $("#form_input").slideDown( "slow");
        });
    </script>
    
    
<style type="text/css">

    #textPicker, #bgPicker {
        margin:0;
        padding:0;
        border:0;
        width:80px;
        height:20px;
        border-right:20px solid black;
        line-height:25px;
    }
</style>
<script type="text/javascript">
	
        $(function(){
            $('#transparency').spinner({
                min: 0, 
                max: 99,
                change: function( event, ui ) {
                          $("#spinner").spinner("stepUp");
                          $("#spinner").spinner("stepDown");
                          alert($("#spinner").spinner("value"));
                      }
            });

            $('#textPicker, #bgPicker').colpick({
                layout:'hex',
                submit:0,
                colorScheme:'light',
                onChange:function(hsb,hex,rgb,el,bySetColor) {
                        $(el).css('border-color','#'+hex);
                        // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
                        if(!bySetColor) $(el).val("#" + hex);
                }
            }).keyup(function(){
//                var $value = this.value;
//                
//                if($value === "" || $value === null){
//                    $value = "#000000";
//                }
//                
//                $(this).colpickSetColor($value);
//                $(this).val($value);
                $(this).change();
            }).change(function(){
                var $value = this.value;
                
                if($value === "" || $value === null){
                    $value = "#000000";
                }
                
                $(this).colpickSetColor($value);
                $(this).val($value);
            });
            //#000000 => black
            $("#textSize").css({"min-width" : "55px", width : "75px", height : "30px"});
            $("#playSpeed").css({"min-width" : "55px", width : "75px", height : "30px"});
            $("#direction").css({"min-width" : "55px", width : "75px", height : "30px"});
            $("#text_input_input_box").css({"border-color": "#cdcdcd", 
             "border-weight":"1px", 
             "border-style":"solid",
             "padding" : "15px", 
             "border-radius" : "10px"});
         
             $('#textPicker, #bgPicker').change();
        });
    
</script>