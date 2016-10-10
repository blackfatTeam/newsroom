<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contents".
 *
 * @property integer $id
 * @property string $title
 * @property string $abstract
 * @property string $content
 * @property integer $status
 * @property string $tags
 * @property integer $thumbnail
 * @property string $publishTime
 * @property string $createTime
 * @property integer $createBy
 * @property string $lastUpdateTime
 * @property integer $lastUpdateBy
 * @property integer $theme
 * @property string $postTitle
 * @property string $credit
 * @property string $viewCount
 * @property string $expireTime
 * @property string $remark
 */
class Contents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['content'], 'string'],
            [['status', 'thumbnail', 'createBy', 'lastUpdateBy', 'theme', 'viewCount'], 'integer'],
            [['publishTime', 'createTime', 'lastUpdateTime', 'expireTime'], 'safe'],
            [['title', 'postTitle', 'credit'], 'string', 'max' => 300],
            [['abstract'], 'string', 'max' => 400],
            [['tags'], 'string', 'max' => 250],
            [['remark'], 'string', 'max' => 255],
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
            'abstract' => 'Abstract',
            'content' => 'Content',
            'status' => 'Status',
            'tags' => 'Tags',
            'thumbnail' => 'Thumbnail',
            'publishTime' => 'Publish Time',
            'createTime' => 'Create Time',
            'createBy' => 'Create By',
            'lastUpdateTime' => 'Last Update Time',
            'lastUpdateBy' => 'Last Update By',
            'theme' => 'Theme',
            'postTitle' => 'Post Title',
            'credit' => 'Credit',
            'viewCount' => 'View Count',
            'expireTime' => 'Expire Time',
            'remark' => 'Remark',
        ];
    }
}
