<?php
namespace app\controllers;

use Yii;
use app\models\Contents;

use app\models\Media;

use app\models\Relatecontent;

use yii\helpers\Url;

use app\lib\Workflow;

use yii\helpers\ArrayHelper;

use yii\data\Pagination;

use app\models\Log;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Tags;

class ContentsController extends Controller
{
	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'rules' => [
								[
										'allow' => true,
										'roles' => ['@'],
								],
						],
				],
				 
		];
	}
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
    	 
    	//relateContent
    	$queryRelate = RelateContent::find()->where(['contentId'=>$id])->all();
    	$relateData = [];
    	if(!empty($queryRelate)){
    		foreach ($queryRelate as $lst){
    			$query = Contents::find()->where(['id'=>$lst->relateId])->one();
    			
    			$relateData[] = [
    					'contentId' => $lst->contentId,
    					'relateId' => $lst->relateId,
    					'title' => $query->title?$query->title:'',
    					'orderNo' => $lst->orderNo,
    					'lastUpdateTime' => $lst->lastUpdateTime,
    			];
    		}
    	}
    	
    	if(\Yii::$app->request->post()){

    		$reqstContents = Yii::$app->request->post('Contents');
    		$publicDate = Yii::$app->request->post('publishDate');
    		$publicTime = Yii::$app->request->post('publishTime');

    		$expireDate = Yii::$app->request->post('expireDate');
    		$expireTime = Yii::$app->request->post('expireTime');
    		
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
    				$ntag->createBy = $identity->id;
    				$ntag->createTime = date('Y-m-d H:i:s',time());
    				$ntag->save();
    			}
    		}
   		
    		
    		
    		$action = '';
    		if(empty($contents->id)){
    			$action= Workflow::ACTION_CREATE;
    			$contents->createBy = $identity->id;
    			$contents->createTime = date('Y-m-d H:i:s',time());
    			$contents->lastUpdateBy = $identity->id;
    			$contents->lastUpdateTime = date('Y-m-d H:i:s',time());
    		}else{
    			$action= Workflow::ACTION_UPDATE;
    			$contents->lastUpdateBy = $identity->id;
    			$contents->lastUpdateTime = date('Y-m-d H:i:s',time());
    		}
    		$contents->setAttributes($reqstContents, false);
    		$contents->publishTime = $publicDate.' '.$publicTime;
    		$contents->expireTime = $expireDate.' '.$expireTime;

    	
    		if(!empty($reqstContents['postTitle'])){    			
    			$strPost = $reqstContents['postTitle'];
    		}else{
    			$strPost = $reqstContents['title'];
    		}    			
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
	    		$log->userId = $identity->id;
	    		$log->createBy = $identity->id;
	    		$log->createTime = date('Y-m-d H:i:s',time());
	    		$log->description = json_encode([
					'id'=>$contents->id,
	    			'title'=>$contents->title,
	    			'table'=>'contents',
	    			'username'=>$identity->username,
	    			"fullname"=>$identity->firstName.' '.$identity->lastName,
	    		]);
	    		$log->save();
    		
    			Yii::$app->session->setFlash('message.level', 'success');
    			Yii::$app->session->setFlash('message.content', 'บันทึกข้อมูล');
    			
    			return $this->redirect(['edit','id'=>$contents->id]);
    		}else{
    			$error[]=['content'=>['id'=>$contents->id]];
    			Yii::$app->session->setFlash('message.level', 'warning');
    			Yii::$app->session->setFlash('message.content', 'บันทึกไม่สำเร็จ');
    		}
    	}

    	return $this->render('edit',[
    			'type'=>$type,
    			'contents'=>$contents,
    			'relateData'=>$relateData
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
			    		$log->userId = $identity->id;
			    		$log->createBy = $identity->id;
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

    			\Yii::$app->session['contents/list.query'] = $query;
    			\Yii::$app->session['contents/list.query.title'] = $title;
    			\Yii::$app->session['contents/list.query.status'] = $status;
    			\Yii::$app->session['contents/list.query.publishTime'] = $publishTime;

    			
    		}else if($op == 'resetSearch'){
    			$query = Contents::find();
    			\Yii::$app->session['contents/list.query'] = $query;
    			\Yii::$app->session['contents/list.query.title'] = '';
    			\Yii::$app->session['contents/list.query.status'] = '';
    			\Yii::$app->session['contents/list.query.publishTime'] = '';
    		}
    	}
    	
    	$query = isset(\Yii::$app->session['contents/list.query'])?\Yii::$app->session['contents/list.query']:Contents::find();
    	$search['title'] = isset(\Yii::$app->session['contents/list.query.title'])?\Yii::$app->session['contents/list.query.title']:'';
    	$search['status'] = isset(\Yii::$app->session['contents/list.query.status'])?\Yii::$app->session['contents/list.query.status']:'';
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
  		
    		$amountImage = Media::find()->where(['refId'=>$model->id,'type'=>Workflow::TYPE_CONTENT])->count();
    		$contentList[]=$model->getAttributes()
    		+['amountImage'=>$amountImage];
    	}

    	return $this->render('list',[
    		'pages'=>$pages,
    		'search'=>$search,
			'contentList'=>$contentList,
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
			Workflow::removeUploadDir($arrImgPath);
			//clear images
			Media::deleteAll(['refId'=>$conId,'type'=>$type]);
		}
		
		//clear relation
		Relation::deleteAll(['in','contentId',$arrContentId]);
		//ลบ contents
		return Contents::deleteAll(['in','id',$arrContentId]);
	}

	public function actionGeneratecontent(){
		$request = Yii::$app->request;
		$q = $request->get('q');
		
		$query = Contents::find();
		$query->orWhere(['like', 'title', $q]);
		$query->orWhere('id =:id', [':id' => $q]);
		$query->andWhere('status  = :status',[':status' => Workflow::STATUS_PUBLISHED]);
		$resultQuery = $query->all();
		$baseUri = Yii::getAlias('@web');
		$result = [];
		if(!empty($resultQuery)){
			foreach ($resultQuery as $lst){
				$result[] = [
						'id' => $lst->id,
						'title' => $lst->title,
						'time' => $lst->publishTime?date('Y-m-d | H:i', strtotime($lst->publishTime)):'',
						'status' => '<img src="'.$baseUri.'/assets/img/'.Workflow::$arrStatusIcon[$lst->status].'"'
				];
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($result);
	}
	
	public function actionGetitem(){
		$request = Yii::$app->request;
		
		$baseUri = Yii::getAlias('@web');
		$id = $request->post('id');
		if(empty($id)){
			$id = $request->get('id');
		}
		$query = Contents::find()->where(['id'=>$id])->one();
		$result = [];
		if(!empty($query)){
			
			if(!empty($query->thumbnail)){
				$img = $this->getThumbnail($query->thumbnail);
			}else{
				$img = '<img src="'.$baseUri.'/assets/img/no-thumbnail.jpg" class="img-responsive">';
			}
			
			$result = [
					'id' => $query->id,
					'title' => $query->title,
					'img' => $img,
					'time' => $query->publishTime?date('Y-m-d | H:i', strtotime($query->publishTime)):'',
				
			];
		}

		header('Content-Type: application/json');
		echo json_encode($result);
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
	
	public function actionSaverelate(){
		$request = Yii::$app->request;
		$id = $request->post('id');
		$arrId = $request->post('arrId');
		$identity = \Yii::$app->user->getIdentity();
		Relatecontent::deleteAll(['contentId'=>$id]);
	
		$count = count($arrId);
		$result = 'บันทึกไม่สำเร็จ';
		if (!empty($arrId) && !empty($id)){
			$i = 1;
			foreach ($arrId as $relateId){
				$model = new Relatecontent();
				$model->contentId = $id;
				$model->relateId = $relateId;
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
	public function actionTest(){
		$originModel = Media::findOne(13);
		$watermarkModel = Media::findOne(14);
	
		$path = json_decode($originModel->srcPath);
		$file = $path->{'origin'};
		$origin = Yii::$app->image->load($file);
	
		$path = json_decode($watermarkModel->srcPath);
		$file = $path->{'origin'};
		$watermark = Yii::$app->image->load($file);
	
		$origin->watermark($watermark, NULL, NULL, 20);
	
		$origin->save( Workflow::getUploadPath('img'). '/test.png');
	}
}