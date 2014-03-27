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
         word-wrap:break-word; 
         table-layout: fixed;
         width:650px; 
         max-width: 650px;
         border-spacing:0px; 
            /*border-collapse:collapse;*/   
    }
    
</style>
<table border="0" style="width:650px;">
    <tbody>
        <tr>
            <td rowspan="3" style="width:100px; border: 1px;"><div style="background-color:#eee;border: 1px solid #888; width: 100%; height: 100px;"></div></td>
            <td colspan="6"><?php echo $companyName;?></td>
            <td colspan="2"> Playback Report By Media </td>
        </tr>
        <tr>
            <td> Media </td>
            <td colspan="7"><?php echo $mediaName;?></td>
        </tr>
        <tr>
            <td> Period </td>
            <td> From : <?php echo $fromDate;?>  </td>
            <td> &nbsp; </td>
            <td> To : <?php echo $toDate;?></td>
            <td colspan="4"> &nbsp;  </td>
        </tr>
    </tbody>
</table>

<hr/>
<table border="0" style="width:650px;">
    <tbody>
        <tr>
            <td colspan="2"> Summary </td>
        </tr>
        <tr>
            <td style="width: 100px;">Total Player</td>
            <td><?php echo $countPlayer;?></td>
        </tr>
        <tr>
            <td>Total Duration</td>
            <td><?php echo $sumDurationMedia;?></td>
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
                <th style="width:10%;">
                    PlayerGroup
                </th>
                <th style="width:10%;">
                    Player
                </th>
                <th style="width:10%;text-align: center;">
                    Start Time
                </th>
                <th style="width:10%;text-align: center;">
                    Stop Time
                </th>
                <th style="width:5%;text-align: center;">
                    Duration
                </th>
                <th style="width:20%;">
                    Playlist
                </th>
                <th style="width:15%;">
                    Zone
                </th>
                <th style="width:20%;">
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
                    <td> <?php echo $value->tmn_grp_name ;?></td>
                    <td> <?php echo $value->tmn_name ;?></td>
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


