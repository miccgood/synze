<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
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

    <body class="">

        <div class="synzecontainer">
            <div class="header"><div class="logo"><a href="#"><img src="<?php echo base_url() ?>theme/images/logo.png" width="169" height="55" style="display:block;" /></a> </div>
                <div class="userinfo right">
                    <div class="userimage"><img src="<?php echo base_url() ?>theme/images/vgi_logo.jpg" class="img-logo"></div>
                    <div class="usermanage">
                        <div class="username">VGI Global Media</div>
                        <div class="logout"><a href="<?php echo site_url("login/logout");?>">Logout</a></div>
                    </div>
                </div>
            </div>
            <div class="mainleft">
                <div class="sidebar1">
                    <br />
<!--                    <div style="margin: auto;padding-left: 20%;margin-bottom: 10px;" id="mode"> 
                        <input type="button" id="advanceModeButton" value="Advance"><input type="button" id="liteModeButton" value="Lite">
                    </div>
                    
                    <script>
                        $(function() {
                          $( "#mode input[type=button]" )
                            .button()
                            .click(function( event ) {
                              event.preventDefault();
                            });
                        });
                    </script>-->
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
                        
                        <?php 
                            $ci = &get_instance();                
                            $mode = $ci->getMode();
                            
                            //$mode = "A";//Advance Mode
                            //$mode = "L";//Lite Mode
                            if($mode == "A"){
                        ?>
                        <li><a href="<?php echo site_url(); ?>/story"><img src="<?php echo base_url() ?>theme/images/story.png" width="36" height="30">Story</a></li>
                        <li><a href="<?php echo site_url(); ?>/layout"><img src="<?php echo base_url() ?>theme/images/layout.png" width="36" height="30">Layout</a></li>
                        <?php 
                            }
                        ?>
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
                    <?php echo $output; ?>
                </div>
            </div>
        </div>
    </body>
</html>

