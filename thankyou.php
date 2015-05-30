<?php
  
  session_start();

  if ($_SESSION['facebook']!='') {
  
    $facebook_data = json_decode($_SESSION['facebook']);
  
  }

?>
<html>
    <head>
        <title>ReNotifier Demo - Standard Form</title>
        <meta charset="UTF-8">        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>   
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
        
        <div class="row">
        
        <div class="col-xs-12">

        <div class="text-center"><a href="https://renotifier.com"><img src="https://renotifier.com/static/img/logo_blue_160px.png" style="width:160px; margin-top:15px;"></a></div>

        <? if ($_SESSION['facebook']=="") { ?>
        <h1 class="text-center">Thank you!</h1>
        </div>
        </div>

        <div class="row">
        <div class="col-md-4 col-md-offset-4">
        <br><br>
        
        <p class="text-center">You didn't authorise and connect to our Demo Facebook app, but we still captured the following information from your manual input (i.e. what you manually entered into the form)</p>
        
        <strong>Email address:</strong> <?=$_SESSION['email']?><br>
        <strong>Favorite color:</strong> <?=$_SESSION['color']?><br>
        </div>
        </div>

        <? } else { ?>

        <h1 class="text-center">Thank you <?=$facebook_data->name?>!</h1>
        </div>
        </div>

        <div class="row">
        <div class="col-md-3 col-md-offset-4">
        <br><br>
        
        <p class="text-center">Woo-hoo <?=$facebook_data->first_name?>! You authorised and connected to our Demo Facebook app! This means:</p>
        </div>

        <div class="col-md-1">
        <br>
        <img src="https://graph.facebook.com/<?=$facebook_data->id?>/picture?type=normal" class="img-circle">
        </div>
        </div>

        <div class="row">
        <div class="col-md-4 col-md-offset-4">
        <br>
        <p>1. We captured the following information from your manual input:</p>
        <strong>Email address:</strong> <?=$_SESSION['email']?><br>
        <strong>Favorite color:</strong> <?=$_SESSION['color']?><br>
        <br>
        <p>2. We also captured the following information from your Facebook profile:</p>
        <strong>First name:</strong> <?=$facebook_data->first_name?><br>
        <strong>Last name:</strong> <?=$facebook_data->last_name?><br>
        <strong>Full name:</strong> <?=$facebook_data->name?><br>
        <strong>Email:</strong> <?=$facebook_data->email?><br>
        <strong>Gender:</strong> <?=$facebook_data->gender?><br>
        <strong>Facebook Id:</strong> <?=$facebook_data->id?><br>
        <strong>Profile link:</strong> <a href="<?=$facebook_data->link?>" target="_BLANK">Click here!</a><br>
        <strong>Locale:</strong> <?=$facebook_data->locale?><br>
        <strong>Timezone (offset to GMT in hours):</strong> <?=$facebook_data->timezone?><br>
        <strong>Last time when profile was updated:</strong> <?=$facebook_data->updated_time?><br>
        <br>
        <p>3) We added you to ReNotifier and already sent you an Autoresponder notification :) <a href="https://www.facebook.com">Check your Facebook >></a></p>

<p>P.S.: When using a Smart Form like this one, our data shows you collect two different email addresses from 25% of users who use your Smart Form! 25% bigger email list!</p>

<p>P.S.2: Facebook Notification have up to 90% open rates (i.e. user opens and see the message) and up to 50% click rates (i.e. user clicks the message and is redirected to your Destination URL. Compare that to Email (OR:20%, CR:5%).</p>

<p>P.S.3: Accept Rate for Facebook Login Dialogs is 60% to up to 90%, depending on how good your value proposition is. This means that out of every 100 people who see Facebook Login Dialog, 60 to 90 of them will authorise and connect to your Facebook app!</p>


        </div>
        </div>


        <? } ?>
        <br><br>

    </body>
</html>
