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
            
            ).insertBefore( $("#cpn_ID_field_box").find("div.clear"));
        });
        
        
        function callbackAfterEdit(){
//          alert();  
        //$('#field-media_type').change().prop('disabled', true).trigger('liszt:updated');

            return false;
        }
        
        function openWinAddGroup()
        {
            var w = 750;
            var h = 240;
            var left = (screen.width/2)-(w/2);
            var top = (screen.height/2)-(h/2);
            var options = "toolbar=no, scrollbars=yes, resizable=no, top="+top+", left="+left+", width="+w+", height=" + h;
                var newwindow = window.open("<?php echo base_url("index.php/company/index/add") ; ?>","_blank",options);
                newwindow.focus();
//              return false;
        }

//function popupwindow(url, title, w, h) {
//
//  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
//} 


        function deleteGroup()
        {
            var cpnId = $("#field-cpn_ID").val();
            if(cpnId === ""){
                alert("Pleace Select Company To Delete");
                return ;
            }else{
                var delete_url = "<?php echo base_url("index.php/company/index/delete") ; ?>";
                delete_url += "/" + cpnId;
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

                                        $("#field-cpn_ID :selected").remove()
                                        $("#field-cpn_ID").trigger('liszt:updated');
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
            
            $("#field-cpn_ID").append("<option value='" + id + "'> " + name + " </option>").trigger('liszt:updated'); 

        }
        
    </script>
    
    