<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tags".
 *
 * @property integer $id
 * @property string $value
 * @property integer $createBy
 * @property string $createTime
 * @property integer $lastUpdateBy
 * @property string $lastUpdateTime
 */
class Tags extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['createBy', 'lastUpdateBy'], 'integer'],
            [['createTime', 'lastUpdateTime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
            'createBy' => 'Create By',
            'createTime' => 'Create Time',
            'lastUpdateBy' => 'Last Update By',
            'lastUpdateTime' => 'Last Update Time',
        ];
    }
}
