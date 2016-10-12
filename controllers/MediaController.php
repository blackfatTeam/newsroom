<?php
namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Media;
use app\lib\Workflow;
use app\models\Contents;
use yii\base\Controller;
//use common\models\Background;
use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;
use app\models\Categories;
use yii\helpers\ArrayHelper;
//use common\models\Banner;
//use common\models\Staticpage;

class MediaController extends Controller
{
    public function actionIndex()
    {
    	$result = $this->doQuery(); 
    }
    public function actionSetthumbnail(){

    	$modelId = Yii::$app->request->post('modelId');
    	$imageId = Yii::$app->request->post('imageId');
    	$action = Yii::$app->request->post('action');
    	$type = Yii::$app->request->post('type');
    	$result = false;
    	 
    	if($type == Workflow::TYPE_CONTENT){
    		$model = Contents::find()->where(['id'=>$modelId])->one();
    	}elseif($type == workflow::TYPE_BANNER){
    		$model = Banner::find()->where(['id'=>$modelId])->one();
    	}elseif($type == workflow::TYPE_STATIC){
    		$model = Staticpage::find()->where(['id'=>$modelId])->one();
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
    
    		//$contents = Contents::find()->where(['id'=>$contId])->one();
    		$images = UploadedFile::getInstancesByName ('upload_ajax');
    		$param = ['images'=>$images,'modelId'=>$modelId,'type'=>$type];
    		$this->Uploads($param);
    	}
    }
    
    public function actionDeletefileajax() {

    	$id = Yii::$app->request->post ( 'key' );
    	$type = (int)Yii::$app->request->get( 'type' );
    
    	$r = Media::deletefile([$id]);    	
    	if ($r){	
    		if($type == Workflow::TYPE_CONTENT){			
		    	$arrContent = Contents::find()->where(['thumbnail'=>$id])->all();
		    	foreach($arrContent as $content){
		    		$content->thumbnail = null;
		    		$content->save();
		    	}
    		}
    		if($type == Workflow::TYPE_BANNER){
    			$arrBanner = Banner::find()->where(['thumbnail'=>$id])->all();
    			foreach($arrBanner as $banner){
    				$banner->thumbnail = null;
    				$banner->save();
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
    		if($type == Workflow::TYPE_CONTENT||$type==Workflow::TYPE_BANNER||$type==Workflow::TYPE_FEATURED){
    			$query->where(['refId'=>$modelId,'type'=>$type]);
    		}elseif($type == Workflow::TYPE_BACKGROUND_SECTION||$type == Workflow::TYPE_BACKGROUND_PAGE){
    			$query->where(['type'=>$type]);
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
    			Media::removeUploadDir($arrImgPath);
    			
    			if($type == Workflow::TYPE_CONTENT){
    				Media::deleteAll(['refId'=>$modelId,'type'=>$type]);
    				$contents = Contents::find()->where(['id'=>$modelId])->one();
    				if($contents){
    					$contents->thumbnail = null;
    					$contents->save();
    				}    				
    				
    			}elseif($type == Workflow::TYPE_BANNER){
    				Media::deleteAll(['refId'=>$modelId,'type'=>$type]);
    				$banner = Banner::find()->where(['id'=>$modelId])->one();
    				if($banner){
    					$banner->thumbnail = null;
    					$banner->save();
    				}    				
    				
    			}elseif($type == Workflow::TYPE_BACKGROUND_SECTION || $type == Workflow::TYPE_BACKGROUND_PAGE){
	    			Media::deleteAll(['type'=>$type]);
	    			$backGroundSection = Background::find()->all();
	    			foreach ($backGroundSection as $bg){
	    				$bg->mediaId = null;
	    				$bg->save();
	    			}
	    			
	    		}elseif($type == Workflow::TYPE_FEATURED){
	    			Media::deleteAll(['refId'=>$modelId,'type'=>$type]);
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
    	
    	if($type==Workflow::TYPE_CONTENT&&$id==null||$type==Workflow::TYPE_BANNER&&$id==null){
    		$models = null;
    	}

    	if($type == Workflow::TYPE_BACKGROUND_SECTION){
    		$section = ArrayHelper::map(Categories::find()->all(), 'id', 'title');
    		foreach ($models as $model){
    			$arrThumb = json_decode($model->thumbPath);
    			if($arrThumb == null){
    				$thumPath = '';
    				$fullPath = '';
    			}else{
    				$thumPath = $arrThumb->{'250'};
    				$fullPath = $arrThumb->{'full'};
    			}
    			
    			$query = Background::find();
    			$query->andWhere(['mediaId'=>$model->id]);
    			$inSection = [];
    			foreach ($query->all() as $bg){
    				$inSection[] = isset($section[$bg->section])?$section[$bg->section]:'';
    			}
    			$items[] = ['id'=>$model->id,'title'=>$model->fileName, 'thumbPath' => $thumPath,'fullPath'=>$fullPath, 'section'=>implode(',', $inSection)];
    		}
    	}else{
	    	foreach ($models as $model){
	    		$arrThumb = json_decode($model->thumbPath);
	    		if($arrThumb == null){
	    			$thumPath = '';
	    			$fullPath = '';
	    		}else{
	    			$thumPath = $arrThumb->{'250'};
	    			$fullPath = $arrThumb->{'full'};
	    			
	    		}
	    		
	    		$items[] = ['id'=>$model->id,'title'=>$model->fileName, 'thumbPath' => $thumPath,'fullPath'=>$fullPath];
	    	}
    	}
    	
    	header('Content-Type: application/json');
    	echo json_encode($items);
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
    
    	$name = $width.'_'.$fileName;
    	
    	//ลดขนาด 250 แก้ โหลดหลังบ้าน ช้า แต่ยังคง ชื่อ 250 ตามเดิม
    	if($width==Workflow::SIZE_LIT){
    		$width = 130;
    	}

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
    	$imgUpPath = Media::getUploadPath('img');
    	$imgUpUrl = Media::getUploadUrl('img');
    	 
    	 
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
    			Workflow::SIZE_LIT=>$imgUpUrl.'/'.Media::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_LIT.'_'.$realFileName,
    			Workflow::SIZE_MID=>$imgUpUrl.'/'.Media::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_MID.'_'.$realFileName,
    			Workflow::SIZE_FULL=>$imgUpUrl.'/'.$realFileName,
    			];
    			$jsonThumb = json_encode($arrThumb);
    			 
    			$arrSrcPath = [
    			'origin'=>$imgUpPath.'/'.$realFileName,
    			Workflow::SIZE_LIT=>$imgUpPath.'/'.Media::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_LIT.'_'.$realFileName,
    			Workflow::SIZE_MID=>$imgUpPath.'/'.Media::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_MID.'_'.$realFileName,
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
    				}elseif($type == Workflow::TYPE_BANNER){
    					//set thumbnail ตั้งต้นให้กับ banner
    					$banner = Banner::find()->where(['id'=>$modelId])->one();
    					if($banner!=null){
    						if($banner->thumbnail==null){
    							$banner->thumbnail = $model->id;
    							$banner->save();
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
	    	$imgUpPath = Media::getUploadPath('img');
	    	$imgUpUrl = Media::getUploadUrl('img');
	    	 
	    	 
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
	    			Workflow::SIZE_LIT=>$imgUpUrl.'/'.Media::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_LIT.'_'.$realFileName,
	    			Workflow::SIZE_MID=>$imgUpUrl.'/'.Media::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_MID.'_'.$realFileName,
	    			Workflow::SIZE_FULL=>$imgUpUrl.'/'.$realFileName,
	    			'old'=>$oldUrl,
	    		];
	    		$jsonThumb = json_encode($arrThumb);
	    			 
	    		$arrSrcPath = [
	    			'origin'=>$imgUpPath.'/'.$realFileName,
	    			Workflow::SIZE_LIT=>$imgUpPath.'/'.Media::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_LIT.'_'.$realFileName,
	    			Workflow::SIZE_MID=>$imgUpPath.'/'.Media::UPLOAD_THUMBNAIL_FOLDER.'/'.Workflow::SIZE_MID.'_'.$realFileName,
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