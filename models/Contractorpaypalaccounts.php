<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contractor_paypal_accounts".
 *
 * @property integer $contractor_paypal_accounts_id
 * @property integer $contractor_id
 * @property string $paypal_id
 * @property string $added_on
 * @property string $main
 */
class Contractorpaypalaccounts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contractor_paypal_accounts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contractor_id', 'paypal_id', 'added_on'], 'required'],
            [['contractor_id'], 'integer'],
            [['added_on'], 'safe'],
            [['main'], 'string'],
            [['paypal_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contractor_paypal_accounts_id' => Yii::t('app', 'Contractor Paypal Accounts ID'),
            'contractor_id' => Yii::t('app', 'Contractor ID'),
            'paypal_id' => Yii::t('app', 'Paypal ID'),
            'added_on' => Yii::t('app', 'Added On'),
            'main' => Yii::t('app', 'Main'),
        ];
    }
}
