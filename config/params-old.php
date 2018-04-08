<?php

include('api_messages.php');

// set default timezone in index.php

return array_merge(
            array(
                'apptitle' => 'Shopomy',//APP TITLE
                'applink' => 'www.google.com',//APP TITLE
                'appName' => 'Shopomy',
                'appcookiename' => 'shopomy',
                'adminEmail' => 'admin@shopomy.com',
                //'adminEmail' => 'shrikant@peerbits.com',
                'msg_success' =>'<div class="alert alert-dismissable alert-success fade in">
                                  <button data-dismiss="alert" class="close close-sm" type="button">
                                      <i class="fa fa-times"></i>
                                  </button>
                                  <strong>Success!</strong> ',
                'msg_error' =>  '<div class="alert alert-dismissable alert-block alert-danger fade in">
                                  <button data-dismiss="alert" class="close close-sm" type="button">
                                      <i class="fa fa-times"></i>
                                  </button>
                                  <strong>Error!</strong> ',
                'encryption_key'=>'}{(**&%%^$%@#$&*!#$%#&*^&*',
                'msg_end' => '</div>',
                'userimage' => 'img/uploads/users/',
                'postimage' => 'img/uploads/post_images/',
                'postvideo' => 'img/uploads/post_videos/',
                'contractorimage' => 'img/uploads/contractors/',
                'advertiseimage' => 'img/uploads/advertise/',
                'facilityimage' => 'img/uploads/facility/',
                'productimage' => 'img/uploads/products/',
                'serviceimage' => 'img/uploads/services/',
                
                'dateformat' => 'd , M - Y',
                'datetimeformat' => 'd-m-Y H:i',
                //'displayTimezone' => 'Asia/Kolkata',
                'displayTimezone' => 'UTC',
                'response_text'=>array(200=>"Success",400=>'Bad Request',401=>'Unauthorized',403=>'Forbidden',404=>'Not Found',500=>'Internal Server Error',601=>'Data Dupliacation',602=>'Could Not Save',603=>'No data found'),
                'ios_push_environment'=>'dev', // dev = development, prod = production/distribution
                'ios_dev_file'=>'/pem/dev_apns.pem', // put .pem file in folder protected/pem/dev_apns.pem
                'ios_prod_file'=>'/pem/prod_apns.pem', // put .pem file in folder protected/pem/prod_apns.pem
                //'android_server_api_key'=>"AIzaSyBav_WbU22Sxhk8ijc7hjCKuhsEF3Ktixg", // android server api key
																'android_server_api_key'=>"AIzaSyBeseWO0D4O4LeE7xRpUWjMQ79x3tCVHmw", // android server api key
                'google_api_key'=>"AIzaSyAeCDbEPFYP5aVlxPzE8ZDE2O3I_pelYOM", // my
																//'google_api_key'=>"AIzaSyDeAU4Z4WYsKNtXjL_-CIb_AA8UHg1fTSA", // client
																//'google_api_key'=>"AIzaSyBI0pLbanJEnr71QP60NB5894BIj-VKB-E", // client working
            ),$array);
?>