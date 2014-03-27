<script type="text/javascript">
    $(function(){
        setInterval(function(){refreshPage()}, 3000);
//refreshPage();
    });
    
    
    function refreshPage(){
        var url = "monitor/ajaxRefresh";
            var dataToBeSent = new Array();
             
                   
            dataToBeSent["monitorIds"] = getDataFromTable();
//            var data = { name: "John", location: "Boston" };

            dataToBeSent = arrayToObject(dataToBeSent);
//            console.log(dataToBeSent);
//            $("#FormLoading").show();

                console.clear();
            $.post(url, dataToBeSent, function(data, textStatus) {
//               console.log(data);
            }, "json")
            .done(function(data) {
                console.log( "done" );
                                
                $("#" + $_tableId + " tbody").find("tr").each(function(e2){
                    var $id = $(this).data("id");
                    
                    if($id !== null && $id != ""){
                        var value = data[$id];
                        console.log("tmn_ID : " + $id);
                        console.log("tmn_monitor : " + value.tmn_monitor);
                        console.log("tmn_status_id : " + value.tmn_status_id);
                        console.log("tmn_status_message : " + value.tmn_status_message);
                        console.log("tmn_status_update : " + value.tmn_status_update);
                        console.log("tmn_status_upload_id : " + value.tmn_status_upload_id);
                        console.log("tmn_status_upload_message : " + value.tmn_status_upload_message);
                        console.log("tmn_status_upload_update : " + value.tmn_status_upload_update);
                        
                        
                        $(this).find("td").eq(2).html(getStatusMessage(value));
                        $(this).find("td").eq(3).html(getStatusUploadMessage(value));
                    }            
                });
            })
            .fail(function(data) {
                console.log( "fail" );
//                form_error_message(data);
//                $("#report-error").fadeIn(1000).delay(3000).fadeOut(1000);
            })
            .always(function() {
//                $("#FormLoading").hide();
                console.log( "always" );
            });
    }
    
    function getStatusMessage(value){
        var $id = value.tmn_status_id;
        var $message = value.tmn_status_message;
        var $active = value.tmn_monitor;

        var $color = "#cccccc";

        if($active !== null && $active === 1){
            switch ($id) {
                case 1:
                    $color = "red";
                    break;
                case 2:
                    $color = "yellow";
                    break;
                case 3:
                    $color = "green";
                    break;

                default:
                    $color = "red";
                    break;
            }
        }  


        if($message === null){
            return '<div id="border-circle" >'
                    + '<div id="circle" style="background: ' + $color +';"></div>'
                + '</div> ';
        } else {
            $message = $message.replace(",", "<br/>");
            return "<font style='color:$color;'> " + $message + " </font>";
        }
        
    }
    
    
    function getStatusUploadMessage(value){
        var $id = value.tmn_status_upload_id;
        var $message = value.tmn_status_upload_message;
        var $active = value.tmn_monitor;

        var $color = "#cccccc";

        if($active !== null && $active === 1){
            switch ($id) {
                case 1:
                    $color = "red";
                    break;
                case 2:
                    $color = "yellow";
                    break;
                case 3:
                    $color = "green";
                    break;

                default:
                    $color = "red";
                    break;
            }
        }  


        if($message === null){
            return '<div id="border-circle" >'
                    + '<div id="circle" style="background: ' + $color +';"></div>'
                + '</div> ';
        } else {
            $message = $message.replace(",", "<br/>");
            return "<font style='color:$color;'> " + $message + " </font>";
        }
        
    }
    
    
    function getDataFromTable(){
        var $ret = new Array();
//        ค้นหา tr 
        $("#" + $_tableId + " tbody").find("tr").each(function(e2){
            var $id = $(this).data("id");
            if($id !== null && $id != ""){
                $ret[$ret.length] = $id;
            }            
        });
        return arrayToObject($ret);
    }    
    
    function arrayToObject($ret){
        var jObject={};
        for(i in $ret)
        {
            jObject[i] = $ret[i];
        }
        return  jObject;
    }
    
    
    
</script>

