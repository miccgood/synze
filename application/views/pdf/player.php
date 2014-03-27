<script type="text/php">
    if ($pdf) {
      $pdf->get_cpdf()->openHere('FitV', 0);
    }
  </script>
<style>
    th{
        background-color: #eee;
    }
    th, td{
        vertical-align: top;  
    }
    th, tr, td{
        border: 1px solid black;  
        border-radius: 2px;
        padding: 2px;
    }
    hr{
         border: 0px solid black; 
         background-color: black;
         height: 0.5px;
    }
     
    .data-table{
         border: 1px solid black; 
         border-style: outset;
         table-layout: fixed;
         width:650px; 
         max-width: 650px;
         border-spacing:0px; 
         
            /*border-collapse:collapse;*/   
    }
    
    td {
        word-wrap:break-word;
    }
    
    .border-none tr td{
        border: none;
    }
    
</style>
<table border="0" style="width:650px;" class="border-none">
    <tbody>
        <tr>
            <td rowspan="4" style="width:100px; border: 0px;">
                <div style="background-color:#eee;border: 1px solid #888; width: 100%; height: 100px;"> 
                    <?php if ($companyLink != null || $companyLink != ""){?>
                        
                        <img src="<?php echo $companyLink ; ?>" width="140px" height="120px" />
                        
                    <?php }?>
                    
                </div>
            </td>
            <td colspan="6"><?php echo $companyName;?></td>
            <td colspan="2"> Playback Report By Media </td>
        </tr>
        <tr>
            <td> Player </td>
            <td colspan="7"><?php echo $tmnName;?></td>
        </tr>
        <tr>
            <td> Player Group </td>
            <td colspan="7"><?php echo $playerGroup;?></td>
        </tr>
        <tr>
            <td> Duration </td>
            <td> From : <?php echo $fromDate;?>  </td>
            <td> &nbsp; </td>
            <td> To : <?php echo $toDate;?></td>
            <td colspan="4"> &nbsp;  </td>
        </tr>
    </tbody>
</table>

<hr/>
<table border="0" style="width:650px;" class="border-none">
    <tbody>
        <tr>
            <td colspan="2"> Summary </td>
        </tr>
        <tr>
            <td style="width: 130px;">Total Player</td>
            <td><?php echo $countMedia;?></td>
        </tr>
        <tr>
            <td>Total Duration</td>
            <td><?php echo $sumDurationPlayer;?></td>
        </tr>
    </tbody>
</table>
<!--<hr noshade="noshade" align="center" style="border-color: #CFCFCF; border-style: dashed; color: #CFCFCF; height: 1px; margin-top: -5px; text-align: center;">-->

<hr style="margin-top: 10px;margin-bottom: 10px;"/>
    
<!--border: 1px solid black;-->

<table cellspacing="0" cellpadding="5" class="data-table">
        <thead>
            <tr>
                <th style="width:3%;text-align: center;">
                    ID
                </th>
                <th style="width:23%;">
                    Media
                </th>
<!--                <th style="width:10%;">
                    Player
                </th>-->
                <th style="width:10%;text-align: center;">
                    Start Time
                </th>
                <th style="width:10%;text-align: center;">
                    Stop Time
                </th>
                <th style="width:5%;text-align: center;">
                    Duration
                </th>
                <th style="width:15%;">
                    Playlist
                </th>
                <th style="width:15%;">
                    Zone
                </th>
                <th style="width:15%;">
                    Story
                </th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $count = 1;
                $tbody = "";
                $player = "";
                foreach ($data as $value) : 
                    $player = $value->pl_name;?>
                <tr style="display:table-row;">
                    <td style="text-align: center;"> <?php echo $count ;?></td>
                    <!--<td> <div style="word-wrap: break-word; width: 10px;"><?php echo $value->media_name ;?></div></td>-->
                    <td><?php echo $value->media_name ;?></td>
                    <!--<td> <?php echo $value->tmn_name ;?></td>-->
                    <td style="text-align: center;"> <?php echo $value->start_time ;?></td>
                    <td style="text-align: center;"> <?php echo $value->stop_time ;?></td>
                    <td style="text-align: center;"> 0 </td>
                    <td> <?php echo $value->pl_name ;?></td>
                    <td> <?php echo $value->dsp_name ;?></td>
                    <td> <?php  echo  $value->story_name ;?></td>
                </tr>


            <?php $count++; 
                endforeach; ?>
        </tbody>
            
        
    </table>


<!--
    <?php 
//        $count = 1;
//        $tbody = "";
//        $player = "";
//        foreach ($data as $value) {
//            $player = $value->pl_name;
//            $tbody .= "<tr>"
//                . "<td> $count </td>"
//                . "<td>$value->tmn_grp_name</td>"
//                . "<td>$value->tmn_name</td>"
//                . "<td>$value->start_time</td>"
//                . "<td>$value->stop_time</td>" 
//                . "<td> 0 </td>"
//                . "<td>$value->pl_name</td>"
//                . "<td>$value->dsp_name</td>"
//                . "<td>$value->story_name</td>"
//            . "</tr>" ;
//            $count++;
//        }
        ?>


<table border="1" style="width:650px;">
        <thead>
            <tr>
                <th>
                    ID
                </th>
                <th>
                    Player Group
                </th>
                <th>
                    Player
                </th>
                <th>
                    Start Time
                </th>
                <th>
                    Stop Time
                </th>
                <th>
                    Duration
                </th>
                <th>
                    Playlist
                </th>
                <th>
                    Zone
                </th>
                <th>
                    Story
                </th>
            </tr>
        </thead>
        <tbody>
            <?php echo $tbody; ?>
        </tbody>
            
        
    </table>-->


