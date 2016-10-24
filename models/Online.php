<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "online".
 *
 * @property integer $id
 * @property string $section
 * @property integer $contentId
 * @property string $web
 * @property integer $orderNo
 * @property string $lastUpdateTime
 * @property integer $lastUpdateBy
 * @property integer $type
 */
class Online extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'online';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section', 'contentId', 'web', 'orderNo'], 'required'],
            [['contentId', 'orderNo', 'lastUpdateBy', 'type'], 'integer'],
            [['lastUpdateTime'], 'safe'],
            [['section', 'web'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'section' => 'Section',
            'contentId' => 'Content ID',
            'web' => 'Web',
            'orderNo' => 'Order No',
            'lastUpdateTime' => 'Last Update Time',
            'lastUpdateBy' => 'Last Update By',
            'type' => 'Type',
        ];
    }
}
