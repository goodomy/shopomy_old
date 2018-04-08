<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cms_master".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $is_active
 * @property string $is_deleted
 * @property integer $i_by
 * @property integer $i_date
 * @property integer $u_by
 * @property integer $u_date
 */
class Cms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'is_active', 'is_deleted'], 'string'],
            [['i_by', 'i_date', 'u_by', 'u_date'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'i_by' => 'I By',
            'i_date' => 'I Date',
            'u_by' => 'U By',
            'u_date' => 'U Date',
        ];
    }
}
