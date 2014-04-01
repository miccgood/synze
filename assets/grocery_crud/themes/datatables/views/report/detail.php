<style>
    .table-curved {
    border-collapse:separate;
    border: solid #ccc 1px;
    border-radius: 5px;
}

.table-curved tr:last-child td
{
    border-radius: 5px;
}
</style>

<div style="width: 100%; margin-bottom: 20px;">
    <table border="0"  cellpadding="10" class="table table-curved" id="nextTeamTable" style="width: 700px; margin: auto;">
        <thead data-parent="#bodyContainer" data-toggle="collapse" data-target="#tbodyCollapse">
            <tr >
                <td colspan="5">Report :   Playback report by media </td>
            </tr>
        </thead>
        <tbody >
            <tr>
                <td>Generate By </td>
                <td colspan="4">
                    <select id="genReportBy" style="width:250px;"class='chosen-select'>
                        <!--<option value="1"> Player Group </option>-->
                        <option value="1"> Player </option>
                        <option value="2"> Media </option>
                    </select>
                </td>
            </tr>

            <tr id="playerGroup">
                <td> Player Group </td>
               <td colspan="4">
                    <select id="genReportByPlayerGroup" style="width:250px;"class='chosen-select'>
                        <option value="0"> All </option>
                        <?php

                            foreach ($default_value["playerGroup"] as $key => $value) {
                                echo "<option value='$key'> $value </option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr id="player">
                <td> Player </td>
               <td colspan="4">
                    <select id="genReportByPlayer" style="width:250px;" class='chosen-select'>
                        <option value="0"> All </option>
                        <?php

                            foreach ($default_value["player"] as $key => $value) {
                                echo "<option value='$key'> $value </option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr id="media">
                <td> Media </td>
               <td colspan="4">
                   <select id="genReportByMedia" style="width:250px;" class='chosen-select'>
                        <option value="0"> All </option>
                        <?php

                            foreach ($default_value["media"] as $key => $value) {
                                echo "<option value='$key'> $value </option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle;">Period </td>
                <!--<td> from : </td>-->
                <td colspan="4">
                    from :
                    <span style="margin-right: 30px; ">
                        <input id='start_date' name='start_date' type='text' value='<?php echo date('01/m/Y'); ?>' maxlength='10' class='datepicker-input' size="8" style="height: 30px;" readonly="readonly"/>
                        <!--<a class='datepicker-input-clear' tabindex='-1' style="margin-right: 20px;">Clear</a>-->
                    </span>
                    to :
                    <span>
                        <input id='stop_date' name='stop_date' type='text' value='<?php echo date(date("t") . '/m/Y'); ?>' maxlength='10' class='datepicker-input' size="8" style="height: 30px;" readonly="readonly"/>
                        <!--<a class='datepicker-input-clear' tabindex='-1'>Clear</a>-->
                    </span>
                </td>
            </tr>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="1" > </td>
                <td colspan="4" >
                    <div class="datatables-add-button">
                    <a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="preview">
                            <span class="ui-button-icon-primary ui-icon ui-icon-zoomin"></span>
                            <span class="ui-button-text">Preview</span>
                    </a>

                    <a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="excel">
                            <span class="ui-button-icon-primary ui-icon ui-icon-document"></span>
                            <span class="ui-button-text">Excel</span>
                    </a>
                    <a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="pdf">
                            <span class="ui-button-icon-primary ui-icon ui-icon-document"></span>
                            <span class="ui-button-text">PDF</span>
                    </a>

                    </div>

                </td>
            </tr>
        </tfoot>
    </table>
</div>

<script type="text/javascript">

    var send = function ($url){
        var dataToBeSent = new Array();

        dataToBeSent = getDataFromPage();

//        dataToBeSent = arrayToString(dataToBeSent);
        $url = $url+"?"+dataToBeSent;
        window.open($url);
//        $.post($url, dataToBeSent, function(data, textStatus) {
//            alert(data);
//            window.open();
//           console.log( "success" );
//          })
//        .done(function(data) {
//            console.log( "done" );
////            form_success_message("<p>Your data has been successfully updated. <a href='<?php echo base_url("index.php/story");?>'>Go back to list</a></p>") ;
//        })
//        .fail(function(data) {
//            console.log( "fail" );
////            form_error_message(data);
//        })
//        .always(function() {
////            $("#FormLoading").hide();
//            console.log( "complete" );
//        });
    };

    var getUrl = function($event){
        return "<?php echo base_url("index.php/report") ?>" + "/" + $event;
    };

    $(function(){
        $("#preview").bind("click", function(e){
            var $url = getUrl("preview");
            send($url);
        });

        $("#excel").bind("click", function(e){
            var $url = getUrl("excel");
            send($url);
        });

        $("#pdf").bind("click", function(e){
            var $url = getUrl("pdf");
            send($url);
        });
    });


    function getDataFromPage(){
        var $ret = new Array();

        $ret["genReportBy"] = $("#genReportBy").val();
        $ret["playerGroup"] = $("#genReportByPlayerGroup").val();
        $ret["player"] =  $("#genReportByPlayer").val();
        $ret["media"] = $("#genReportByMedia").val();
        $ret["fromDate"] = $("#start_date").val();
        $ret["toDate"] = $("#stop_date").val();
        return arrayToString($ret);
    }

    function arrayToObject($ret){
        var jObject={};
        for(var i = 0 ; i < $ret.length ; i++)
        {
            jObject[i] = $ret[i];
        }
        return  jObject;
    }
//    
    function arrayToObject($ret){
        var jObject={};
        for(i in $ret)
        {
            jObject[i] = $ret[i];
        }
        return  jObject;
    }

//    function arrayToString($ret){
//        var jSring=""
//        for(var i in $ret)
//        {
//            jSring  += i + "=" + $ret[i] + "&";
//        }
//        return  jSring;
//    }

</script>
