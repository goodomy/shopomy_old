<?php

namespace app\components;
 
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use app\models\Users;
use app\models\Post;
use app\models\Follow;
 
class CommonFunction extends Component
{
    /**
    * for getting time deffernce between current time and passed time
    * @param int $old
    * @return difference between $old and current time
    */
    public static function timedifference($old=0)
    {
		$current = time();
		$difference = $current - $old;
		$years = abs(floor($difference / 31536000));
		$days = abs(floor(($difference-($years * 31536000))/86400));
		$hours = abs(floor(($difference-($years * 31536000)-($days * 86400))/3600));
		$mins = abs(floor(($difference-($years * 31536000)-($days * 86400)-($hours * 3600))/60));
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
		}else{
			$timeString = "1 min ";
		}
		return $timeString;
    }
    /**
    * for sending ios pushnotification
    * @param string $deviceToken
    * @param array $body
    */
    public static function pushnotification_iphone($deviceToken, $body)
    {
		//pem file path
		if(Yii::$app->params['ios_push_environment'] == 'prod')
			$pem_file = Yii::getAlias('@webroot').Yii::$app->params['ios_prod_file'];//for production
		else
			$pem_file = Yii::getAlias('@webroot').Yii::$app->params['ios_dev_file'];//for development
			
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $pem_file);
		
		
		if(Yii::$app->params['ios_push_environment'] == 'prod')
			$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 600, STREAM_CLIENT_CONNECT, $ctx); //for production
        else
			$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx); //for development
		
		$payload = json_encode($body);
		
		$msg = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n",strlen($payload)) . $payload;
		
		fwrite($fp, $msg);
		fclose($fp);
    }
	/**
    * for sending android pushnotification
    * @param string $deviceToken
    * @param array $body
    */
    public static function pushnotification_android($deviceToken, $body)
    {
		$url = 'https://android.googleapis.com/gcm/send';
		//server api key for android pushnotification
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
		curl_close($ch);
    }
	
	/**
    * for sending ios pushnotification to multiple users
    * @param array $deviceToken
    * @param array $body
    */
	public static function pushnotification_iphone_array($deviceToken, $body)
    {
		try
        {
            if(isset($deviceToken) && $deviceToken != array())
            {
				
				if(Yii::$app->params['ios_push_environment'] == 'prod')
					$pem_file = Yii::getAlias('@webroot').Yii::$app->params['ios_prod_file'];//for production
				else
					$pem_file = Yii::getAlias('@webroot').Yii::$app->params['ios_dev_file'];//for development
                    
                foreach($deviceToken as $key=>$device1)
                {
                    if($device1 != array())
                    {
                        $ctx = stream_context_create();
                        stream_context_set_option($ctx, 'ssl', 'local_cert', $pem_file);
                        
                        if(Yii::$app->params['ios_push_environment'] == 'prod')
							$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 600, STREAM_CLIENT_CONNECT, $ctx); //for production
						else
							$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx); //for development
                        
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
										//$body['aps']['badge'] = (int)  Yii::$app->common->badge($key);
                                        $payload = json_encode($body);
                                        $msg = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $device)) . pack("n",strlen($payload)) . $payload;
                                        fwrite($fp, $msg);
                                    }
                                }
                                catch(Exception $e)
                                {
                                    //echo 'Not Sent due to...';
                                    //print_r($e); die;
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
            //echo 'Not Sent due to...';
            //print_r($e); die;
        }
    }
   
    
	/**
    * for sending android pushnotification to multiple users
    * @param array $deviceToken
    * @param array $body
    */
	public static function pushnotification_android_array($deviceToken,$body)
    {
      //print_r($deviceToken); die;
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
			  //print_r($value);die;
			  $value = array_values($value);
			  //$token_block[$ij] = 
			   $data = array(
				  'registration_ids' => $value,
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
			   curl_close($ch);
		   }
		}
    }
    
	/**
    * for display pagination dropdown on index page
    * @return array
    */
	public function paginationarray()
    {
        $pagination=['1'=>'1','5'=>'5','10'=>'10','20'=>'20','30'=>'30','50'=>'50','100'=>'100'];
        return $pagination;
    }
	/**
    * function to check user is deleted or not before calling any api
    * @param $id
    * @return status
    */
	public function checkuser($id = null)
    {
        if(isset($_POST['userid']) && $_POST['userid'] != '')
        {
            $data = Users::find()->where(['user_type'=>'U','is_deleted'=>'N','id'=>$_POST['userid']])->one();
            if($data != array())
            {
                return 'Y';    
            }
            else
            {
                return 'N';
            }
        }else{
            return 'Y';
        }
    }
    /**
    * function to check user is deleted or not before calling any api
    * @param float $lat1
    * @param float $lat1 [latitude1]
    * @param float $lon1 [longitude1]
    * @param float $lat2 [latitude2]
    * @param float $lon2 [longitude2]
    * @param float $unit [K = 'Kilometere' , 'M' = Miles]
    * @return distance as per unit
    */
	public function distance($lat1=0, $lon1=0, $lat2=0, $lon2=0, $unit="M")
    {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$miles = $dist * 3959; //convert distance into miles
		$unit = strtoupper($unit);
		if ($unit == "K") {
			return ($dist * 6371); //convert distance into kilometers
		} else {
			return $miles;
		}
    }
    //coutn total number of feed
    public function count_feed($id)
    {
	$model = Post::find()->where('user_id = "'.$id.'" and is_active= "y" and is_deleted ="n"')->count();
	if($model!=0)
	    return $model;
	else
	    return 0;
	
    }
    //coutn total number of followers
    public function count_followers($id)
    {
	$model = Follow::find()->where('following_id = "'.$id.'"')->count();
	if($model!=0)
	    return $model;
	else
	    return 0;
	
    }
    //coutn total number of following
    public function count_following($id)
    {
	$model = Follow::find()->where('follower_id = "'.$id.'"')->count();
	if($model!=0)
	    return $model;
	else
	    return 0;
    }
	/* Openfire Rest API Implementation */
	public function adduser()
	{
		
		include  \Yii::getAlias('@vendor')."/openfire/OpenFireRestApi.php";

		// Create the Openfire Rest api object
		$api = new \Gidkom\OpenFireRestApi\OpenFireRestApi;
		
		// Set the required config parameters
		$api->secret = "9a4u36qHGO8Axh40";
		$api->host = "192.168.1.144";
		$api->port = "9090";  // default 9090
		
		// Optional parameters (showing default values)
		
		$api->useSSL = false;
		$api->plugin = "/plugins/restapi/v1";  // plugin 
		
		// Add a new user to OpenFire and add to a group
		//$result = $api->addUser('Ravi', '123', 'Peerdev Ravi', 'ravi@peerbits.com', array());
		//$result = $api->addUser('Aadil', '123', 'Peerdev Aadil', 'aadil@peerbits.com', array());
		
		//$result = $api->updateUser('Ravi', '123', 'Peerdeveloper Ravi', 'ravi@peerbits.com', array());
		
		//$jid = "user1@192.168.1.144";
		//$api->addToRoster('user3',$jid,'user1','both');
		//
		//$jid = "Ravi@192.168.1.144";
		////Delete from roster
		//$api->addToRoster("Aadil", $jid);
		//
		////Update user roster
		//$api->updateRoster("Ravi", "Aadil@192.168.1.144", "Aadil", "Friends");
		
		
		
		
		// Check result if command is succesful
		if($result['status']) {
			// Display result, and check if it's an error or correct response
			echo 'Success: ';
			echo $result['message'];
		} else {
			// Something went wrong, probably connection issues
			echo 'Error: ';
			echo $result['message'];
		}


	}

	public function normalUpload($new_image,$path){
	  $image = $new_image["name"];
      $ext = strtolower(substr(strrchr($image, "."), 1));
      $fileName = md5(rand() * time()) . ".$ext";
      $path = $path.$fileName;
      move_uploaded_file($new_image['tmp_name'],Yii::getAlias('@webroot')."/".$path);
      return $path;
  }

	
	
	
	
        
}
?>