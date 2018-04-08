<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use yii\web\Session;
use app\models\Users;
use app\models\Category;
use app\models\Setting;
use app\models\Authitem;
use app\models\Role;
use app\models\Contractortypes;
use app\models\Organizationtype;
use app\models\Contractorpaypalaccounts;


class MyComponent extends Component
{

    public function uploadUserImage($image, $uploadDir, $w, $thumbnail_width)
    {
            $imagePath = '';
            $thumbnailPath = '';

            if (trim($image['tmp_name']) != '')
            {
                    $ext = substr(strrchr($image['name'], "."), 1);

                    $imagePath = 'thumb-'.md5(rand() * time()) . ".$ext";

                    list($width, $height, $type, $attr) = getimagesize($image['tmp_name']);

                    $this->createThumbnail($image['tmp_name'], $uploadDir . $imagePath, $w,$height);
            }
            $arr['image'] = $imagePath;
            return $arr;
    }
    public function createThumbnail($srcFile, $destFile, $width, $quality = 90)
    {
            $thumbnail = '';

            if (file_exists($srcFile)  && isset($destFile))
            {
                    $size        = getimagesize($srcFile);
                    $w           = number_format($width, 0, ',', '');
                    $h           = number_format(($size[1] / $size[0]) * $width, 0, ',', '');

                    $thumbnail =  $this->copyImage($srcFile, $destFile, $w, $h, $quality);
            }

            // return the thumbnail file name on sucess or blank on fail

            return basename($thumbnail);
    }

    function copyImage($srcFile, $destFile, $w, $h, $quality = 75)
    {
        $tmpSrc     = pathinfo(strtolower($srcFile));
        $tmpDest    = pathinfo(strtolower($destFile));
        $size       = getimagesize($srcFile);

        if ($tmpDest['extension'] == "gif" || $tmpDest['extension'] == "jpg")
        {
                $destFile  = substr_replace($destFile, 'jpg', -3);
                $dest      = imagecreatetruecolor($w, $h);

              // imageantialias($dest, TRUE);
        }
        elseif ($tmpDest['extension'] == "png" || $tmpDest['extension'] == "jpeg")
        {
               $dest = imagecreatetruecolor($w, $h);
               //imageantialias($dest, TRUE);
        }
        else
        {
              return false;
        }

        switch($size[2])
        {
           case 1:       //GIF
               $src = imagecreatefromgif($srcFile);
               break;
           case 2:       //JPEG
               $src = imagecreatefromjpeg($srcFile);
               break;
           case 3:       //PNG
               $src = imagecreatefrompng($srcFile);
               break;
           default:
               return false;
               break;
        }

        imagecolortransparent($dest, imagecolorallocatealpha($dest, 0, 0, 0, 127));
        imagealphablending($dest, false);
        imagesavealpha($dest, true);
        imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);

        switch($size[2])
        {
           case 1:
           case 2:
               imagejpeg($dest,$destFile, $quality);
               break;
           case 3:
               imagepng($dest,$destFile);
        }
        return $destFile;
    }

    public function timestamp_to_date($timestamp=null,$timezone=null,$format=null)
    {
        if($timezone != '')
            date_default_timezone_set($timezone);

        if(!$format)
        {
            if(isset(Yii::$app->params['dateformat']) && Yii::$app->params['dateformat'] != null)
                $format = Yii::$app->params['dateformat'];
            else
                $format = 'd-m-Y';
        }

        if(isset($timestamp))
        {
            $session = Yii::$app->session;
            if ($session->isActive && $session->get('admintimezone') != '')
            {
                date_default_timezone_set($session->get('admintimezone'));
            }
            //echo $timezone;
            //if ($timezone != null)
            //{
            //    //echo $timezone; die;
            //    date_default_timezone_set($timezone);
            //}

            return date($format,$timestamp);
        }
        else
        {
            return date($format,0);
        }
    }

    public function date_to_timestamp($date=null,$format=null)
    {
        if(!$format)
        {
            if(isset(Yii::$app->params['dateformat']) && Yii::$app->params['dateformat'] != null)
                $format = Yii::$app->params['dateformat'];
            else
                $format = 'd-m-Y';
        }

        if($date)
        {
            $datetime = DateTime::createFromFormat($format, '10-25-2012 10:10');
            $timestamp = $datetime->getTimestamp();
        }
        else
        {
            $datetime = DateTime::createFromFormat($format, '01-01-1970 00:00');
            $timestamp = $datetime->getTimestamp();
        }

        return $timestamp;
    }
    public function timeDiff1($old = 0,$timezone=null)
    {
        if(isset($timezone) && $timezone != null)
        {
            date_default_timezone_set($timezone);
        }

        $current = time();
        //$old = 1363164299;
        $difference = $current - $old;
        $years = abs(floor($difference / 31536000));
        $months = abs(floor(($difference-($years * 31536000))/2592000));
        $days = abs(floor(($difference-($years * 31536000))/86400));
        $hours = abs(floor(($difference-($years * 31536000)-($days * 86400))/3600));
        $mins = abs(floor(($difference-($years * 31536000)-($days * 86400)-($hours * 3600))/60));
        $timeString = "";
        if($years > 0)
        {
            //$timeString = $months > 1 ? $months . " days ago" : $months . " day ago";
            $timeString = $years . "y";
        }
        elseif($months > 0)
        {
            //$timeString = $months > 1 ? $months . " days ago" : $months . " day ago";
            $timeString = $months . "m";
        }
        elseif($days > 0)
        {
            //$timeString = $days > 1 ? $days . " days ago" : $days . " day ago";
             $timeString = $days . "d";
        }
        elseif($hours > 0)
        {
            //$timeString = $hours > 1 ? $hours . " hours ago" : $hours . " hour ago";
             $timeString = $hours . "h";
        }
        elseif($mins > 0)
        {
            //$timeString = $mins > 1 ? $mins . " mins ago" : $mins . " min ago";
            $timeString = $mins . "min";
        }else{
            $timeString = "now";
        }
        return $timeString;
    }



    // send welcome email to registered user, using email id and name
    public function sendwelcomeemail($email,$name)
    {
        $content = "Hello $name, <br>Welcome to ".Yii::$app->params['appName'];
        Yii::$app->mailer->compose('@app/mail/layouts/welcomeemail',['content'=>$content])
                 ->setTo($email)
                 ->setFrom(\Yii::$app->params['adminEmail'])
                 ->setSubject('Welcome to '.Yii::$app->params['appName'])
                 ->send();

    }


    //for sending pushnotification
    public static function pushnotification_iphone_array($deviceToken, $body)
    {
        try
        {
            if(isset($deviceToken) && $deviceToken != array())
            {
                if(Yii::$app->params['ios_push_environment'] == 'prod')
                    $pem_file = Yii::getAlias('@webroot').Yii::$app->params['ios_prod_file'];//for live
                else
                    $pem_file = Yii::getAlias('@webroot').Yii::$app->params['ios_dev_file'];//for live

                foreach($deviceToken as $key=>$device1)
                {
                    if($device1 != array())
                    {
                        $ctx = stream_context_create();
                        stream_context_set_option($ctx, 'ssl', 'local_cert', $pem_file);

                        if(Yii::$app->params['ios_push_environment'] == 'prod')
                            $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 600, STREAM_CLIENT_CONNECT, $ctx); // for live
                        else
                            $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx); // for developer

                        if (!$fp)
                        {
                            // error
                        }
                        else
                        {
                            foreach($device1 as $key=>$device)
                            {
                                try
                                {
                                    if($device != '')
                                    {
                                        $payload = json_encode($body);
                                        $time = time()+(30*24*60*60);
                                        $msg = chr(0) .  pack("n",32) . pack('H*', str_replace(' ', '', $device)) . pack("n",strlen($payload)) . $payload;
                                        fwrite($fp, $msg);
                                    }
                                }
                                catch(Exception $e)
                                {
                                    echo 'Not Sent due to...';
                                    print_r($e); die;
                                }
                            }
                        }
                        fclose($fp);
                    }
                }
            }
        }
        catch(Exception $e)
        {
            echo 'Not Sent due to...';
            print_r($e); die;
        }
    }

    public static function pushnotification_android_array($deviceToken,$body)
    {
        if(isset($deviceToken) && $deviceToken != null && $deviceToken != array())
        {
            $url = 'https://android.googleapis.com/gcm/send';
            $serverApiKey = Yii::$app->params['android_server_api_key'];

            $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $serverApiKey
            );

            foreach($deviceToken as $key=>$value)
            {
                $data = array(
                       'registration_ids' => $value,
                       'data' => $body
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                if ($headers)
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                $response = curl_exec($ch);
                curl_close($ch);
            }
        }
    }

    //for sending pushnotification
    public static function pushnotification_iphone($deviceToken, $body)
    {
        try{
            if(isset($deviceToken) && $deviceToken != null)
            {
                if(Yii::$app->params['ios_push_environment'] == 'prod')
                    $pem_file = Yii::getAlias('@webroot').Yii::$app->params['ios_prod_file'];//for live
                else
                    $pem_file = Yii::getAlias('@webroot').Yii::$app->params['ios_dev_file'];//for live

                $ctx = stream_context_create();
                stream_context_set_option($ctx, 'ssl', 'local_cert', $pem_file);

                if(Yii::$app->params['ios_push_environment'] == 'prod')
                    $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 600, STREAM_CLIENT_CONNECT, $ctx); // for live
                else
                    $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx); // for developer

                if (!$fp)
                {
                    // error
                }
                else
                {
                    //if(strlen($deviceToken) == 64)
                    {
                        $payload = json_encode($body);
                        $msg = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n",strlen($payload)) . $payload;
                        fwrite($fp, $msg);
                        fclose($fp);
                    }
                }
            }
        }
        catch(Exception $e)
        {

        }
    }

    public static function pushnotification_android($deviceToken, $body)
    {
        if(isset($deviceToken) && $deviceToken != null)
        {
            $url = 'https://android.googleapis.com/gcm/send';
            $serverApiKey = Yii::$app->params['android_server_api_key'];

            $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $serverApiKey
            );

            $data = array(
               'registration_ids' => array($deviceToken),
               'data' => $body['data']
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if ($headers)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec($ch);
            //echo '<pre>';
            //print_r($response); die;
            curl_close($ch);
        }
    }

    private function setHeader($status)
    {
        $status_header = 'HTTP/1.1 ' . $status . ' ' . Yii::$app->params['response_text'][$status];
        $content_type="application/json; charset=utf-8";
        header($status_header);
        header('Content-type: ' . $content_type);
        header('X-Powered-By: ' . "Crossfit");
    }

	public function validate_user($value,$encrypted_data)
	{
        $secretkey = Yii::$app->params['encryption_key'];
        $user = hash_hmac('sha256', $value, $secretkey);
        if($user != $encrypted_data)
        {
            $this->setHeader(403);
            echo json_encode(array('code'=>403,'status'=>'error','message'=>utf8_encode(Yii::$app->params['error_user_have_not_access'])));
            die;
        }
        //return $user;
	}


    public function gettimedifference($time)
    {
        $current = time();
        $difference = $current - $time;
        $years = abs(floor($difference / 31536000));
        $days = abs(floor(($difference-($years * 31536000))/86400));
        $hours = abs(floor(($difference-($years * 31536000)-($days * 86400))/3600));
        $mins =  abs(floor(($difference-($years * 31536000)-($days * 86400)-($hours * 3600))/60));

        $timeString = "";
        if($days > 0)
        {
            $timeString = $days > 1 ? $days . " days " : $days . " day ";
        }
        elseif($hours > 0)
        {
            $timeString = $hours > 1 ? $hours . " hours " : $hours . " hour ";
        }
        elseif($mins > 0)
        {
            $timeString = $mins > 1 ? $mins . " mins " : $mins . " min ";
        }
        else
        {
            $timeString = $difference > 1 ? $difference . " secs " : $difference . " sec ";
        }
        return $timeString;
    }
    public function randomstring($id)
    {
        $random_str = time().rand(10000,99999);
        $res = md5($random_str);
        $check = Users::find()->where([$id=>$res])->one();
        if($check)
        {
            $code = Yii::$app->mycomponent->randomstring($id);
            return $code;
        }
        return $res;
    }

    /*public static function getusersname($userid=0)
    {
        $data = Users::find()->where(['is_deleted'=>"N"])->all();
        return ArrayHelper::map($data,'id','first_name');
    }*/

    /******** function for checking Access *******************/
    public function authenticate($controller,$action)
    {

        $uer_type=Users::find()->where(['id'=>Yii::$app->user->id])->one();

        /*echo "$controller--$action";
        exit;*/
        if($uer_type->role_id != 0)
        {

            $d=Authitem::find()->where(["controller"=>$controller,'action'=>$action,'is_deleted'=>'N'])->one();
            if(isset($d) && count($d)>0)
            {

               if(isset($uer_type))
               {
                   $user_roles_array = explode(',',$uer_type->role_id);
                   $permissions_list_array = array();
                    $permission=Role::find()->where(['is_deleted'=>'N',"id"=>$user_roles_array])->all();

                            if(isset($permission) && (!empty($permission[0])))
                            {
                                foreach($permission as $each_permission)
                                {
                                $permissions_list_array = array_merge($permissions_list_array,explode(',',$each_permission->auth_item));
                                }
                            }

                           if(isset($permissions_list_array) && (!empty($permissions_list_array[0])))
                           {
                            $permissions_list_unique = array_unique($permissions_list_array);
                           }
                           else{
                            $permissions_list_unique = array();
                           }


                            /*echo"<pre>";
                            print_r($user_roles_array);
                            print_r($permission);
                            print_r($permissions_list_array);
                            print_r($permissions_list_unique);
                            print_r($d->id);
                            exit;*/

                        if(isset($permissions_list_unique) &&(!empty($permissions_list_unique[0])))
                                if(isset($permissions_list_unique) && in_array($d->id,$permissions_list_unique))
                                {
                                    //echo
                                    return true;
                                }
                                else
                                {
                                    //echo "you have no access";
                                    //exit;
                                    return false;
                                }

                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
             return true;
        }
    }

    //********************************************************************************
    //Title : Get All Organization List
    //Developer: Shahnavaz Patni
    //Email:shahnavaz@peerbits.com
    //Company:Peerbits Solution
    //Project:Nursigo
    //Created By : Shahnavaz Patni
    //Created Date : 22-11-2017
    //Updated Date :
    //Updated By :
    //********************************************************************************
    public function getOrganizationList()
    {
        $result=array();
        $data=Organizationtype::find()->select('id,name')->where(['is_deleted'=>'N'])->all();
        if(isset($data) && $data!=array())
        {
            $result = ArrayHelper::map($data,'id','name');
        }
        return $result;
    }

    public function getorganizationtypebyids($id){
        return Organizationtype::find()->select(["name"])->where(['id'=>$id])->one();
    }

    public function getpaypalidbyids($id){
        return Contractorpaypalaccounts::find()->select(["paypal_id"])->where(['contractor_id'=>$id, 'main'=>'Y'])->one();
    }


    //********************************************************************************
    //Title : Get All Service Type List
    //Developer: Shahnavaz Patni
    //Email:shahnavaz@peerbits.com
    //Company:Peerbits Solution
    //Project:Nursigo
    //Created By : Shahnavaz Patni
    //Created Date : 27-11-2017
    //Updated Date :
    //Updated By :
    //********************************************************************************
    public function getServicetype()
    {
        $result=array();
        $data=Contractortypes::find()->select('service_type_id,abbrevation')->all();
        if(isset($data) && $data!=array())
            $result = ArrayHelper::map($data,'service_type_id','abbrevation');

        return $result;
    }

    public function getservicetypebyids($id){

        return Contractortypes::find()->select(["abbrevation"])->where(['service_type_id'=>$id])->one();

    }


    public function getcategorybyids($id){

        return Category::find()->select(["name"])->where(['id'=>$id])->one();

    }

    public function getfeedbacklist($limit=null)
    {
        $result=array();
        $query=Users::find()->select('id,full_name,image,total_strikes,u_date')->where(['is_deleted'=>'N', 'user_type'=>'C']);
        if($limit!="")
                $query->offset(0)->limit($limit);
        $query->orderBy('total_strikes desc');
        $result=$query->all();

        return $result;
    }

    public function getServicestatuslist()
    {
        $result=['1'=>'Upcoming','2'=>'Requested','3'=>'On-going','4'=>'Completed','5'=>'Expired',6=> 'Canceled'];
        return $result;
    }
    public function getServiceTypeTitle($id){

        return Contractortypes::find()->select(["service_type_title"])->where(['service_type_id'=>$id])->one();

    }

    public function getCategoryList()
    {
        $result=array();
        $data=Category::find()->select('id,name')->where(['is_deleted'=>'N', 'parent_id'=>0])->all();
        if(isset($data) && $data!=array())
        {
            $result = ArrayHelper::map($data,'id','name');
        }
        return $result;
    }

    public function getSubcategoryList_basedonCategory($cid=null)
    {
        $result=array();
        $andwhere='id=0';
        if($cid!=null)
        {
            $andwhere='parent_id='.$cid;
        }
        $data=Category::find()->select('id,name')->where(['is_deleted'=>'N'])->andwhere($andwhere)->all();
        if(isset($data) && $data!=array())
        {
            $result = ArrayHelper::map($data,'id','name');
        }
        
        return $result;
    }

    public function userResponse($user)
    {
        $result['Token']['token'] = $user->access_token;
        $result['Token']['type'] = 'Bearer';
        
        $result['User']['id'] = $user->id;
        $result['User']['name'] = $user->user_name;
        $result['User']['email'] = $user->email;

        if(isset($user->image) && $user->image!=null)
        {
          $result['User']['image'] = $user->image;
        }
        else
        {
          $result['User']['image'] = "";
        }

        if(isset($user->is_social) && $user->is_social!=null)
        {
          $result['User']['is_social'] = $user->is_social;
        }
        else
        {
            $result['User']['is_social'] = "N";
        }

        if(isset($user->search_within_from) && $user->search_within_from!=null)
        {
          $result['User']['search_radius_min'] = $user->search_within_from;
        }
        else
        {
            $result['User']['search_radius_min'] = 0;
        }

        if(isset($user->search_within_to) && $user->search_within_to!=null)
        {
          $result['User']['search_radius_max'] = $user->search_within_to;
        }
        else
        {
            $result['User']['search_radius_max'] = 0;
        }

        if(isset($user->measurement_unit) && $user->measurement_unit!=null)
        {
          $result['User']['search_radius_unit'] = $user->measurement_unit;
        }
        else
        {
            $result['User']['search_radius_unit'] = 0;
        }
        
        
        return $result;
    }

    



}


?>
