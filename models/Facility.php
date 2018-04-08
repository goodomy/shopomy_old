<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
/**
 * This is the model class for table "facility_master".
 *
 * @property integer $id
 * @property string $name
 * @property string $is_active
 * @property string $is_deleted
 * @property integer $i_by
 * @property integer $i_date
 * @property integer $u_by
 * @property integer $u_date
 */
class Facility extends \yii\db\ActiveRecord
{
    public $full_image;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'facility_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['is_active', 'name'], 'required'],
            [['is_active', 'is_deleted'], 'string'],
            [['i_by', 'i_date', 'u_by', 'u_date'], 'integer'],
            [['name','image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'image' => Yii::t('app', 'Image'),
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
        if($this->image != '')
        $this->full_image = Url::to('@web/'.$this->image,true);
        else        
        $this->full_image = '';
    }
}
