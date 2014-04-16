<style>
    #progressbar .ui-progressbar-value {
        background-color: #ccc;
    }
    .ui-progressbar {
    position: relative;
  }
  .progress-label {
    position: absolute;
    left: 29%;
    top: 4px;
    font-weight: bold;
    text-shadow: 1px 1px 0 #fff;
  }
  
</style>

<script type="text/javascript">
    
    
   
   
   $(function(){
       $("#field-pl_type").change(function(){
           if($(this).val() != ""){
               $("#selectGroup").change();
               $("#Media_input_box").find("div.selected").find("ul").find("li").not("ui-helper-hidden-accessible").remove();
               $("#field-Media :selected").prop({selected:false});
               
           }
        });
        $("#selectGroup").change();
//        $("#progressbar").prop({readonly: true});
        
        var $progressbarValue = $("#field-pl_usage").val();
        $(".progress-label").html("");
        $("#progressbar").progressbar({
            value: 0
        }).css({width: "300px"}).show();
        
        
//        var $string = $("#field-pl_lenght").val();
//        var $arr = $string.split(":");
//        var $lenght = $arr[0] * 3600 + $arr[1] * 60 + $arr[2]; 
    
    
        var $lenght = $("#field-pl_lenght").val();
        var percen = (parseFloat($progressbarValue) / parseFloat($lenght) * 100).toFixed(2);
        if($lenght == ""){
            $lenght = 0;
            percen = 0;
        }else{
            
        }
        
        var int = percen / $progressbarValue;
        var pGress = setInterval(function() {
            var pVal = $('#progressbar').progressbar('option', 'value');
            var pCnt = !isNaN(pVal) ? (pVal + 1) : 1;
            if (pCnt > percen) {
                clearInterval(pGress);
            } else {
//                var pCnt = !isNaN(pVal) ? (pVal - 1) : 1;
//                        var $lenght = $("#field-pl_lenght").val();
//                        var percen = (parseFloat(pCnt) / parseFloat($lenght) * 100).toFixed(2);
//                        if (pCnt < countUsage) {
//                            clearInterval(gLoop);
//                        } else {
//                            $('#progressbar').progressbar({value: percen});
//                            $(".progress-label").html(pCnt + ":" + $lenght + " (" + percen + "%)");
//                        }
                
                $('#progressbar').progressbar({value: pCnt});
                $(".progress-label").html( (pCnt/int).toFixed(0) + ":" + $lenght + " (" + pCnt + "%)");
            }
        },10);
    
        $("#crudForm").bind("submit", function(e){
            $('#field-media_temp').val("");
            var $li = $("#Media_input_box div .selected").find("li").not(".ui-helper-hidden-accessible");
            $.each($li, function(index, value){
                var $optionLink = $li.eq(index).data("optionLink") ;
                if($optionLink){
                    var $value = $optionLink.val();
                    $("#field-media_temp").val($('#field-media_temp').val() + ',' + $value);
                }
            });
        });
        
        $("#field-pl_lenght").bind("blur", function(e){
            var min = $(this).val();
            if(min == ""){
                min = 0;
            }
            var $string = getFormatTime(timeToString(min));
            $(this).val($string);
            
//            var $arr = $string.split(":");
//            var $lenght = parseInt($arr[0]) * 3600 + parseInt($arr[1]) * 60 + parseInt($arr[2]); 
                
            var countUsage = $("#field-pl_usage").val();
            var $lenght = timeToString($("#field-pl_lenght").val());
            var percen = ((countUsage / $lenght) * 100).toFixed(2);
            if(parseInt(countUsage) == 0){
                percen = 0;
            } else if($lenght == "0"){
                percen = 100;
            }
            $('#progressbar').progressbar({value: parseInt(percen)});
            $(".progress-label").html( getFormatTime(countUsage) + " / " + getFormatTime($lenght) + " (" + percen + "%)");
//            $("#field-pl_usage").val(countUsage);
                
        }).blur();
        
        

   });
   
    function nullToZero($value){
        return (typeof $value === "undefined" || $value === null || $value < 0) ? 0 : $value;
    }
    
    function getFormatTime (sec) { 
        var sec_num = parseInt(nullToZero(sec), 10); // don't forget the second param
        var hours   = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
        var seconds = sec_num - (hours * 3600) - (minutes * 60);

        if (hours   < 10) {hours   = "0"+hours;}
        if (minutes < 10) {minutes = "0"+minutes;}
        if (seconds < 10) {seconds = "0"+seconds;}
        var time    = hours+':'+minutes+':'+seconds;
        return time;
    }
    
    function getFormatTimeByMinute (min) { 
        return getFormatTime(parseInt(min) * 60);
    }
    
    function timeToString ($string) { 
        if(typeof $string !== "string"){
            return $string;
        }
        var $arr = $string.split(":");
        
        if($arr.length == 3){
            return parseInt($arr[0]) * 3600 + parseInt($arr[1]) * 60 + parseInt($arr[2]); 
        } else if($arr.length == 2){
            return parseInt($arr[1]) * 60 + parseInt($arr[2]);
        } else if($arr.length == 1){
            return parseInt($arr);
        }
    }
    
    function timeToStringByMinute ($string) { 
        if(typeof $string !== "string"){
            return $string;
        }
        var $arr = $string.split(":");
        
        if($arr.length == 3){
            return parseInt($arr[0]) * 60 + parseInt($arr[1]) + parseInt($arr[2]) / 60; 
        } else if($arr.length == 2){
            return parseInt($arr[1]) + parseInt($arr[2]) / 60;
        } else if($arr.length == 1){
            return parseInt($arr);
        }
    }
    
</script>
