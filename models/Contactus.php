<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contactus_messages".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $email_id
 * @property string $subject
 * @property string $message
 * @property integer $i_date
 * @property string $is_deleted
 */
class Contactus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contactus_messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'i_date'], 'integer'],
            [['subject', 'message', 'is_deleted'], 'string'],
            [['email_id'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'email_id' => Yii::t('app', 'Email ID'),
            'subject' => Yii::t('app', 'Subject'),
            'message' => Yii::t('app', 'Message'),
            'i_date' => Yii::t('app', 'I Date'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }
}
