<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hottopic".
 *
 * @property integer $id
 * @property string $title
 * @property string $link
 * @property integer $orderNo
 * @property string $web
 * @property integer $status
 * @property integer $createBy
 * @property string $createTime
 * @property integer $lastUpdateBy
 * @property string $lastUpdateTime
 */
class Hottopic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hottopic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderNo', 'status', 'createBy', 'lastUpdateBy'], 'integer'],
            [['createTime', 'lastUpdateTime'], 'safe'],
            [['title', 'web'], 'string', 'max' => 50],
            [['link'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'link' => 'Link',
            'orderNo' => 'Order No',
            'web' => 'Web',
            'status' => 'Status',
            'createBy' => 'Create By',
            'createTime' => 'Create Time',
            'lastUpdateBy' => 'Last Update By',
            'lastUpdateTime' => 'Last Update Time',
        ];
    }
}
