<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property integer $id
 * @property integer $userId
 * @property string $action
 * @property integer $type
 * @property string $description
 * @property integer $modelId
 * @property string $createTime
 * @property integer $createBy
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'type', 'modelId', 'createBy'], 'integer'],
            [['createTime'], 'safe'],
            [['action'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'action' => 'Action',
            'type' => 'Type',
            'description' => 'Description',
            'modelId' => 'Model ID',
            'createTime' => 'Create Time',
            'createBy' => 'Create By',
        ];
    }
}
