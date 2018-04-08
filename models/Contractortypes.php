<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service_type_master".
 *
 * @property integer $service_type_id
 * @property string $service_type_title
 * @property string $service_type_description
 * @property string $abbrevation
 * @property string $requirements
 * @property string $is_active
 */
class Contractortypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_type_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_type_title', 'service_type_description', 'abbrevation', 'requirements', 'is_active'], 'required'],
            [['service_type_description'], 'string'],
            [['service_type_title', 'requirements'], 'string', 'max' => 255],
            [['abbrevation'], 'string', 'max' => 10],
            [['is_active'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'service_type_id' => Yii::t('app', 'Service Type ID'),
            'service_type_title' => Yii::t('app', 'Service Type Title'),
            'service_type_description' => Yii::t('app', 'Service Type Description'),
            'abbrevation' => Yii::t('app', 'Abbrevation'),
            'requirements' => Yii::t('app', 'Requirements'),
            'is_active' => Yii::t('app', 'Status'),
        ];
    }
}
