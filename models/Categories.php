<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property string $title
 * @property integer $status
 * @property string $thumbnail
 * @property integer $parentId
 * @property integer $type
 * @property string $section
 * @property string $createTime
 * @property integer $createBy
 * @property string $lastUpdateTime
 * @property integer $lastUpdateBy
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['status', 'parentId', 'type', 'createBy', 'lastUpdateBy'], 'integer'],
            [['createTime', 'lastUpdateTime'], 'safe'],
            [['title'], 'string', 'max' => 300],
            [['thumbnail', 'section'], 'string', 'max' => 100],
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
            'status' => 'Status',
            'thumbnail' => 'Thumbnail',
            'parentId' => 'Parent ID',
            'type' => 'Type',
            'section' => 'Section',
            'createTime' => 'Create Time',
            'createBy' => 'Create By',
            'lastUpdateTime' => 'Last Update Time',
            'lastUpdateBy' => 'Last Update By',
        ];
    }
}
