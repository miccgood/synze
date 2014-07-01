<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv=Content-Type content="text/html; charset=utf-8">
        
        
        <?php foreach ($css_files as $file): ?>
            <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
        <?php endforeach; ?>
        <link href="<?php echo base_url(); ?>theme/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>theme/css/style.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>theme/css/jquery-ui-1.10.3.custom.css" type="text/css">

        <?php foreach ($js_files as $file): ?>
            <script src="<?php echo $file; ?>"></script>
        <?php endforeach; ?>
        <script src="<?php echo base_url() ?>theme/js/jquery-ui-1.10.3.custom.js"></script>



        



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
            
            .img-logo {width:61px !important;  height:61px !important;};
        </style>
    </head>
<?php 
    $ci = &get_instance();                
    $mode = $ci->getMode();

    //$mode = "A";//Advance Mode
    //$mode = "L";//Lite Mode

?>
    <body class="">

        <div class="synzecontainer">
            <div class="header"><div class="logo"><a href="#"><img src="<?php echo base_url() ?>theme/images/logo.png" width="169" height="55" style="display:block;" /></a> </div>
                <div class="userinfo right">
                    <div class="userimage"><img src="<?php echo base_url() ?>theme/images/vgi_logo.jpg" class="img-logo"></div>
                    <div class="usermanage">
                        <div class="cpnname"><?php  echo $ci->getCpnName(); ?></div>
                        <div class="username"><?php  echo $ci->getDisplayName(); ?></div>
                        <div class="logout">
                        <?php
                            if($ci->getUserPermissionAdminPage()){
                        ?>
                            <a href="<?php echo site_url("admin");?>">Admin</a> / 
                        <?php 
                            }
                        ?>  
                        <a href="<?php echo site_url("login/logout");?>">Logout</a></div>
                    </div>
                </div>
            </div>
            <div class="mainleft">
                <div class="sidebar1">
                    <br />
                    <div style="margin: auto;padding-left: 20%;margin-bottom: 10px;" id="mode"> 
                        <input type="button" id="liteModeButton" value="Lite" class="ui-button ui-widget ui-state-default ui-corner-all">
                        <input type="button" id="advanceModeButton" value="Advance" class="ui-button ui-widget ui-state-default ui-corner-all">
                    </div>
                    
                    <script>
                        $(function() {
                          $( "#advanceModeButton" )
                            .button(<?php echo ($mode == "A" ? "{ disabled: true }" : ""); ?>)
                            .click(function( event ) {
                                setMode("A");
                                $(this).button({ disabled: true });
                                $("#liteModeButton").button({ disabled: false });
                            });
                          $( "#liteModeButton" )
                            .button(<?php echo ($mode == "L" ? "{ disabled: true }" : ""); ?>)
                            .click(function( event ) {
                                setMode("L");
                                $(this).button({ disabled: true });
                                $("#advanceModeButton").button({ disabled: false });
                            });
                        });
                        
                        function setMode($mode){
                            var url = "<?php echo base_url("index.php/mode/setmode") ; ?>/"+$mode;
                            
                            $.post(url, $mode , function(data, textStatus) {
                                if($mode === "L"){
                                    $(".mode").hide(400);
                                }else{
                                    $(".mode").show(400);
                                }
                            }, "json").fail(function() {
                                //ถ้าเชื่อต่อไม่ได้แปลว่า session timeout
                                window.location.replace("<?php echo base_url("index.php/login") ; ?>");
                                //alert( "Network Error" );
                            });
                        }
                    </script>
                    
                    <ul class="nav">
                        <li><h4>Content Distribution</h4></li>
                        <div class="divider"></div>
                        <li><a href="<?php echo site_url(); ?>/scheduling"><img src="<?php echo base_url() ?>theme/images/scheduling.png" width="38" height="30">Scheduling</a></li>
                        <li><a href="<?php echo site_url(); ?>/deployment"><img src="<?php echo base_url() ?>theme/images/deployment.png" width="38" height="30">Deployment</a></li> 
                    </ul>
                    <br />

                    <ul class="nav">
                        <li><h4>Content Management</h4></li>
                        <div class="divider"></div>
                        <li></li>
                        
                        
                        <li class="mode" <?php echo ($mode == "L" ? "style='display:none;'" : ""); ?>>
                            <a href="<?php echo site_url(); ?>/story"><img src="<?php echo base_url() ?>theme/images/story.png" width="36" height="30">Story</a>
                        </li>
                        <li class="mode" <?php echo ($mode == "L" ? "style='display:none;'" : ""); ?>>
                            <a href="<?php echo site_url(); ?>/layout"><img src="<?php echo base_url() ?>theme/images/layout.png" width="36" height="30">Layout</a>
                        </li>
                        
                        <li><a href="<?php echo site_url(); ?>/playlist"><img src="<?php echo base_url() ?>theme/images/playlist.png" width="36" height="30">Playlist</a></li>
                        <li><a href="<?php echo site_url(); ?>/media"><img src="<?php echo base_url() ?>theme/images/content.png" width="36" height="30">Content</a></li>
                    </ul>
                    <br />
                    <ul class="nav">
                        <li><h4>Device Management</h4></li>
                        <div class="divider"></div>
                        <li><a href="<?php echo site_url(); ?>/terminal"><img src="<?php echo base_url() ?>theme/images/terminal-group.png" width="38" height="30">Player</a></li> 
                        <li><a href="<?php echo site_url(); ?>/monitor"><img src="<?php echo base_url() ?>theme/images/monitor_icon.png" width="38" height="30">Monitor</a></li>
                        <li><a href="<?php echo site_url(); ?>/report"><img src="<?php echo base_url() ?>theme/images/report_icon.png" width="38" height="30">Report</a></li>
                    </ul>
                </div>
            </div>
            <div class="content-outer">
                <div class="content-inner">
                    <?php 
                        $permissionView = $ci->getPermissionView();
                        if($permissionView){
                            echo $output; 
                        } else {
                            show_error("You don't have permissions for this operation", 500, "Error Permission Denied");
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
    
    <script type="text/javascript">
        
        $(function(){
            var newDate = new Date();
            var day = newDate.getDate();
            var month = newDate.getMonth() + 1;
            var year = newDate.getFullYear();

            $(".datepicker-input").not(".datepicker-not-min-date").each(function(){
                
                var date = $(this).datepicker('getDate');
                if(date !== null){
                     var diff = diffDate(date, newDate);
                
                    if(diff >= 0){
                        day = date.getDate();
                        month = date.getMonth() + 1;
                        year = date.getFullYear();
                    }
                }
               
                
                $(this).datepicker("option", "minDate", day+"/"+month+"/"+year);



//                $(this).datepicker('setDate', day+"/"+month+"/"+year);
            });
        });
        
        var diffDate = function(d1, d2) {
            var t2 = d2.getTime();
            var t1 = d1.getTime();
            return parseInt(t2-t1);
//            return parseInt((t2-t1)/(24*3600*1000));
        }; 
        
    </script>
</html>

