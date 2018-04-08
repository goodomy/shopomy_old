<?php

namespace app\components;
 
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Users;
 
class PhotoComponent extends Component
{
    /**
    * for creating thumb nail of image
    * @param array $image
    * @param string $uploadDir
    * @param int $w
    * @param int $thumbnail_width
    * @return image path
    */
    public function uploadUserImage($image, $uploadDir, $w, $thumbnail_width)
    {
        $imagePath = '';		
        $thumbnailPath = '';
        
        if (trim($image['tmp_name']) != '')
        {
            $ext = substr(strrchr($image['name'], "."), 1);
            
            $imagePath = md5(rand() * time()) . ".$ext";
            
            list($width, $height, $type, $attr) = getimagesize($image['tmp_name']); 
            
            $this->createThumbnail($image['tmp_name'], $uploadDir . $imagePath, $w,100);
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
            
        if ($tmpDest['extension'] == "gif" || $tmpDest['extension'] == "jpg" || $tmpDest['extension'] == "jpeg")
        {
                //$destFile  = substr_replace($destFile, 'jpg', -3);
                $dest      = imagecreatetruecolor($w, $h);
                
              // imageantialias($dest, TRUE);
        }
        elseif ($tmpDest['extension'] == "png")
        {
               $dest = imagecreatetruecolor($w, $h);
               //imageantialias($dest, TRUE);			
        }
         else
        {
              return false;
        }
        //print_r($size); die;
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
}


?>