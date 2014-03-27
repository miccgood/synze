<script type="text/javascript">
    
      
   $(function(){
       
       
//       $( ".inner" ).before( "<p>Test</p>" );before
       $("#tmn_grp_ID_field_box").find(".clear").before(
               '<span class="datatables-add-button" style="position: relative;" onclick="openWinAddGroup()">'+
               '<a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="/" onclick="return false;" >'+
                    '<span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span>'+
                    '<span class="ui-button-text">Add</span>'+
            '</a>'+
        '</span>'+
        
        '<span class="datatables-add-button" style="position: relative;" onclick="deleteGroup()" >'+
            '<a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="/" onclick="return false;">'+
                    '<span class="ui-button-icon-primary ui-icon ui-icon-circle-minus"></span>'+
                    '<span class="ui-button-text">Delete</span>'+
            '</a>'+
        '</span>'
                
                
                
                
                );
       
       
       $("#field-cat_name, #field-tmn_name, #field-tmn_desc").css({width: "300px"});
   });
   
        function openWinAddGroup()
        {
            var options = "toolbar=no, scrollbars=yes, resizable=yes, top=500, left=500, width=550, height=240";
                var newwindow = window.open("<?php echo base_url("index.php/terminalgroup/index/add") ; ?>","_blank",options);
                newwindow.focus();
//              return false;
        }


        function deleteGroup()
        {
            var id = "#field-tmn_grp_ID";
            var tmnGrpId = $(id).val();
            if(tmnGrpId === ""){
                alert("Pleace Select Group To Delete");
                return ;
            }else{
                var delete_url = "<?php echo base_url("index.php/terminalgroup/index/delete") ; ?>";
                delete_url += "/" + tmnGrpId;
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
                                    $("#field-tmn_grp_ID :selected").remove()
                                    $("#field-tmn_grp_ID").trigger('liszt:updated');
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
            $("#field-tmn_grp_ID").append("<option value='" + id + "'> " + name + " </option>").trigger('liszt:updated'); 
        }
        
        
   
</script>
