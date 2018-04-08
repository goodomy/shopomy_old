<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "certification_contractor".
 *
 * @property integer $certification_contractor_id
 * @property integer $contractor_id
 * @property integer $service_type_id
 * @property string $front_side_image
 * @property string $back_side_image
 * @property integer $total_experience_years
 * @property integer $total_experience_month
 * @property string $tb_xray_image
 * @property string $skin_test_image
 * @property integer $i_date
 * @property string $is_active
 * @property string $is_deleted
 * @property integer $i_by
 * @property integer $u_by
 * @property integer $u_date
 */
class Certificationcontractor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'certification_contractor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contractor_id', 'service_type_id', 'front_side_image', 'back_side_image', 'total_experience_years', 'total_experience_month', 'tb_xray_image', 'skin_test_image'], 'required'],
            [['contractor_id', 'service_type_id', 'total_experience_years', 'total_experience_month', 'i_date', 'i_by', 'u_by', 'u_date'], 'integer'],
            [['is_active', 'is_deleted'], 'string'],
            [['front_side_image', 'back_side_image', 'tb_xray_image', 'skin_test_image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'certification_contractor_id' => Yii::t('app', 'Certification Contractor ID'),
            'contractor_id' => Yii::t('app', 'Contractor ID'),
            'service_type_id' => Yii::t('app', 'Service Type ID'),
            'front_side_image' => Yii::t('app', 'Front Side Image'),
            'back_side_image' => Yii::t('app', 'Back Side Image'),
            'total_experience_years' => Yii::t('app', 'Total Experience Years'),
            'total_experience_month' => Yii::t('app', 'Total Experience Month'),
            'tb_xray_image' => Yii::t('app', 'Tb Xray Image'),
            'skin_test_image' => Yii::t('app', 'Skin Test Image'),
            'i_date' => Yii::t('app', 'I Date'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'i_by' => Yii::t('app', 'I By'),
            'u_by' => Yii::t('app', 'U By'),
            'u_date' => Yii::t('app', 'U Date'),
        ];
    }
}
