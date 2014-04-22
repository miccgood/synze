<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
<script src="http://localhost/synzef/assets/grocery_crud/js/jquery-1.10.2.min.js"></script>
<style type='text/css'>
body
{
	font-family: Arial;
	font-size: 14px;
}
a {
    color: blue;
    text-decoration: none;
    font-size: 14px;
}
a:hover
{
	text-decoration: underline;
}
</style>

<script type="text/javascript">
    $(function(){
        $("#send").click(function(e){
            var url = "<?php echo base_url("index.php/programmer/ajax/5") ; ?>";

//            $.post(url, "null" , function(data, textStatus) {
//                alert(textStatus);
//            }, "json").fail(function() {
//                alert("Fail");
//            });
            $.ajax({
                url:url,
                type:"POST",
                data:null,
                contentType:"application/json; charset=utf-8",
                dataType:"json",
                success: function(msg){
                    alert( "Data Saved: " + msg );
                }
//            $.ajax({
//                type: "json",
//                url: url,
//                data: { name: "John", location: "Boston" }
//            })
//            .done(function( msg ) {
//                alert( "Data Saved: " + msg );
//            }).fail(function() {
//                alert("Fail");

                //ถ้าเชื่อต่อไม่ได้แปลว่า session timeout
            //        window.location.replace("<?php echo base_url("index.php/login") ; ?>");
                //alert( "Network Error" );
            }).fail(function() {
                alert("Fail");
            });

        });
    });
   
    
</script>
</head>
<body>
	<div style='height:20px;' id="result"> <?php echo $result;?></div>  
        <div>
            <input type="button" id="send" value="send"/> 
        </div>
</body>
</html>
