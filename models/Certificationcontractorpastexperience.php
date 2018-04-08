<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "certification_contractor_past_experience".
 *
 * @property integer $certification_contractor_past_experience_id
 * @property integer $certification_contractor_id
 * @property string $certification_contractor_past_experience_place
 * @property string $certification_contractor_past_experience_detail
 * @property string $certification_contractor_past_experience_from_year_month
 * @property string $certification_contractor_past_experience_till_year_month
 */
class Certificationcontractorpastexperience extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'certification_contractor_past_experience';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['certification_contractor_id', 'certification_contractor_past_experience_place', 'certification_contractor_past_experience_detail', 'certification_contractor_past_experience_from_year_month', 'certification_contractor_past_experience_till_year_month'], 'required'],
            [['certification_contractor_id'], 'integer'],
            [['certification_contractor_past_experience_detail'], 'string'],
            [['certification_contractor_past_experience_from_year_month', 'certification_contractor_past_experience_till_year_month'], 'safe'],
            [['certification_contractor_past_experience_place'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'certification_contractor_past_experience_id' => Yii::t('app', 'Certification Contractor Past Experience ID'),
            'certification_contractor_id' => Yii::t('app', 'Certification Contractor ID'),
            'certification_contractor_past_experience_place' => Yii::t('app', 'Certification Contractor Past Experience Place'),
            'certification_contractor_past_experience_detail' => Yii::t('app', 'Certification Contractor Past Experience Detail'),
            'certification_contractor_past_experience_from_year_month' => Yii::t('app', 'Certification Contractor Past Experience From Year Month'),
            'certification_contractor_past_experience_till_year_month' => Yii::t('app', 'Certification Contractor Past Experience Till Year Month'),
        ];
    }
}
