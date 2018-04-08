<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cancellation_reason_master".
 *
 * @property integer $id
 * @property string $name
 * @property string $role
 * @property string $is_active
 * @property string $is_deleted
 * @property integer $i_by
 * @property string $i_date
 * @property integer $u_by
 * @property string $u_date
 */
class Cancellationreason extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cancellation_reason_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['i_by', 'u_by'], 'integer'],
            [['i_date', 'u_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['is_active', 'is_deleted', 'role'], 'string'],
            
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
            'role' => Yii::t('app', 'Role'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'i_by' => Yii::t('app', 'I By'),
            'i_date' => Yii::t('app', 'I Date'),
            'u_by' => Yii::t('app', 'U By'),
            'u_date' => Yii::t('app', 'U Date'),
        ];
    }
}
