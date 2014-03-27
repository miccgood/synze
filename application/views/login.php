<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to SpotOn</title>

	<style type="text/css">

	/*::selection{ background-color: #E13300; color: white; }*/
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	.form {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
                text-align: center;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 50px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
        input {
            font-family: sans-serif;                
        }
        
        .require{
            color: red;
        }
        
        .hide{
            display: none;
        }
	</style>
        
        
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.9.1.js"></script>
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <link rel="stylesheet" href="/resources/demos/style.css">
        <script>
        $(function() {
            $( "input[type=submit], input[type=reset]" ).button();
            $( "#form" ).submit(function( event ) {
                $(".hide").hide().find("span").html("");
                $("input[name=user], input[name=pass]").each(function(){
                    var $this = $(this);
                    if($this.val() === ""){
                        var $error = $("#"+$this.attr("name"));
                        $error.show().find("span").html("This Field is Require");
                        $this.focus();
                        event.preventDefault();
                        return;
                    }
                });
                
                
            });
            
            $("[name=user]").focus();
            
        });
        </script>
  
</head>
<body>
    <form id="form" action="<?php echo  site_url("login/check");?>" method="post">
        <div id="container" style="text-align: center;">
            <h1>Login</h1>

            <div id="body" style="display: block;" class="form">
                <table align="center">
                    <tr>
                        <td>
                            Username  
                        </td>
                        <td>
                            <input type="text" name="user" maxlength="255"/> <span class="require">*</span>
                        </td>
                    </tr>
                    <tr id="user" class="hide">
                        <td colspan="2">
                            <span class="require"></span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Password 
                        </td>
                        <td>
                            <input type="password" name="pass" maxlength="255"/> <span class="require">*</span>
                        </td>
                    </tr>
                    
                    <tr id="pass" class="hide">
                        <td colspan="2">
                            <span class="require"></span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td align="center" colspan="2">
                            <h3 style="color:red;"><?php echo $error;?> </h3>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2">
                            <input type="submit" name="login" value="Login" />
                            <input type="reset" name="reset" value="Reset"/>
                        </td>
                    </tr>
                </table>
            </div>

            <p class="footer">&nbsp;</p>
        </div>
    </form>
</body>
</html>