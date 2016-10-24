<?php
namespace app\controllers;
use yii\web\Controller;
use Yii;
use app\models\Contents;
use app\models\Online;
use app\models\Gallary;
use app\lib\OnlineConfig;
use app\models\Media;
use app\lib\Workflow;

class OnlineController extends Controller{
    public function actionView()
    {
    	$web = Yii::$app->request->get('web');
    	OnlineConfig::$arrSection;
    	
    	$query = Online::find();
    	$query->andWhere('web = :web', [':web' => $web]);
    	$resultQuery = $query->all();
    	
    	$arrItem = [];
    	foreach (OnlineConfig::$arrSection as $key => $lst){
    		$arrItem[$key] = [];
    	}
    	if (!empty($resultQuery)){
    		foreach ($resultQuery as $lst){
    			if (!empty($lst->contentId)){
    				if ($lst->type == Workflow::TYPE_CONTENT){
    					$queryContent = Contents::find()->where(['id'=>$lst->contentId])->one();
    				}elseif ($lst->type == Workflow::TYPE_GALLARY){
    					$queryContent = Gallary::find()->where(['id'=>$lst->contentId])->one();
    				}
    				if (!empty($queryContent)){
    					if(!empty($queryContent->thumbnail)){
    						$img = $this->getThumbnail($queryContent->thumbnail, $lst->type);
    					}else{
    						$img = '<img src="'.$baseUri.'/assets/img/no-thumbnail.jpg" class="img-responsive">';
    					}
    					
	    				$arrItem[$lst->section][] = [
	    						'id' => $queryContent->id,
	    						'title' => $queryContent->title,
	    						'time' => $queryContent->publishTime?date('Y-m-d | H:i', strtotime($queryContent->publishTime)):'',
	    						'img' => $img
	    				];
    				}
    			}
    			
    			
    		}
    	}

    	return $this->render('view', [
    			'web' => $web,
    			'arrItem' => $arrItem
    	]);
    }
    
    public function actionEdit()
    {
    	$web = Yii::$app->request->get('web');
    	$section = Yii::$app->request->get('section');
    	$sectionData = OnlineConfig::$arrSection[$section];
    	$limit = OnlineConfig::$arrSection[$section]['limit'];
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
		    			if ($lst->type == Workflow::TYPE_CONTENT){
	    					$queryContent = Contents::find()->where(['id'=>$lst->contentId])->one();
	    				}elseif ($lst->type == Workflow::TYPE_GALLARY){
	    					$queryContent = Gallary::find()->where(['id'=>$lst->contentId])->one();
	    				}
	    			}
	    			if (!empty($queryContent)){
	    				
	    				if(!empty($queryContent->thumbnail)){
	    					$img = $this->getThumbnail($queryContent->thumbnail);
	    				}else{
	    					$img = '<img src="'.$baseUri.'/assets/img/no-thumbnail.jpg" class="img-responsive">';
	    				}
	    				
	    				$type = 'content';
	    				if (!empty($lst->type)){
		    				switch ($lst->type){
		    					case Workflow::TYPE_CONTENT:
		    						$type = 'content';
		    						break;
		    					case Workflow::TYPE_GALLARY:
		    						$type = 'gallery';
		    						break;
		    				}
	    				}
	    				
		    			$arrContent[] = [
		    					'id' => $queryContent->id,
		    					'title' => $queryContent->title,
		    					'publishTime' => $queryContent->publishTime,
		    					'status' => $queryContent->status,
		    					'img' => $img,
		    					'type' => $type
		    			];
	    			}
	    		}
	    	}
    	}

    	
    	return $this->render('edit', [
    			'web' => $web,
    			'section' => $section,
    			'sectionData' => $sectionData,
    			'arrContent' => $arrContent,
    			'limit' => $limit
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
    	$arrType = $request->get('arrType');
    	
    	if (!empty($web) && !empty($section)){
    		Online::deleteAll(['web'=>$web, 'section' => $section]);
    	}
    	$identity = \Yii::$app->user->getIdentity();
    	
    	$count = count($arrId);
    	$result = 'บันทึกไม่สำเร็จ';
    	if (!empty($arrId) && !empty($web) && !empty($section)){
    		$i = 1;
    		$n = 0;
    		foreach ($arrId as $id){
    			
    			$type = Workflow::TYPE_CONTENT;
    			if ($arrType[$n] == 'gallery'){
    				$type = Workflow::TYPE_GALLARY;;
    			}
    			
    			$model = new Online();
    			$model->web = $web;
    			$model->section = $section;
    			$model->contentId = $id;
    			$model->orderNo = $i;
    			$model->lastUpdateTime = date('Y-m-d H:i:s');
    			$model->lastUpdateBy = $identity->id;
    			$model->type = $type;
    			if ($model->save()){
    				if($count == $i){
    					$result = 'บันทึกสำเร็จแล้ว';
    				}
    				$i++;	
    			}
    			$n++;
    		}
    	}
    	header('Content-Type: application/json');
    	echo json_encode($result);
    }
}
