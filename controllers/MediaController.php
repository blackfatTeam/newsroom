<?php
namespace app\controllers;

use Yii;
use app\models\Media;
use app\lib\Workflow;
use app\models\Contents;

use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;
use app\models\Categories;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\Gallary;
use function Faker\boolean;
use yii\bootstrap\Html;
use app\lib\Auth;
use yii\filters\AccessControl;
use yii\filters\AccessRule;


class MediaController extends Controller
{
	public function beforeAction($event)
	{
		$this->enableCsrfValidation = false;
		return parent::beforeAction($event);
	}
	public function behaviors()
	{

		return [
				'access'=>[
						'class'=>AccessControl::className(),
						'ruleConfig'=>[
								'class'=>AccessRule::className()
						],
						'only'=>['setconfigmedia','setthumbnail','uploadajax','deletefileajax','manage'],
						'rules'=>[
								[
									'allow'=> true,
									'roles'=>[
											
											Auth::NEWS_MAN,
											Auth::ADMIN,
											Auth::EDITOR,
											Auth::REWRITE
									]
								],
						]
				],
		];
	}
	
	public function getWatermark($mark = null,$getAll = false){
		$arr = [];
		$arr[Workflow::WATER_MARK_NONE] = null;
		$arr[Workflow::WATER_MARK_1] = \Yii::getAlias('@webroot').'/assets/watermark/sample-trans1.png';
		$arr[Workflow::WATER_MARK_2] = \Yii::getAlias('@webroot').'/assets/watermark/sample-trans2.png';
	
		if($getAll){
			return $arr;
		}
		return $arr[$mark];
	}
	public function actionGenmedia(){
	
		$w = \Yii::$app->request->get('w');
		$h = \Yii::$app->request->get('h');
		$wtm = \Yii::$app->request->get('wtm');
		$mId = \Yii::$app->request->get('mId');
	
		$mediaId = $mId;
		$watermark = $wtm;
		$width =  $w;
		$height = $h;
	
	
		$media = Media::find()->where(['id'=>$mediaId])->one();
	
		if($media){
			$arrpathath = json_decode($media->srcPath);
			$originPath = $arrpathath->{'origin'};
	
			$image = Yii::$app->image->load($originPath);
	
	
			if(!empty($width)&&!empty($height)){
				$image->resize($width,$height,\yii\image\drivers\Image::CROP);
			}elseif(!empty($width)){
				$image->resize($width,$height,\yii\image\drivers\Image::WIDTH);
			}elseif(!empty($height)){
				$image->resize($width,$height,\yii\image\drivers\Image::HEIGHT);
			}
	
	
			if(!empty($watermark)){
				$watermarkSrc = MediaController::getWatermark($watermark);
				$watermarkFile = Yii::$app->image->load($watermarkSrc);
				$watermarkFile->resize($image->width,$image->height,\yii\image\drivers\Image::CROP);
				$image->watermark($watermarkFile, NULL, NULL, 50);
			}
	
			header("Content-Type: ".$image->mime);
			echo $image->render();
		}
	}
    public function actionIndex()
    {
    	$result = $this->doQuery(); 
    }

    public function actionSetconfigmedia(){
    	$result = true;
    	$data = Yii::$app->request->post('data');    	
    	  
    	//setting media detail
    	$mediaModel = Media::findOne(['id'=>$data['mediaId']]);
    	if($mediaModel){
    		$difWatermark = $mediaModel->watermarkNo;
    		$mediaModel->caption = $data['caption'];
    		$mediaModel->watermarkNo = $data['watermark'];
    		if($mediaModel->save()){
    			//ไม่ต้อง gen รูปลายน้ำเก็บไว้แล้ว เพราะมีฟังชัน getPreviewuri
    		}else{
    			$result = false;
    		}
    	}    	
    	
    	//set thumbnail
    	if($data['type'] == Workflow::TYPE_CONTENT){
    		$model = Contents::find()->where(['id'=>$data['modelId']])->one();
    	}elseif($data['type'] == workflow::TYPE_GALLARY){
    		$model = Gallary::find()->where(['id'=>$data['modelId']])->one();
    	}    

    	if(!empty($model)){
	    	if($data['isThumbnail']==="true"){
	    			$model->thumbnail = $data['mediaId'];
	    			if(!$model->save()){
	    				$result = false;
	    			}
	    	}else{
	    		if((int)$model->thumbnail === (int)$data['mediaId']){
	    			$model->thumbnail = '';
	    			if(!$model->save()){
	    				$result = false;
	    			}
	    		}
	    	}
    	}
    	header('Content-Type: application/json');
    	echo json_encode($result);
    }
    public function actionSetthumbnail(){

    	$modelId = Yii::$app->request->post('modelId');
    	$imageId = Yii::$app->request->post('imageId');
    	$action = Yii::$app->request->post('action');
    	$type = Yii::$app->request->post('type');
    	$result = false;
    	 
    	if($type == Workflow::TYPE_CONTENT){
    		$model = Contents::find()->where(['id'=>$modelId])->one();
    	}elseif($type == workflow::TYPE_GALLARY){
    		$model = Gallary::find()->where(['id'=>$modelId])->one();
    	}
    	
    	if(!empty($model)){
    		if($action=='set'){
    			$model->thumbnail = $imageId;
    		}else if($action=='clear'){
    			$model->thumbnail = '';
    		}
    		if($model->save()){
    			$result = true;
    		}
    	}
    	header('Content-Type: application/json');
    	echo json_encode($result);
    }
    public function actionUploadajax() {
    
    	if (Yii::$app->request->isPost) {
    
    		$modelId = Yii::$app->request->post('modelId');
    		$type = Yii::$app->request->post('type');
    
    		$images = UploadedFile::getInstancesByName ('upload_ajax');
    		$param = ['images'=>$images,'modelId'=>$modelId,'type'=>$type];
    		$this->Uploads($param);
    	}
    }
    
    public function actionDeletefileajax() {

    	$id = Yii::$app->request->post ( 'key' );
    	$type = (int)Yii::$app->request->get( 'type' );
    
    	$r = Workflow::deletefile([$id]);    	
    	if ($r){	
    		if($type == Workflow::TYPE_CONTENT){			
		    	$arrContent = Contents::find()->where(['thumbnail'=>$id])->all();
		    	foreach($arrContent as $content){
		    		$content->thumbnail = null;
		    		$content->save();
		    	}
    		}
    		echo json_encode ( [
    			'success' => true
    		] );
    
    	} else {
    		echo json_encode ( [
    				'success' => false
    				] );
    	}
    }
    
    public function actionManage(){

    	$op = Yii::$app->request->post('op');
    	$type = Yii::$app->request->post('type');
    	$modelId =  Yii::$app->request->post('modelId');
    	$currentAction =  Yii::$app->request->post('currentAction');
    	
    	if($op == 'deleteAllimg'){
    		
    		$query = Media::find();
    		if($type == Workflow::TYPE_CONTENT||$type==Workflow::TYPE_GALLARY){
    			$query->where(['refId'=>$modelId,'type'=>$type]);
    		}
    		$media = $query->all();

    		if(!empty($media)){
    			$arrImgPath = [];
    			foreach($media as $m){
    				$path = $m->folderPath;
    				if(!in_array($path, $arrImgPath)){
    					$arrImgPath[]=$path;
    				}
    			}
    			Workflow::removeUploadDir($arrImgPath);
    			
    			if($type == Workflow::TYPE_CONTENT){
    				Media::deleteAll(['refId'=>$modelId,'type'=>$type]);
    				$contents = Contents::find()->where(['id'=>$modelId])->one();
    				if($contents){
    					$contents->thumbnail = null;
    					$contents->save();
    				}    				
    				
    			}elseif($type == Workflow::TYPE_GALLARY){
    				Media::deleteAll(['refId'=>$modelId,'type'=>$type]);
    				$gallary = Gallary::find()->where(['id'=>$modelId])->one();
    				if($gallary){
    					$gallary->thumbnail = null;
    					$gallary->save();
    				}    				
    				
    			}
	    		
    			Yii::$app->session->setFlash('message.content', 'ลบสำเร็จ');
    		}
    	}

    	return $this->redirect($currentAction);
    }
    private function doQuery() {
    	$items = array();
    	$id = Yii::$app->request->post('id');
    	$type = Yii::$app->request->post('type');

    	$query = Media::find();
    	if($id!=null){
    		$query->where(['refId'=>$id]);
    	}
    	$query->andWhere(['type'=>$type]);
    	$models=$query->all();
    	
    	if($type==Workflow::TYPE_CONTENT&&$id==null){
    		$models = null;
    	}
    	foreach ($models as $model){
    		$arrThumb = json_decode($model->thumbPath);
    		if($arrThumb == null){
    			$thumPath = '';
    			$fullPath = '';
    		}else{
    			$thumPath = $arrThumb->{Workflow::SIZE_LIT};
    			$fullPath = $arrThumb->{'full'};
    			
    		}
    		
    		$items[] = ['id'=>$model->id,'title'=>$model->fileName, 'thumbPath' => $thumPath,'fullPath'=>$fullPath];
    	}

    	header('Content-Type: application/json');
    	echo json_encode($items);
    }
    private function CreateDir($basePath = null,$folderName) {
    	if($basePath == null){
    		$basePath = Workflow::getUploadPath();
    	}
    	if ($folderName != NULL) {
    		 
    		if (BaseFileHelper::createDirectory ( $basePath.'/'.$folderName, 0777 )) {
    			return true;
    		}
    	}
    	return false;
    }
    private function createThumbnail($imgUpPath, $fileName, $width = Workflow::SIZE_LIT) {
    
    	$this->CreateDir($imgUpPath,Workflow::UPLOAD_THUMBNAIL_FOLDER);
    	$uploadPath = $imgUpPath.'/'.Workflow::UPLOAD_THUMBNAIL_FOLDER;
    	$file = $imgUpPath .'/'. $fileName;
    
    	$name = $width.'_'.$fileName;
    
    	$image = Yii::$app->image->load($file);
    	$image->resize ( $width );
    
    	if($image->save( $uploadPath . '/' . $name )){
    		return true;
    	}
    	return false;
    }

    private function isImage($filePath) {
    	 
    	return @is_array( getimagesize($filePath)) ? true : false;
    }
   
    private function Uploads($param) {

    	$images = $param['images'];
    	$modelId = $param['modelId'];
    	$type = $param['type'];
    	
    	$identity = \Yii::$app->user->getIdentity();
    	$dateCreate = date('Y-m-d H:i:s',time());
    	$date = date('Ym',strtotime($dateCreate));
    	$imgUpPath = Workflow::getUploadPath('img');
    	$imgUpUrl = Workflow::getUploadUrl('img');
    	 
    	//สร้าง folder แยกตามวัน
    	$this->CreateDir($imgUpPath,$date);
    	$imgUpPath = $imgUpPath.'/'.$date;
    	$imgUpUrl = $imgUpUrl.'/'.$date;
    	//แยกตาม type
    	$this->CreateDir($imgUpPath,$type);
    	$imgUpPath = $imgUpPath.'/'.$type;
    	$imgUpUrl = $imgUpUrl.'/'.$type;
    	//แยกตาม id content
    	$this->CreateDir($imgUpPath,$modelId);
    	$imgUpPath = $imgUpPath.'/'.$modelId;
    	$imgUpUrl = $imgUpUrl.'/'.$modelId;

    	foreach ( $images as $index=>$file ) {
    		$fileName = $file->baseName . '.' . $file->extension;
    		$realFileName = md5($file->baseName . microtime()) . '.' . $file->extension;
    		$savePath = $imgUpPath.'/'.$realFileName;

    		if ($file->saveAs($savePath)){
    
    			if($this->isImage($imgUpUrl.'/'.$realFileName)) {

    				$this->createThumbnail($imgUpPath,$realFileName,Workflow::SIZE_LIT);
    				$this->createThumbnail($imgUpPath,$realFileName,Workflow::SIZE_MID);
    			}
    			$arrThumb = [
    				Workflow::SIZE_LIT=>$imgUpUrl.'/'.Workflow::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_LIT.'_'.$realFileName,
    				Workflow::SIZE_MID=>$imgUpUrl.'/'.Workflow::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_MID.'_'.$realFileName,
    				Workflow::SIZE_WATERMARK=>$imgUpUrl.'/'.Workflow::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_WATERMARK.'_'.$realFileName,
    				Workflow::SIZE_FULL=>$imgUpUrl.'/'.$realFileName,
    				
    			];
    			$jsonThumb = json_encode($arrThumb);
    			 
    			$arrSrcPath = [
    				'origin'=>$imgUpPath.'/'.$realFileName,
    				Workflow::SIZE_LIT=>$imgUpPath.'/'.Workflow::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_LIT.'_'.$realFileName,
    				Workflow::SIZE_MID=>$imgUpPath.'/'.Workflow::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_MID.'_'.$realFileName,
    				Workflow::SIZE_WATERMARK=>$imgUpPath.'/'.Workflow::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_WATERMARK.'_'.$realFileName,
    			];
    			$jsonSrcPath = json_encode($arrSrcPath);

    			$model = new Media();
    			$model->fileName = $fileName;
    			$model->realFilename = $realFileName;
    			$model->createBy = $identity->id;
    			$model->createTime = date('Y-m-d H:i:s',time());
    			$model->type = $type;
    			$model->refId = (int)$modelId;
    			$model->fullPath = $imgUpUrl.'/'.$realFileName;
    			$model->thumbPath = $jsonThumb;
    			$model->srcPath = $jsonSrcPath;
    			$model->folderPath= $imgUpPath;

    			if($model->save()){
    				if($type == Workflow::TYPE_CONTENT){
	    				//set thumbnail ตั้งต้นให้กับ content
	    				$contentModel = Contents::find()->where(['id'=>$modelId])->one();
	    				if($contentModel!=null){
	    					if($contentModel->thumbnail==null){
	    						$contentModel->thumbnail = $model->id;
	    						$contentModel->save();
	    					}
	    				}
    				}elseif($type == Workflow::TYPE_GALLARY){
    					//set thumbnail ตั้งต้นให้กับ gallary
    					$gallary = Gallary::find()->where(['id'=>$modelId])->one();
    					if($gallary!=null){
    						if($gallary->thumbnail==null){
    							$gallary->thumbnail = $model->id;
    							$gallary->save();
    						}
    					}
    				}
    			}
    
    			echo json_encode ( [
    					'success' => 'true'
    					] );
    
    		} else{
    			echo json_encode ( [
    					'success' => 'false',
    					'eror' => $file->error
    					] );
    
    		}
    	}
    }
    
	public function actionGetimage(){		
		set_time_limit(0);
		$error=[];
    	$query = Media::find()->where(['thumbPath'=>null]);
    	$query->andWhere(['not',['fullPath'=>null]]);

    	foreach($query->each() as $item){
			
	    	$modelId = $item->refId;
	    	$type = Workflow::TYPE_CONTENT;
	    	$oldUrl = $item->fullPath;
	    	
			$identity = \Yii::$app->user->getIdentity();
	    	$dateCreate = date('Y-m-d H:i:s',time());
	    	$date = date('Ym',strtotime($dateCreate));
	    	$imgUpPath = Workflow::getUploadPath('img');
	    	$imgUpUrl = Workflow::getUploadUrl('img');
	    	 
	    	 
	    	//สร้าง folder แยกตามวัน
	    	$this->CreateDir($imgUpPath,$date);
	    	$imgUpPath = $imgUpPath.'/'.$date;
	    	$imgUpUrl = $imgUpUrl.'/'.$date;
	    	//แยกตาม type
	    	$this->CreateDir($imgUpPath,$type);
	    	$imgUpPath = $imgUpPath.'/'.$type;
	    	$imgUpUrl = $imgUpUrl.'/'.$type;
	    	//แยกตาม id content
	    	$this->CreateDir($imgUpPath,$modelId);
	    	$imgUpPath = $imgUpPath.'/'.$modelId;
	    	$imgUpUrl = $imgUpUrl.'/'.$modelId;
			
			
	    	
    		$fileName = $item->fileName . '.' . 'jpg';
    		$realFileName = md5($item->fileName . microtime()) . '.' . 'jpg';
    		$savePath = $imgUpPath.'/'.$realFileName;
	    	
    		//getfile to tmp
    		if($this->isImage($item->fullPath)) {
	    		
	    	
		    	$content = file_get_contents($item->fullPath,1);
	
		    	
		    	
				$fp = fopen($savePath, "w");
				fwrite($fp, $content);
	
				$this->createThumbnail($imgUpPath,$realFileName,Workflow::SIZE_LIT);
	    		$this->createThumbnail($imgUpPath,$realFileName,Workflow::SIZE_MID);
	
				$arrThumb = [
	    			Workflow::SIZE_LIT=>$imgUpUrl.'/'.Workflow::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_LIT.'_'.$realFileName,
	    			Workflow::SIZE_MID=>$imgUpUrl.'/'.Workflow::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_MID.'_'.$realFileName,
	    			Workflow::SIZE_FULL=>$imgUpUrl.'/'.$realFileName,
	    			'old'=>$oldUrl,
	    		];
	    		$jsonThumb = json_encode($arrThumb);
	    			 
	    		$arrSrcPath = [
	    			'origin'=>$imgUpPath.'/'.$realFileName,
	    			Workflow::SIZE_LIT=>$imgUpPath.'/'.Workflow::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_LIT.'_'.$realFileName,
	    			Workflow::SIZE_MID=>$imgUpPath.'/'.Workflow::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_MID.'_'.$realFileName,
	    		];
	    		$jsonSrcPath = json_encode($arrSrcPath);
	    			
	
	    		//$item->fileName = $fileName;
	    		$item->realFilename = $realFileName;
	    		$item->createBy = $identity->id;
	    		$item->createTime = date('Y-m-d H:i:s',time());
	    		//$item->type = $type;
	    		//$item->refId = (int)$modelId;
	    		$item->fullPath = $imgUpUrl.'/'.$realFileName;
	    		$item->thumbPath = $jsonThumb;
	    		$item->srcPath = $jsonSrcPath;
	    		$item->folderPath= $imgUpPath;
				if($item->save()){
					//$tmpp[]=$oldUrl;
					/* $query2 = Contents::find()->where(['like',['content'=>$oldUrl]]);
					foreach($query2->each() as $tmp){
						$tmp->content = str_replace($oldUrl, $item->fullPath, $tmp->content);
						$tmp->save();
					} */
				}
    		}else{
    			$error[]=['id'=>$item->id,'fullPath'=>$item->fullPath];
    		}	    	
    	}
    	//var_dump($tmpp);
		
	}

	
}