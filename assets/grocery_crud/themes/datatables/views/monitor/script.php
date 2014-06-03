<?php

$ci = &get_instance();                
$setInterval = $ci->setInterval;

?>

<script type="text/javascript">
    
    var $_countFail = 0;
    var $_countSuccess = 0;
    $(function(){
        setInterval(function(){refreshPage();}, <?php echo $setInterval ; ?> * 1000);
//refreshPage();
    });
    
    
    function refreshPage(){
        var url = "monitor/ajaxRefresh";
            var dataToBeSent = {"monitorIds" : getDataFromTable()};
             
            if($.isEmptyObject(dataToBeSent.monitorIds ) ) return;
                console.clear();
            $.post(url, dataToBeSent, function(data, textStatus) {
//               console.log(data);
            }, "json")
            .done(function(data) {
                $_countSuccess++;
//                console.log( "done" );
//                console.log("<------------>");
//                        
//                console.log("Fail : " + $_countFail);
//                console.log("Success : " + $_countSuccess);
//
//                console.log("<------------>");
                
                $("#" + $_tableId + " tbody").find("tr").each(function(e2){
//                    var $id = $(this).data("id");
                    var $id = $(this).attr("tmn_ID");
                    if($id !== null && $id !== "" && $id !== 0  && $id !== "0"  && typeof $id !== "undefined"){
                        var value = data[$id];
                        if(typeof value === "undefined" || value === null ){
                            return;
                        }
                        var $playerStatus = $(this).find("td").eq(2);
                        var $uploadStatus = $(this).find("td").eq(3);
                        var $incedentPerDay = $(this).find("td").eq(4);
                        var $incedentPerMonth = $(this).find("td").eq(5);
                        
//                        console.log("tmn_ID : " + $id);
//                        console.log("tmn_monitor : " + value.tmn_monitor);
//                        console.log("tmn_status_id : " + value.tmn_status_id);
//                        console.log("tmn_status_message : " + value.tmn_status_message);
//                        console.log("tmn_status_update : " + value.tmn_status_update);
//                        console.log("tmn_status_upload_id : " + value.tmn_status_upload_id);
//                        console.log("tmn_status_upload_message : " + value.tmn_status_upload_message);
//                        console.log("tmn_status_upload_update : " + value.tmn_status_upload_update);
//                        console.log("incedent_per_day : " + value.incedent_per_day);
//                        console.log("incedent_per_month : " + value.incedent_per_month);
                        
                        $incedentPerDay.html(value.incedent_per_day);
                        $incedentPerMonth.html(value.incedent_per_month);
                         
                        var $statusMessage = value.tmn_status_message;
                        var $statusUploadMessage = value.tmn_status_upload_message;

                        $playerStatus.html($statusMessage);
                        $uploadStatus.html($statusUploadMessage);
                    }            
                });
            })
            .fail(function(data) {
                console.log( "fail" );
                $_countFail++;
//                form_error_message(data);
//                $("#report-error").fadeIn(1000).delay(3000).fadeOut(1000);
            })
            .always(function() {
//                $("#FormLoading").hide();
                console.log( "always" );
            });
    }
    
    function getDataFromTable(){
        var $ret = new Array();
//        ค้นหา tr 
        $("#" + $_tableId + " tbody").find("tr").each(function(e2){
//            var $id = $(this).data("id");
            var $id = $(this).attr("tmn_ID"); 
            if($id !== null && $id !== "" && $id !== 0  && $id !== "0"  && typeof $id !== "undefined"){
                $ret[$ret.length] = $id;
            }            
        });
        return arrayToObject($ret);
    }    
    
    function arrayToObject($ret){
        var jObject={};
        for(var i = 0 ; i < $ret.length ; i++)
        {
            jObject[i] = $ret[i];
        }
        return  jObject;
    }
    
    
    
</script>

