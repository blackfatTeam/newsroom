<?php
namespace app\lib;
use app\models\Media;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\helpers\BaseFileHelper;

class Workflow {
	/*------------------------------workflow----------------------------*/
	const STATUS_REJECTED = -1;
	const STATUS_DRAFT = 2;
	const STATUS_PUBLISHED = 10;
	
	//type media 
	const TYPE_CONTENT = 1;
	const TYPE_HIGHLIGHT = 2;  
	const TYPE_BACKGROUND_PAGE = 3;
	const TYPE_BACKGROUND_SECTION = 4;
	const TYPE_BANNER = 5;
	const TYPE_FEATURED = 6;	
	const TYPE_STATIC = 7;
	
	const STATIC_TYPE_MAGAZINE = 1;
	const STATIC_TYPE_LETTER = 2;
	
	const CATEGORY_MAIN = 1;
	const CATEGORY_SUB = 2;
	
	//image size 
	const SIZE_LIT = 250;
	const SIZE_MID = 450;
	const SIZE_FULL = 'full';
	
	const ICON_THUMBNAIL = 'fa-camera';
	const PAGE_SIZE = 30;
	
	const AD_PER_CONTENT = 5;
	
	const PAGE_SIZE_CONTENT = 20;
	
	const BANNER_LEADERBOARD = 1;
	const BANNER_HIGHLIGHT = 2;
	const BANNER_MIDDLE = 3;
	const BANNER_MIDDLE2 = 7;
	const BANNER_WIDGET = 4;
	const BANNER_WIDGET2 = 5;
	const BANNER_CONTENT = 6;
	
	const INSTAGRAM_ID = 253868709;
	const INSTAGRAM_TOKEN = '253868709.1677ed0.41b597999e314839a80fd7997f44e561';
	
	const META_TITLE = 'HiSoParty.com | The New Generation of High Society';
	const META_DESCRIPTION = '';
	const META_AUTHOR = 'www.hisoparty.com';
	
	const INSTAGRAM_CATEGORYID = 1000;
	const POST_URL = "www.hisoparty.com/content/";
	const POST_URL_UNCATE = "www.hisoparty.com/special/";
	
	const ACTION_CREATE = 'create';
	const ACTION_UPDATE = 'update';
	const ACTION_DELETE = 'delete';
	
	public static $arrAction = array(
		self::ACTION_CREATE=>self::ACTION_CREATE,
		self::ACTION_DELETE=>self::ACTION_DELETE,
		self::ACTION_UPDATE=>self::ACTION_UPDATE
	);
	
	public static $arrBanner = array(
		self::BANNER_LEADERBOARD => 'Leaderboard Banner',
		self::BANNER_HIGHLIGHT => 'Highlight Banner',
		self::BANNER_MIDDLE => 'Middle Banner',
		self::BANNER_MIDDLE2 => 'Middle Banner2',
		self::BANNER_WIDGET => 'Widget Banner',
		self::BANNER_WIDGET2 => 'Widget Banner2',
		self::BANNER_CONTENT => 'Content Banner'
	);
	
	public static $arrStatusTh = array(
			self::STATUS_DRAFT=>'กำลังแก้ไข',
			self::STATUS_PUBLISHED=>'แสดงผล',
			self::STATUS_REJECTED => 'ปิด',
	);

	public static $arrStatusIcon = array(
			self::STATUS_REJECTED => 'disable_icon.png',
			self::STATUS_DRAFT=>'abc.png',
			self::STATUS_PUBLISHED=>'enable_icon.png',
	);

	
	public static $arrStatusFaIcon = array(
			self::STATUS_DRAFT=> array('icon'=>'fa-pencil-square-o', 'css'=> 'draft'),
			self::STATUS_PUBLISHED=> array('icon'=>'fa-check', 'css'=> 'published'),
			self::STATUS_REJECTED => array('icon'=>'fa-lock', 'css'=> 'delete'),
	);
	
	public static $arrMediaType = array(
			self::TYPE_CONTENT=>'Contents',
			//self::TYPE_HIGHLIGHT=>'Highlight',
	);
	
	public static $arrTypeStaticPage = array(
			self::STATIC_TYPE_MAGAZINE => 'Magazine',
			self::STATIC_TYPE_LETTER => 'E-Letter'
	);

		
	/*------------------------------Contents----------------------------*/
	public static $theme = [
			1 => 'Full Panorama',
			2 => 'Tile Gallery',
	];
	
	/*------------------------------Media----------------------------*/
	const UPLOAD_FOLDER='uploads';
	const UPLOAD_IMAGES_FOLDER = 'images';
	const UPLOAD_THUMBNAIL_FOLDER ='thumbnail';
		
	/**
	 *
	 * @param string $ch รับ สองค่า   'img', 'thumb' สำหรับดึง path ที่เก็บรูปจริง และ path ของ thumbnail
	 * @return string path
	 */
	public static function getTmp(){
		return Yii::getAlias('@uploadUrl').'/tmp.png';
	}
	public static function getUploadPath($ch=''){
		if($ch=='img'){
			return \Yii::getAlias('@uploadPath');
		}
		return false;
	}
	
	public static function getUploadUrl($ch=''){
		if($ch=='img'){
			return \Yii::getAlias('@uploadUrl');
		}
		return false;
	}
	
	public function getPreviewGallery($path)
	{
		$arrPath = json_decode($path, true);
		$result = '';
		if ($arrPath[Workflow::SIZE_MID]) {
			$result = $arrPath[Workflow::SIZE_MID];
		}
	
		return $result;
	}
	
	public static function getInitialPreview($media,$type,$model = null){
	
		$initialPreview = [ ];
		$initialPreviewConfig = [ ];
	
		if($type == Workflow::TYPE_CONTENT || $type == Workflow::TYPE_BANNER || $type==Workflow::TYPE_STATIC){
	
			foreach ( $media as $key => $value ) {
				$setThumb = false;
				if($value->id == $model->thumbnail){
					$setThumb = true;
				}
				array_push ( $initialPreview, Workflow::getTemplatePreview ( $value,$type,$setThumb ) );
				array_push ( $initialPreviewConfig, [
						'caption' => $value->fileName,
						//'width' => '120px',
						//'height' => '120px',
						'url' => Url::to ( [
								'//media/deletefileajax?type='.$type
						] ),
						/* 'url' => Url::to ( [
						 '//media/deletefileajax'
						] ), */
						'key' => $value->id,
				] );
			}
		}else{
			foreach ( $media as $key => $value ) {
	
				array_push ( $initialPreview, Workflow::getTemplatePreview ($value,$type) );
				array_push ( $initialPreviewConfig, [
						'caption' => $value->fileName,
						//'width' => '120px',
						//'height' => '120px',
						'url' => Url::to ( [
								'//media/deletefileajax'
						] ),
						'key' => $value->id,
				] );
			}
		}
	
	
		return [
				$initialPreview,
				$initialPreviewConfig
		];
	}
	
	private static function getTemplatePreview(Media $model,$type,$setThumb = FALSE) {
	
		$arrThumb = json_decode($model->thumbPath);
		$thumPath = isset($arrThumb->{Workflow::SIZE_LIT})?$arrThumb->{Workflow::SIZE_LIT}:null;
	
		//$isImage = Media::isImage ( $thumPath );
		$isImage = 1;
		$modalId = '#modalPreview';
		if($type==Workflow::TYPE_CONTENT || $type==Workflow::TYPE_BANNER || $type==Workflow::TYPE_STATIC){
			$modalId = '#modalConfigImage';
		}
	
		if ($isImage) {
	
			$thumb='';
			$isThumb=0;
			if($setThumb){
				$isThumb=1;
				$thumb = '<button class="btn isThumb" style="position: absolute;top:1;background-color:#BE922A;"><i class="fa fa-picture-o"></i></button>';
			}

			$file = Html::img ( $thumPath, [
					'class' => 'file-preview-image imageThumb',
					'data-id' => $model->id,
					'data-isThumb' => $isThumb,
					'alt' => $model->realFilename,
					'title' => $model->fileName
			] );
	
			$file = '<a class="thumbPreview" data-toggle="modal" href="'.$modalId.'">'.
					$thumb.
					$file.'</a>';
	
		} else {
			$file = "<div class='file-preview-other'> " . "<h2><i class='glyphicon glyphicon-file'></i></h2>" . "</div>";
		}
		return $file;
	}
	public function isImage($filePath) {
	
		return @is_array( getimagesize($filePath)) ? true : false;
	}
	private function CreateDir($basePath = null,$folderName) {
		if($basePath == null){
			$basePath = Media::getUploadPath();
		}
		if ($folderName != NULL) {
			 
			if (BaseFileHelper::createDirectory ( $basePath.'/'.$folderName, 0777 )) {
				return true;
			}
		}
		return false;
	}
	
	private function createThumbnail($imgUpPath, $fileName, $width = 250) {
	
		$this->CreateDir($imgUpPath,Media::UPLOAD_THUMBNAIL_FOLDER);
		$uploadPath = $imgUpPath.'/'.Media::UPLOAD_THUMBNAIL_FOLDER;
		$file = $imgUpPath .'/'. $fileName;
	
	
		$image = Yii::$app->image->load($file);
		$image->resize ( $width );
	
		if($image->save( $uploadPath . '/' . $width.'_'.$fileName )){
			return true;
		}
		return false;
	}
	/**
	 *
	 * @param string $arrId รับ Array id ของ Media ที่ต้องการลบ
	 * @return number จำนวน media ที่ลบไป
	 */
	public static function deletefile($arrId = null){
		$r = 0;
		$models = Media::find()->where(['in','id',$arrId])->all();
		foreach($models as $model){
			$arrSrcPath = json_decode($model->srcPath);
			//set thumbnail คืนเป็น null กรณีลบรูปที่ set thumbnail ออก
			if ($model->delete()){
				if($arrSrcPath != null){
					foreach($arrSrcPath as $srcPaht){
						@unlink ( $srcPaht );
					}
				}
				$r++;
			}
		}
	
		return $r;
	}
	/**
	 *
	 * @param Array $path เป็น ชุดของ path ที่ต้องการจะลบ
	 */
	public static function removeUploadDir($path = null) {
		foreach($path as $dir){
			BaseFileHelper::removeDirectory($dir);
		}
	}
}
