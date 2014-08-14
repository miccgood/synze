<script type="text/javascript">
    
    
   
   
   function callbackAfterAdd(data){
       var name = $("#field-cpn_name").val();
       window.opener.refreshGroup(data.insert_primary_key, name);
   }
   
   $(function(){
       $("#field-cat_name").css({width: "300px"});
   });
   
</script>
