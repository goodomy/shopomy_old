<?php

namespace app\models;
use yii\helpers\Url;
use Yii;

/**
 * This is the model class for table "advertise_master".
 *
 * @property integer $id
 * @property string $title
 * @property string $media_type
 * @property string $media_path
 * @property string $is_active
 * @property string $is_deleted
 * @property integer $i_by
 * @property integer $i_date
 * @property integer $u_by
 * @property integer $u_date
 */
class Advertise extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
     public $full_image;
     public $image;
    public static function tableName()
    {
        return 'advertise_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['image'], 'required'],
            [['media_type', 'is_active', 'is_deleted'], 'string'],
            [['i_by', 'i_date', 'u_by', 'u_date'], 'integer'],
            [['title', 'media_path'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'media_type' => Yii::t('app', 'Media Type'),
            'media_path' => Yii::t('app', 'Media Path'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'i_by' => Yii::t('app', 'I By'),
            'i_date' => Yii::t('app', 'I Date'),
            'u_by' => Yii::t('app', 'U By'),
            'u_date' => Yii::t('app', 'U Date'),
        ];
    }
    
     public function afterfind()
    {
        if($this->media_path != '')
        {
            if(strpos($this->media_path, "http://") !== false)
            {
                $this->full_image = $this->media_path;  
            }else{
                $this->full_image = Url::to('@web/'.$this->media_path,true);
            }
        }
        else
        {
            $this->full_image = '';
        }
    }
    
    
}
