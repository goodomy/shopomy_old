<?php
       use yii\helpers\Url;
?>
<html lang="en" class="no-scroll">
   
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <title><?= Yii::$app->params['apptitle']; ?></title>
      <link href='https://fonts.googleapis.com/css?family=Lato:900,900italic,700italic,700,400italic,400,300italic,300,100italic,100&subset=latin,latin-ext' rel='stylesheet' type='text/css' />
      <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css' />
      <style>
         @import url("https://fonts.googleapis.com/css?family=Lato:900,900italic,700italic,700,400italic,400,300italic,300,100italic,100&subset=latin,latin-ext' rel='stylesheet' type='text/css");
         @import url("https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'");
      </style>
   </head>
    
        <body style="width:600px;margin:50px auto;color: #000;font-family: 'Lato', sans-serif;font-size: 16px;font-weight: 400;line-height: 1.62857143;-webkit-font-smoothing: antialiased;">
            <div class="main-content-wrap">
               <div class="header"> 
                  <div class="logo" style="padding: 0px 0px 20px;text-align: center;border-bottom: 3px solid #39b54a;">
                    <img  src="<?=Url::to("@web/img/logo.png",true) ?>" alt="<?php echo Yii::$app->params['appName']?>" style="max-width: 200px;" />
                  </div>
               </div>
               <div class="main-content" style="overflow: hidden;text-align: left;">
                  <div class="section-1" style="padding: 70px 0px;">
                     <p style="text-align: left;margin-top: 0px;">Hello <strong><?=$name?></strong>,</p>
                     <p style="text-align: left;margin-top: 0px;">Welcome to <strong><?= Yii::$app->params['apptitle']; ?></strong>. You have been added in <?= Yii::$app->params['apptitle']; ?> <?php //echo $role?>.</p>
                     <!--<h5  style="text-align: left;">Dashboard Links: <a href="<?=Url::to("@web/admin/default/",true);?>"><?=Url::to("@web/admin/dashboard",true);?></a></h5>-->
                     <p  style="text-align: left;">Use the credentials below to sign-in.</p>
                     <p  style="text-align: left;">Username : <strong><?=$email?></strong></p>
                     <p  style="text-align: left;">Password : <strong><?=$password?></strong></p>
                     
                     <p style="text-align: left;">Thank you,<br /><?= Yii::$app->params['apptitle']; ?></p>
                  </div>
               </div>
            </div>
        </body>
</html>
