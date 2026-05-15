<?php
include('../include/connect.php');
include("../include/functions.php");
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Its A Popup</title> 
    <link rel="stylesheet" href="dist/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="is-boxed has-animations" >
    <div class="body-wrap boxed-container" style="background: #fe3e4f;">
        
<div style="    height: 51px;
    background: #2c2c2c;
    text-align: center;
    display: inline-grid;
    color: white;
    text-shadow: 2px 1px 8px #000;
    font-size: 25px;
    font-weight: bold;padding: 10px;
">
    <?php echo base64_decode(get_SettingValue('popup_on_home_title'));?>
</div>
<div style="height:100%">
<div style="text-align: center;text-shadow: 2px 2px 10px black;
font-size: 25px;
font-weight: bold;
background: #fe3e4f;
color: white;
padding: 10px 30px;"> 

<i class="fa fa-bullhorn" style="font-size: 80px;
color: yellow;
display: block;
padding: 22px;
text-shadow: 2px 2px 10px black;"></i>


<?php echo base64_decode(get_SettingValue('popup_on_home_desc'));?> </div>

<br>
</div>


    </div>
<div style="position: absolute;
width: 50px;
height: 50px;
top: 0px;
right: 0px;
background: white;
border-radius: 0px 0px 0px 15px;"> &nbsp;</div>
    <script src="dist/js/main.min.js"></script>
    
    
    
    <script>
    function close_popup(){
        app.close_popup();
        return false;
    }
    
    </script>
    
    
</body>
</html>
