<?php
namespace app\controllers;
use yii\web\Controller;
use Yii;
use app\models\Contents;
use app\models\Online;
use app\lib\OnlineConfig;
use app\models\Media;
use app\lib\Workflow;

class OnlineController extends Controller{
    public function actionView()
    {
    	$web = Yii::$app->request->get('web');
    	
    	return $this->render('view', [
    			'web' => $web
    	]);
    }
    
    public function actionEdit()
    {
    	$web = Yii::$app->request->get('web');
    	$section = Yii::$app->request->get('section');
    	$sectionData = OnlineConfig::$arrSection[$section];
    	
    	$arrContent = [];
    	if (!empty($web) && !empty($section)){
	    	$query = Online::find();
	    	$query->andWhere('section = :section', [':section' => $section]);
	    	$query->andWhere('web = :web', [':web' => $web]);
	    	$query->orderBy('orderNo ASC');
	    	$arrModel = $query->all();
	    	
	    	if (!empty($arrModel)){
	    		foreach ($arrModel as $lst){
	    			if (!empty($lst->contentId)){
	    				$queryContent = Contents::find()->where(['id'=>$lst->contentId])->one();
	    			}
	    			if (!empty($queryContent)){
	    				
	    				if(!empty($queryContent->thumbnail)){
	    					$img = $this->getThumbnail($queryContent->thumbnail);
	    				}else{
	    					$img = '<img src="'.$baseUri.'/assets/img/no-thumbnail.jpg" class="img-responsive">';
	    				}
	    				
		    			$arrContent[] = [
		    					'id' => $queryContent->id,
		    					'title' => $queryContent->title,
		    					'publishTime' => $queryContent->publishTime,
		    					'status' => $queryContent->status,
		    					'img' => $img
		    			];
	    			}
	    		}
	    	}
    	}

    	
    	return $this->render('edit', [
    			'web' => $web,
    			'section' => $section,
    			'sectionData' => $sectionData,
    			'arrContent' => $arrContent
    	]);
    }
    
    public function getThumbnail($thumbnailId){
    	$baseUri = Yii::getAlias('@web');
    	$img = '<img src="'.$baseUri.'/assets/img/no-thumbnail.jpg" class="img-responsive">';
    
    	if (!empty($thumbnailId)){
    		$query = Media::find();
    		$query->andWhere('id = :id', [':id' => $thumbnailId]);
    		$result = $query->one();
    			
    		if (!empty($result->thumbPath)){
    			$arrThumb = json_decode($result->thumbPath);
    			if($arrThumb == null){
    				$thumPath = '';
    				$fullPath = '';
    			}else{
    				$thumPath = $arrThumb->{Workflow::SIZE_LIT};
    				$fullPath = $arrThumb->{'full'};
    			}
    
    
    			$img = '<img src="'.$thumPath.'" class="img-responsive" width="80">';
    		}
    	}
    
    	return $img;
    }
    
    public function actionSaveonline(){
    	$request = Yii::$app->request;
    	$web = $request->get('web');
    	$section = $request->get('section');
    	$arrId = $request->get('arrId');
    	Online::deleteAll(['web'=>$web, 'section' => $section]);
    	$identity = \Yii::$app->user->getIdentity();
    	
    	$count = count($arrId);
    	$result = 'บันทึกไม่สำเร็จ';
    	if (!empty($arrId) && !empty($web) && !empty($section)){
    		$i = 1;
    		foreach ($arrId as $id){
    			$model = new Online();
    			$model->web = $web;
    			$model->section = $section;
    			$model->contentId = $id;
    			$model->orderNo = $i;
    			$model->lastUpdateTime = date('Y-m-d H:i:s');
    			$model->lastUpdateBy = $identity->id;

    			if ($model->save()){
    				if($count == $i){
    					$result = 'บันทึกสำเร็จแล้ว';
    				}
    				$i++;
    					
    					
    			}
    		}
    	}
    	header('Content-Type: application/json');
    	echo json_encode($result);
    }
}
