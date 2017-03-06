<?php
namespace app\controllers;
use yii\web\Controller;
use Yii;
use app\models\Contents;
use app\models\Category;
use app\models\Online;
use app\models\Gallary;
use app\lib\OnlineConfig;
use app\models\Media;
use app\lib\Workflow;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\lib\Auth;

class OnlineController extends Controller{
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
						//'only'=>[''],
						'rules'=>[
								[
									'allow'=> true,
									'roles'=>[
											
											//Auth::NEWS_MAN,
											Auth::ADMIN,
											Auth::EDITOR,
											//Auth::REWRITE
									]
								],
						]
				],
		];
	}
    public function actionView()
    {
    	$web = Yii::$app->request->get('web');
    	$baseUri = Yii::getAlias('@web');
    	$query = Online::find();
    	$query->andWhere('web = :web', [':web' => $web]);
    	$resultQuery = $query->all();
    	
    	$categoryQuery = Category::find();
    	$categoryQuery->andWhere('selected = :selected', [':selected' => 1]);
    	$resultCategory = $categoryQuery->all();
    	
    	$arrItem = [];
    	foreach ($resultCategory as $lst){
    		$arrItem[$lst->id] = [
    				'title' => $lst->name,
    				'categoryId' => $lst->id
    		];
    	}
    	if (!empty($resultQuery)){
    		foreach ($resultQuery as $lst){
    			if (!empty($lst->contentId)){
    				
    				$queryContent = Contents::find()->where(['id'=>$lst->contentId])->one();

    				if (!empty($queryContent)){
    					if(!empty($queryContent->thumbnail)){
    						$img = $this->getThumbnail($queryContent->thumbnail, $lst->type);
    					}else{
    						$img = '<img src="'.$baseUri.'/assets/img/no-thumbnail.jpg" class="img-responsive" width="80">';
    					}
    					
    					if(!empty($lst->categoryId)){
		    				$arrItem[$lst->categoryId]['data'][] = [
		    						'id' => $queryContent->id,
		    						'title' => $queryContent->title,
		    						'time' => $queryContent->publishTime?date('Y-m-d | H:i', strtotime($queryContent->publishTime)):'',
		    						'img' => $img
		    				];
    					}
    				}
    			}
    			
    			
    		}
    	}
    	//var_dump($arrItem);exit;
    	return $this->render('view', [
    			'web' => $web,
    			'arrItem' => $arrItem
    	]);
    }
    
    public function actionEdit()
    {
    	$baseUri = Yii::getAlias('@web');
    	$web = Yii::$app->request->get('web');
    	$section = Yii::$app->request->get('section');
    	//$sectionData = OnlineConfig::$arrSection[$section];
   
    	$categoryQuery = Category::find();
    	$categoryQuery->andWhere('selected = :selected', [':selected' => 1]);
    	$categoryQuery->andWhere('id = :id', [':id' => $section]);
    	$resultCategory = $categoryQuery->one();
    	$sectionData = $resultCategory->name?$resultCategory->name:'';
    	//$limit = OnlineConfig::$arrSection[$section]['limit'];
    	$arrColumn = [17,18,19,20];
    	$limit = 4;
    	if (in_array($section, $arrColumn)){
    		$limit = 1;
    	}

    	$arrContent = [];
    	
    	$totalCount = 0;
    	if (!empty($web) && !empty($section)){
	    	$query = Online::find();
	    	$query->andWhere('categoryId = :categoryId', [':categoryId' => $section]);
	    	$query->andWhere('web = :web', [':web' => $web]);
	    	//$query->orderBy('orderNo ASC');
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
	    					$img = '<img src="'.$baseUri.'/assets/img/no-thumbnail.jpg" class="img-responsive" width="80">';
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
		    			$totalCount = count($arrContent);
	    			}
	    		}
	    	}
    	}

    	
    	return $this->render('edit', [
    			'web' => $web,
    			'section' => $section,
    			'sectionData' => $sectionData,
    			'arrContent' => $arrContent,
    			'limit' => $limit,
    			'totalCount' => $totalCount
    	]);
    }
    
    public function getThumbnail($thumbnailId){
    	$baseUri = Yii::getAlias('@web');
    	$img = '<img src="'.$baseUri.'/assets/img/no-thumbnail.jpg" class="img-responsive" width="80">';
    
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
    		Online::deleteAll(['web'=>$web, 'categoryId' => $section]);
    	}
    	$identity = \Yii::$app->user->getIdentity();
    	
    	$count = count($arrId);
    	$result = 'บันทึกไม่สำเร็จ กรุณาลองใหม่อีกครั้ง';
    	$resultFact = 0;
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
    			$model->categoryId = $section;
    			$model->contentId = $id;
    			$model->orderNo = $i;
    			$model->date = date('Y-m-d');
    			$model->lastUpdateTime = date('Y-m-d H:i:s');
    			$model->lastUpdateBy = $identity->id;
    			$model->type = $type;
    			if ($model->save()){
    				if($count == $i){
    					$result = 'บันทึกข้อมูลสำเร็จแล้ว';
    					$resultFact = 1;
    				}
    				$i++;	
    			}
    			$n++;
    		}
    	}
    	header('Content-Type: application/json');
    	echo json_encode(array('result' => $result, 'resultFact' => $resultFact));
    }
}
