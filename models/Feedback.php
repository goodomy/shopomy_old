<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback_master".
 *
 * @property string $id
 * @property integer $user_id
 * @property integer $feeling_station_id
 * @property string $email
 * @property string $subject
 * @property string $comment
 * @property string $is_active
 * @property string $is_deleted
 * @property integer $i_by
 * @property integer $i_date
 * @property integer $u_by
 * @property integer $u_date
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'feeling_station_id', 'i_by', 'i_date', 'u_by', 'u_date'], 'integer'],
            [['comment', 'is_active', 'is_deleted'], 'string'],
            [['email', 'subject'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User'),
            'feeling_station_id' => Yii::t('app', 'Filling Station'),
            'email' => Yii::t('app', 'Email'),
            'subject' => Yii::t('app', 'Subject'),
            'comment' => Yii::t('app', 'Comment'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'i_by' => Yii::t('app', 'I By'),
            'i_date' => Yii::t('app', 'I Date'),
            'u_by' => Yii::t('app', 'U By'),
            'u_date' => Yii::t('app', 'U Date'),
        ];
    }
}
