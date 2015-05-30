<?php
  
  // in this demo we use the session to store the data
  session_start();

  // this is the API access token retrieved at https://renotifier.com/client-area/api/
  $token = 'YOUR-RENOTIFIER-TOKEN-HERE';

  $facebook_app_id = '1580859238854014'; //replace this with your facebook app id


  // Here we handle the form if it was posted!
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Just to be safe, we do a server side validation of the email!
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        // The email is valid! Here we can save the email and the color to a database
        // or even add it to an email list such as mailchimp via their API.

        // In our use case we will save it to the $_SESSION variable so
        // that we may show it to you on the thankyou page!
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['color'] = $_POST['color'];

        if ($_POST['facebook_data']!="") {
          // user has connected with Facebook. We will add him to the ReNotifier database using the API:
         $facebook_data = json_decode($_POST['facebook_data']);

         // we are going to import both the facebook ID and append a color to match it with.
         // You can find more information on the import inputs here:
         // https://renotifier.com/client-area/
         $facebook_ids = "facebook_id, color\n".$facebook_data->id.", ".$_POST['color'];

         $data = array('facebook_app_id' => $facebook_app_id, 'facebook_ids' => $facebook_ids);

         $endpoint_url = 'https://renotifier.com/api/import';
         $curl = curl_init($endpoint_url);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Authorization: Token ".$token));
         curl_setopt($curl, CURLOPT_POST, 1);
         curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);   
         $curl_response = curl_exec($curl);
         curl_close($curl);

          // We will also save all his Facebook data to the session so that we may display it in the thank you page:
          $_SESSION['facebook'] = $_POST['facebook_data'];
        } else {
          $_SESSION['facebook'] = "";
        }

        // We redirect the user to the thank you page.
        Header("Location: thankyou.php");
    }
  }

  // if the form wasn't posted we simply display the squeeze page:

?>
<html>
    <head>
        <title>ReNotifier Demo - Smart Form</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>   
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link href="./css/bootstrap-social.css" rel="stylesheet">
    </head>
    <body>
    <div id="fb-root"></div>
        <div class="container">
        
        <div class="row">
        
        <div class="col-xs-12">

        <div class="text-center"><a href="https://renotifier.com"><img src="https://renotifier.com/static/img/logo_blue_160px.png" style="width:160px; margin-top:15px;"></a></div>

        <h1 class="text-center">Capture 2 email addresses from the same user using 1 form!</h1>
        </div>
        </div>

        <div class="row">
        <div class="col-md-4 col-md-offset-4">
        <br><br>
        <p class="text-center">With a smart form you can use one single form to:
        <ol>
          <li>captrue an email address and other information the user inputs manually into the form</li>
          <li>capture information from the user's Facebook profile (i.e. email address, first name, last name, gender, etc.)</li>
          <li>get permission to send Facebook Notifications to the user using <a href="https://renotifier.com">ReNotifier.com</a></li>
        </ol></p>
        
        <br><br>
        
        <form method="POST" id="theForm">
          <div class="form-group email-group">
            <label for="exampleInputEmail1" class="control-label">Email address</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label>Your favorite color</label>
            <div class="radio">
              <label><input type="radio" name="color" value="red">Red</label>
            </div>
            <div class="radio">
              <label><input type="radio" name="color" value="green">Green</label>
            </div>
            <div class="radio">
              <label><input type="radio" name="color" value="blue">Blue</label>
            </div>    
            <div class="radio">
              <label><input type="radio" name="color" value="yellow">Yellow</label>
            </div>  
          </div>
          <input type="hidden" id="facebookdata" name="facebook_data" value="">
          <button type="button" class="btn btn-block btn-social btn-facebook" onclick="FBlogin();"><i class="fa fa-facebook"></i> Proceed with Facebook</button>     
          <div class="text-center"><a href="javascript:;" class="proceed_without"><small>Proceed without Facebook &raquo;</small></a></div>

        </form>
        </div>
        
        </div>
    <script type="text/javascript">
    // This is the facebook login code.
    window.fbAsyncInit = function() {
    FB.init({
      appId      : '<?=$facebook_app_id?>',
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });

    };

    // Load the SDK asynchronously
    (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
    }(document));

    function FBlogin() {

      if (!IsEmail($("#email").val())) {
        // Invalid email! Let's display a simple error:
        $(".email-group").removeClass("has-success").addClass("has-error");
        alert("Please enter a valid email address.");
        return false;
      }

      FB.login(function(response){
        if(response.authResponse)
          {
            // User has successfully connected with facebook! We go ahead and submit the form - before that we populate the hidden field with the user's facebook data:
            //$( "#theForm" ).submit();   
            FB.api('/me', function(response) {
                $("#facebookdata").val(JSON.stringify(response));
                $( "#theForm" ).submit(); 
            });                     
          }
        else {
            // The user has decided not to connect with facebook - we still submit the form:
            $( "#theForm" ).submit();                
        }
      },{scope:'email',response_type:'code'});
    }


    // this is a simple function for validating emails
    function IsEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }    

    $( document ).ready(function() {

        $( ".proceed_without").click(function( event) {
          $( "#theForm" ).submit();
        });

      // We are gonna validate the email before the form is submitted
        $( "#theForm" ).submit(function( event ) {
          if (!IsEmail($("#email").val())) {
            // Invalid email! Let's display a simple error:
            $(".email-group").removeClass("has-success").addClass("has-error");
            alert("Please enter a valid email address.");
            event.preventDefault();
          }
        });

      // Color the input green if the email is valid
        $( "#email" ).blur(function ( event ) {
          if (IsEmail($("#email").val())) {
            // Invalid email! Let's display a simple error:
            $(".email-group").removeClass("has-error").addClass("has-success");
          } else {
            $(".email-group").removeClass("has-success").addClass("has-error");
          }          
        });
    });
    </script>
    </body>
</html>
