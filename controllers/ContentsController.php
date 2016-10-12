<?php
namespace app\controllers;

use Yii;
use app\models\Contents;
use app\models\Categories;

use app\models\Media;

use yii\helpers\Url;

use app\lib\Workflow;
use app\models\Relation;

use yii\helpers\ArrayHelper;

use yii\data\Pagination;
use yii\base\Controller;

use app\models\Log;
//use common\models\Tags;

class ContentsController extends Controller
{
	public function actionTagapi() {
		$request = Yii::$app->request;
		
		$q = $request->post('q');
			if (empty($q))
				$q = $request->get('q');
			
		$query = Tags::find();
		if (!empty($q)) {
			$query->orWhere(['like', 'value', $q]);
		}
		$query->limit = 10;
		$lst = $query->all();
		
		$result = [];
		foreach($lst as $model) {
			$result[] = $model->getAttributes(['id', 'value']);
		}
		
		header('Content-Type: application/json');
		return json_encode($result);
	}
	public function actionInstagramapi(){
		$json = file_get_contents('http://api.instagram.com/oembed?url='.$_REQUEST['objectName']);

		if($json){
			header('Content-Type: application/json');
			echo $json;
		}else{
			return false;
		}
	}
	public function actionTwitterapi(){
		$json = file_get_contents('https://api.twitter.com/1/statuses/oembed.json?url='.$_REQUEST['objectName']);

		if($json){
			header('Content-Type: application/json');
			echo $json;
		}else{
			return false;
		}
	}

    public function actionEdit(){
    	$type = Workflow::TYPE_CONTENT;
    	$error=[];
    	//request
    	$id = Yii::$app->request->post('id');
		if(empty($id)){ 
			$id = Yii::$app->request->get('id');
		}
		$identity = \Yii::$app->user->getIdentity();
	   	//query
    	$contents = Contents::find()->where(['id'=>$id])->one();
    	if(empty($contents)){
    		$contents = new Contents();	

    	}	   	
    	   	
    	if(\Yii::$app->request->post()){

    		$reqstContents = Yii::$app->request->post('Contents');
    		$publicDate = Yii::$app->request->post('publishDate');
    		$publicTime = Yii::$app->request->post('publishTime');

    		$categoriesReq = Yii::$app->request->post('categories');    	

    		//เก็บ tags
    		$values = $reqstContents['tags'];
    		$arrV = explode(',', $values);
    		$key = array_search('', $arrV);
    		if($key!==false){
    			unset($arrV[$key]);
    		}
    		foreach ($arrV as $v){
    			$qtag = Tags::find()->where(['like','value',$v])->one();
    			if(empty($qtag)){
    				$ntag = new Tags();
    				$ntag->value = $v;
    				$ntag->createBy = '';//$identity->id;
    				$ntag->createTime = date('Y-m-d H:i:s',time());
    				$ntag->save();
    			}
    		}
   		
    		
    		
    		$action = '';
    		if(empty($contents->id)){
    			$action= Workflow::ACTION_CREATE;
    			$contents->createBy = '';//$identity->id;
    			$contents->createTime = date('Y-m-d H:i:s',time());
    			$contents->lastUpdateBy = '';//$identity->id;
    			$contents->lastUpdateTime = date('Y-m-d H:i:s',time());
    		}else{
    			$action= Workflow::ACTION_UPDATE;
    			$contents->lastUpdateBy = '';//$identity->id;
    			$contents->lastUpdateTime = date('Y-m-d H:i:s',time());
    		}
    		$contents->setAttributes($reqstContents, false);
    		$contents->publishTime = $publicDate.' '.$publicTime;

    	
    		if(!empty($reqstContents['postTitle'])){    			
    			$strPost = $reqstContents['postTitle'];
    		}else{
    			$strPost = $reqstContents['title'];
    		}    			
    		//$arrTmp = preg_split("/\/|\?|\=|\ |\-|\\\|\*|\./", $reqstContents['title']);
    		$arrTmp = preg_split("/[-!$%^&*\(\)_+|~=`{}\[\]:;\'<>?,.\"\/\\\ ]/", $strPost);
    		$strTmp = '';
    		foreach($arrTmp as $tmp){
    			if(!empty($tmp)){
    				if(empty($strTmp)){
    					$strTmp = strtolower($tmp);
    				}else{
    					$strTmp.='-'.strtolower($tmp);
    				}
    			}
    		}
    		$contents->postTitle = mb_substr($strTmp, 0,299,'UTF-8');
    		
    		
    		if($contents->save()){
    			//save category
	    		$r = Relation::deleteAll(['contentId'=>$contents->id]);   
	    		if($categoriesReq){
	    			foreach($categoriesReq as $id=>$cate){
	    				$model = new Relation();
	    				$model->categoryId = $id;
	    				$model->contentId = $contents->id;
	    				if(!$model->save()){
	    					$error[]=['relation'=>['id'=>$model->id]];
	    				}
	    
		    		}
	    			$contents->save();
	    		}else{
	    			$contents->save();
	    		}
	    		
	    		//set flag show in content
	    		$models = Media::find()->where(['refId'=>$contents->id])->all();
	    		foreach ($models as $model){
	    			$model->showInContent = null;
	    			$model->save();
	    		}	    		
	    		
	    		$content = $reqstContents['content'];
	    		$pattern = '/xxy\d*yxx/';	
	    		preg_match_all($pattern, $content, $arrTmp);

	    		foreach ($arrTmp[0] as $tmp){
	    			$pattern = '/x|y/';	
	    			$tmp2 = preg_split($pattern, $tmp);
	    			$id = implode('', $tmp2);
	    			
	    			$model = Media::findOne($id);
	    			if($model){
	    				$model->showInContent = 1;
	    				$model->save();
	    			}
	    		}
	    		
	    		//log
	    		$log = new Log();
	    		$log->action = $action;
	    		$log->type = Workflow::TYPE_CONTENT;
	    		$log->modelId = $contents->id;
	    		$log->userId = '';//$identity->id;
	    		$log->createBy = '';//$identity->id;
	    		$log->createTime = date('Y-m-d H:i:s',time());
	    		$log->description = json_encode([
					'id'=>$contents->id,
	    			'title'=>$contents->title,
	    			'table'=>'contents',
	    			'username'=>'',//$identity->username,
	    			"fullname"=>'',//$identity->firstName.' '.$identity->lastName,
	    		]);
	    		$log->save();
    		
    			Yii::$app->session->setFlash('message.level', 'success');
    			Yii::$app->session->setFlash('message.content', 'บันทึกข้อมูล');
	    		
    		}else{
    			$error[]=['content'=>['id'=>$contents->id]];
    			Yii::$app->session->setFlash('message.level', 'warning');
    			Yii::$app->session->setFlash('message.content', 'บันทึกไม่สำเร็จ');
    		}
    	}

    	//display
    	$categories = Categories::find()->where(['type'=>Workflow::TYPE_PARENT])->andWhere(['not',['id'=>0]])->all();
		$arrCategory = [];
		foreach ($categories as $cate){
			$tmp = Categories::find()->where(['parentId'=>$cate->id])->all();
			$arrCategory[] = ['parent'=>$cate,'sub'=>$tmp];
		}

    	
		$query = Categories::find();
		$query->innerJoin('relation', 'categories.id = relation.categoryId');
		$query->innerJoin('contents', 'contents.id = relation.contentId');
		$query->andWhere(['contents.id'=>$contents->id]);
		$liveInCate = yii\helpers\ArrayHelper::map($query->all(),'id','id');	
	

    	return $this->render('edit',[
    			'type'=>$type,
    			'contents'=>$contents,
      			'arrCategory'=>$arrCategory,
    			'liveInCate'=>$liveInCate,
    	]);
    }
    public function actionList(){
    	$identity = \Yii::$app->user->getIdentity();
     	if (Yii::$app->request->isPost) {
    		$op = Yii::$app->request->post('op');
    		$selectContent =  Yii::$app->request->post('selectContent');
    		if($op == 'delete'){
    			$contentsDelete = Contents::find()->where(['in','id',$selectContent])->all();
    			
    			$r = $this->doDelete($selectContent,Workflow::TYPE_CONTENT);
    			if($r){
	    			$detail = ArrayHelper::map($contentsDelete, 'id', 'title');
    				//log
    				foreach ($detail as $id=>$item){
    					$log = new Log();
			    		$log->action = Workflow::ACTION_DELETE;
			    		$log->type = Workflow::TYPE_CONTENT;
			    		$log->modelId = $id;
			    		$log->userId = '';//$identity->id;
			    		$log->createBy = '';//$identity->id;
			    		$log->createTime = date('Y-m-d H:i:s',time());
			    		$log->description = json_encode([
							'id'=>$id,
			    			'title'=>$item,
			    			'table'=>'contents',
			    			'username'=>$identity->username,
			    			"fullname"=>$identity->firstName.' '.$identity->lastName,
			    		]);
			    		$log->save();
    				}
		    		
		    		
    				Yii::$app->session->setFlash('message.level', 'success');
    				Yii::$app->session->setFlash('message.content', 'ลบสำเร็จ '.$r.' รายกาย');
    			}else{
    				Yii::$app->session->setFlash('message.level', 'warning');
    				Yii::$app->session->setFlash('message.content', 'ลบไม่สำเร็จ');
    			}
    		}else if($op == 'search'){
    			$title =  Yii::$app->request->post('title');
    			$status =  Yii::$app->request->post('status');
    			$category =  Yii::$app->request->post('category');
    			$publishTime =  Yii::$app->request->post('publishTime');

    			$query = Contents::find();
    			if($title!=null){
    				$query->andWhere(['like','title',$title]); 
    			}
    			if($status!=null){
    				$query->andWhere(['status'=>$status]);    	
    			}
    			if($publishTime!=null){
    				$query->andWhere(['like','publishTime',$publishTime]);    
    			}    			
    			if($category!=null){
    				$tmp = Relation::find()->where(['categoryId'=>$category])->all();
    				$arrId = ArrayHelper::map($tmp, 'contentId', 'contentId');
    				$query->andWhere(['in','id',$arrId]);
      				
    			}
    			\Yii::$app->session['contents/list.query'] = $query;
    			\Yii::$app->session['contents/list.query.title'] = $title;
    			\Yii::$app->session['contents/list.query.status'] = $status;
    			\Yii::$app->session['contents/list.query.publishTime'] = $publishTime;
    			\Yii::$app->session['contents/list.query.category'] = $category;
    			
    		}else if($op == 'resetSearch'){
    			$query = Contents::find();
    			\Yii::$app->session['contents/list.query'] = $query;
    			\Yii::$app->session['contents/list.query.title'] = '';
    			\Yii::$app->session['contents/list.query.status'] = '';
    			\Yii::$app->session['contents/list.query.publishTime'] = '';
    			\Yii::$app->session['contents/list.query.category'] = '';
    		}
    	}
    	
    	$query = isset(\Yii::$app->session['contents/list.query'])?\Yii::$app->session['contents/list.query']:Contents::find();
    	$search['title'] = isset(\Yii::$app->session['contents/list.query.title'])?\Yii::$app->session['contents/list.query.title']:'';
    	$search['status'] = isset(\Yii::$app->session['contents/list.query.status'])?\Yii::$app->session['contents/list.query.status']:'';
    	$search['category'] = isset(\Yii::$app->session['contents/list.query.category'])?\Yii::$app->session['contents/list.query.category']:'';
    	$search['publishTime'] = isset(\Yii::$app->session['contents/list.query.publishTime'])?\Yii::$app->session['contents/list.query.publishTime']:'';
    	
    	$query->orderBy('id DESC');
    	$count = $query->count();
    
		$pages = new Pagination([
				'defaultPageSize' => Workflow::PAGE_SIZE,
				'totalCount' => $count,
		]);

		$models = $query
		->offset($pages->offset)
		->limit($pages->limit)
		->all();
		
    	
    	$contentList = [];
    	foreach($models as $model){
    		$arrCategory=[];
    		foreach($model->getRelations()->all() as $cat){
    			$tmp = Categories::find()->where(['id'=>$cat->categoryId])->one();
    			$arrCategory[] = $tmp->getAttributes(['title'])['title'];
    		}
    		if(empty($arrCategory)){
    			$arrCategory[]='Uncategory';
    		}
    		
    		$amountImage = Media::find()->where(['refId'=>$model->id,'type'=>Workflow::TYPE_CONTENT])->count();
    		$contentList[]=$model->getAttributes()
    		+['categoryText'=>implode(',', $arrCategory)]
    		+['amountImage'=>$amountImage];
    	}

    	$query = Categories::find();
    	$categories = ArrayHelper::map($query->all(), 'id', 'title');

    	return $this->render('list',[
    		'pages'=>$pages,
    		'search'=>$search,
			'contentList'=>$contentList,
    		'categories'=>$categories,
    	]);
    }
	public function doDelete($arrContentId,$type){
		//delete file ที่อยู่ใน contents
		foreach($arrContentId as $conId){
			//find media
			$arrMedia = Media::findAll(['refId'=>$conId,'type'=>$type]); 
			$arrImgPath = [];
			foreach($arrMedia as $m){
				$path = $m->folderPath;
				if(!in_array($path, $arrImgPath)){
					$arrImgPath[]=$path;
				}
			}
			Media::removeUploadDir($arrImgPath);
			//clear images
			Media::deleteAll(['refId'=>$conId,'type'=>$type]);
		}
		
		//clear relation
		Relation::deleteAll(['in','contentId',$arrContentId]);
		//ลบ contents
		return Contents::deleteAll(['in','id',$arrContentId]);
	}
  
}