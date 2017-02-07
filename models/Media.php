<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "media".
 *
 * @property integer $id
 * @property string $fileName
 * @property string $realFilename
 * @property integer $createBy
 * @property string $createTime
 * @property integer $type
 * @property integer $refId
 * @property string $fullPath
 * @property string $thumbPath
 * @property string $srcPath
 * @property string $folderPath
 * @property integer $showInContent
 * @property integer $watermarkNo
 * @property string $caption
 * @property integer $isGallery
 */
class Media extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createBy', 'type', 'refId', 'showInContent', 'watermarkNo', 'isGallery'], 'integer'],
            [['createTime'], 'safe'],
            [['fileName', 'realFilename'], 'string', 'max' => 50],
            [['fullPath', 'folderPath'], 'string', 'max' => 500],
            [['thumbPath', 'srcPath'], 'string', 'max' => 900],
            [['caption'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fileName' => 'File Name',
            'realFilename' => 'Real Filename',
            'createBy' => 'Create By',
            'createTime' => 'Create Time',
            'type' => 'Type',
            'refId' => 'Ref ID',
            'fullPath' => 'Full Path',
            'thumbPath' => 'Thumb Path',
            'srcPath' => 'Src Path',
            'folderPath' => 'Folder Path',
            'showInContent' => 'Show In Content',
            'watermarkNo' => 'Watermark No',
            'caption' => 'Caption',
            'isGallery' => 'Is Gallery',
        ];
    }
}
