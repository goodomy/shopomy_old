<?php

namespace app\models;

use Yii;
use app\models\Product;
use app\models\Service;
use app\models\Facility;
use app\models\Stationreview;

/**
 * This is the model class for table "feeling_station".
 *
 * @property integer $id
 * @property string $name
 * @property string $region
 * @property string $phone_number
 * @property string $address
 * @property string $latitude
 * @property string $longitude
 * @property string $info
 * @property string $services
 * @property string $products
 * @property string $facilities
 * @property string $is_active
 * @property string $is_deleted
 * @property integer $i_by
 * @property integer $i_date
 * @property integer $u_by
 * @property integer $u_date
 */
class Feelingstation extends \yii\db\ActiveRecord
{
    public $distance;
    public $avg_rating;
    public $rating_count;
    public $user_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feeling_station';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['info', 'services', 'products', 'facilities', 'is_active', 'is_deleted','map_link'], 'string'],
            [['station_id', 'i_by', 'i_date', 'u_by', 'u_date'], 'integer'],
            [['working_hours'], 'integer','integerOnly' => false],
            [['name', 'region', 'phone_number', 'address', 'latitude', 'longitude'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'station_id' => Yii::t('app', 'Station ID'),
            'name' => Yii::t('app', 'Name'),
            'region' => Yii::t('app', 'Region'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'address' => Yii::t('app', 'Address'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'info' => Yii::t('app', 'Info'),
            'services' => Yii::t('app', 'Services'),
            'products' => Yii::t('app', 'Products'),
            'facilities' => Yii::t('app', 'Facilities'),
            'working_hours' => Yii::t('app', 'Working Hours'),
            'is_active' => Yii::t('app', 'Is Active'),
            'map_link' => Yii::t('app', 'Map Link'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'i_by' => Yii::t('app', 'I By'),
            'i_date' => Yii::t('app', 'I Date'),
            'u_by' => Yii::t('app', 'U By'),
            'u_date' => Yii::t('app', 'U Date'),
        ];
    }
    
    public function afterfind()
    {
        $this->avg_rating = 0;
        $this->rating_count = 0;
        
        $this->rating_count = Stationreview::find()->where(['feeling_station_id'=>$this->id,'is_deleted'=>'N','is_active'=>'Y'])->count();
        
        $avg_rating = Stationreview::find()->where(['feeling_station_id'=>$this->id,'is_deleted'=>'N','is_active'=>'Y'])->average('rate');
        if($avg_rating > 0)
        $this->avg_rating = round($avg_rating,2);
        //$this->avg_rating = $avg_rating;
        
    }
    
    public function getProduct($id)
    {
        $id = explode(',',$id);
        $exist1 = Product::find()->where(['id'=>$id,'is_deleted'=>'N'])->all();
        $i = 0;
        $exist = array();
        foreach($exist1 as $product)
        {
            $exist[$i]['id'] = $product->id;
            $exist[$i]['name'] = $product->name;
            $exist[$i]['image'] = $product->full_image;
            $i++;
        }
        return $exist;
    }
    public function getService($id)
    {
        $id = explode(',',$id);
        $exist1 = Service::find()->where(['id'=>$id,'is_deleted'=>'N'])->all();
        $i = 0;
        $exist = array();
        foreach($exist1 as $product)
        {
            $exist[$i]['id'] = $product->id;
            $exist[$i]['name'] = $product->name;
            $exist[$i]['image'] = $product->full_image;
            $i++;
        }
        return $exist;
    }
    public function getFacility($id)
    {
        $id = explode(',',$id);
        $exist1 = Facility::find()->where(['id'=>$id,'is_deleted'=>'N'])->all();
        $i = 0;
        $exist = array();
        foreach($exist1 as $product)
        {
            $exist[$i]['id'] = $product->id;
            $exist[$i]['name'] = $product->name;
            $exist[$i]['image'] = $product->full_image;
            $i++;
        }
        return $exist;
    }
}
