<script type="text/javascript">
    
    
   
   
   function callbackAfterAdd(data){
       var name = $("#field-tmn_grp_name").val();
       window.opener.refreshGroup(data.insert_primary_key, name);
   }
   
   $(function(){
       $("#field-tmn_grp_name").css({width: "300px"});
   });
   
</script>
