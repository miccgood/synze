<style>
    #progressbar .ui-progressbar-value {
        background-color: #ccc;
    }
    .ui-progressbar {
    position: relative;
  }
  .progress-label {
    position: absolute;
    left: 35%;
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
        
        var $lenght = $("#field-pl_lenght").val();
        var percen = (parseFloat($progressbarValue) / parseFloat($lenght) * 100).toFixed(2);
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
   });
   
</script>
