<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cms_page".
 *
 * @property integer $id
 * @property string $page_name
 * @property string $title
 * @property string $content
 * @property string $is_active
 * @property string $is_deleted
 * @property integer $i_by
 * @property integer $i_date
 * @property integer $u_by
 * @property integer $u_date
 */
class Cmspage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'is_active', 'is_deleted', 'role'], 'string'],
            [['i_by', 'u_by'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['i_date', 'u_date'], 'safe']
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'i_by' => Yii::t('app', 'I By'),
            'i_date' => Yii::t('app', 'I Date'),
            'u_by' => Yii::t('app', 'U By'),
            'u_date' => Yii::t('app', 'U Date'),
        ];
    }
}
