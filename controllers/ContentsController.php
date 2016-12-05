<?php
namespace app\controllers;

use Yii;
use app\models\Contents;

use app\models\Media;

use app\models\Relatecontent;
use app\models\Gallary;
use yii\helpers\Url;

use app\lib\Workflow;

use yii\helpers\ArrayHelper;

use yii\data\Pagination;

use app\models\Log;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Tags;
use app\models\Online;
use yii\filters\AccessRule;
use app\lib\Auth;
use app\models\User;

class ContentsController extends Controller
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
	public function actionTest(){
		$t = Workflow::getWatermark();
		var_dump($t);exit;
		return $this->render('test');
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
    		
    		//ตั้งค่าจังหวัดเริ่มต้นตอนสร้างข่าว โดยใช้จังหวัดที่ประจำอยู่ของ user
    		if(!empty($identity->web)){
    			$web = explode(',', $identity->web);
    			$contents->web = isset($web[0])?$web[0]:'';
    		}    		

    	}	   	
    	 
    	//relateContent
    	$queryRelate = RelateContent::find()->where(['contentId'=>$id])->all();

    	$relateData = [];
    	if(!empty($queryRelate)){
    		foreach ($queryRelate as $lst){
    			
    			if ($lst->type == Workflow::TYPE_CONTENT){
    				$queryContent = Contents::find()->where(['id'=>$lst->relateId])->one();
    			}elseif ($lst->type == Workflow::TYPE_GALLARY){
    				$queryContent = Gallary::find()->where(['id'=>$lst->relateId])->one();
    			}

    			if (!empty($queryContent)){
    				 
	    			$relateType = 'content';
	    			if (!empty($lst->type)){
	    				switch ($lst->type){
	    					case Workflow::TYPE_CONTENT:
	    						$relateType = 'content';
	    						break;
	    					case Workflow::TYPE_GALLARY:
	    						$relateType = 'gallery';
	    						break;
	    				}
	    			}
	    			
	    			if(!empty($queryContent->thumbnail)){
	    				$img = $this->getThumbnail($queryContent->thumbnail, $relateType);
	    			}else{
	    				$img = '<img src="'.$baseUri.'/assets/img/no-thumbnail.jpg" class="img-responsive" width="80">';
	    			}
	    			
	    			$relateData[] = [
	    					'contentId' => $lst->contentId,
	    					'relateId' => $lst->relateId,
	    					'title' => $queryContent->title?$queryContent->title:'',
	    					'orderNo' => $lst->orderNo,
	    					'publishTime' => $queryContent->publishTime,
	    					'lastUpdateTime' => $lst->lastUpdateTime,
	    					'img' => $img,
	    					'type' => $relateType
	    			];
	    		}
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
    			$web =  Yii::$app->request->post('web');
    			$arrWeb = explode(',', $web);
    			
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
    			if($web!=null){
    				$query->andWhere(['in','web',$arrWeb]);
    			}

    			\Yii::$app->session['contents/list.query'] = $query;
    			\Yii::$app->session['contents/list.query.title'] = $title;
    			\Yii::$app->session['contents/list.query.status'] = $status;
    			\Yii::$app->session['contents/list.query.publishTime'] = $publishTime;
    			\Yii::$app->session['contents/list.query.web'] = $web;

    			
    		}else if($op == 'resetSearch'){
    			$query = Contents::find();
    			\Yii::$app->session['contents/list.query'] = $query;
    			\Yii::$app->session['contents/list.query.title'] = '';
    			\Yii::$app->session['contents/list.query.status'] = '';
    			\Yii::$app->session['contents/list.query.publishTime'] = '';
    			\Yii::$app->session['contents/list.query.web'] = '';
    		}
    	}
    	
    	$query = isset(\Yii::$app->session['contents/list.query'])?\Yii::$app->session['contents/list.query']:Contents::find();
    	$search['title'] = isset(\Yii::$app->session['contents/list.query.title'])?\Yii::$app->session['contents/list.query.title']:'';
    	$search['status'] = isset(\Yii::$app->session['contents/list.query.status'])?\Yii::$app->session['contents/list.query.status']:'';
    	$search['publishTime'] = isset(\Yii::$app->session['contents/list.query.publishTime'])?\Yii::$app->session['contents/list.query.publishTime']:'';
    	$search['web'] = isset(\Yii::$app->session['contents/list.query.web'])?\Yii::$app->session['contents/list.query.web']:'';
    	
    	//กำหนดสิทธิ์ให้เห้นเฉพาะคนที่สร้าง
    	if(!\yii::$app->user->can(Auth::CONTENT_LIST_ALL)){
    		$query->andWhere(['createBy'=>$identity->id]);
    	}    	
    	
    	
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
  			$creBy = User::findIdentity($model->createBy);
    		$amountImage = Media::find()->where(['refId'=>$model->id,'type'=>Workflow::TYPE_CONTENT])->count();
    		$contentList[]=$model->getAttributes()
    		+['amountImage'=>$amountImage]
    		+['createByStr'=>$creBy->firstName.' '.$creBy->lastName];
    	}
    	//display
    	//จังหวัดนักข่าว

    	$provinces = [];
    	foreach(Workflow::$arrWeb as $key => $web){
    		$provinces[] = ['id'=>$key,'text'=>$web];
    	}

    	return $this->render('list',[
    		'pages'=>$pages,
    		'search'=>$search,
			'contentList'=>$contentList,
    		'provinces'=>$provinces,
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
		
		//clear relate content
		Relatecontent::deleteAll(['in','contentId',$arrContentId,'type'=>Workflow::TYPE_CONTENT]);
		
		//clear online pick
		Online::deleteAll(['in','contentId',$arrContentId,'type'=>Workflow::TYPE_CONTENT]);
		
		//ลบ contents
		return Contents::deleteAll(['in','id',$arrContentId]);
	}

	public function actionGeneratecontent(){
		$request = Yii::$app->request;
		$q = $request->get('q')?$request->get('q'):'';
		$qGallery = $request->get('qGallery')?$request->get('qGallery'):'';
		$type = $request->get('type');
		
		if ($type == 'gallery'){
			$query = Gallary::find();
			$query->orWhere(['like', 'title', $qGallery]);
			$query->orWhere('id =:id', [':id' => $qGallery]);
		}else{
			$query = Contents::find();
			$query->orWhere(['like', 'title', $q]);
			$query->orWhere('id =:id', [':id' => $q]);
		}

		$query->limit(30);
		$query->orderBy('publishTime DESC');
		//$query->andWhere('status  = :status',[':status' => Workflow::STATUS_PUBLISHED]);
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
	
	public function actionResetcontent(){
		$request = Yii::$app->request;
		$type = $request->get('type')?$request->get('type'):'';
		
		if ($type == 'content'){
			$query = Contents::find();
		}else{
			$query = Gallary::find();
		}

		$query->limit(30);
		$query->orderBy('publishTime DESC');
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
		$type = $request->post('type');
		if(empty($id)){
			$id = $request->get('id');
			$type = $request->get('type');
		}
		if ($type == 'content'){
			$query = Contents::find()->where(['id'=>$id])->one();
		}elseif ($type == 'gallery'){
			$query = Gallary::find()->where(['id'=>$id])->one();
		}
		
		$result = [];
		if(!empty($query)){
			
			if(!empty($query->thumbnail)){
				$img = $this->getThumbnail($query->thumbnail, $type);
			}else{
				$img = '<img src="'.$baseUri.'/assets/img/no-thumbnail.jpg" class="img-responsive" width="80">';
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
	
	public function getThumbnail($thumbnailId, $type){
		$baseUri = Yii::getAlias('@web');
		$img = '<img src="'.$baseUri.'/assets/img/no-thumbnail.jpg" class="img-responsive" width="80">';
		
		if ($type == 'content'){
			$typeNo = Workflow::TYPE_CONTENT;
		}elseif ($type == 'gallery'){
			$typeNo = Workflow::TYPE_GALLARY;
		}
		
		if (!empty($thumbnailId)){
			$query = Media::find();
			$query->andWhere('id = :id', [':id' => $thumbnailId]);
			$query->andWhere('type = :type', [':type' => $typeNo]);
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
		$id = $request->get('id');
		$arrId = $request->get('arrId');
		$arrType = $request->get('arrType');
		$identity = \Yii::$app->user->getIdentity();
		Relatecontent::deleteAll(['contentId'=>$id]);
	
		$count = count($arrId);
		$result = 'บันทึกไม่สำเร็จ กรุณาลองใหม่อีกครั้ง';
		$resultFact = 0;
		if (!empty($arrId) && !empty($id)){
			$i = 1;
			$n = 0;
			foreach ($arrId as $relateId){
				
				$type = Workflow::TYPE_CONTENT;
				if ($arrType[$n] == 'gallery'){
					$type = Workflow::TYPE_GALLARY;;
				}
				$model = new Relatecontent();
				$model->contentId = $id;
				$model->relateId = $relateId;
				$model->orderNo = $i;
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
	/* public function actionTest(){

		$watermark =  Yii::$app->image->load('\www\newsroom\images\sample-trans1.png');
		$originModel = Media::findOne(34);
		$path = json_decode($originModel->srcPath);
		$file = $path->{'origin'};
		$origin = Yii::$app->image->load($file);
	
		$origin->watermark($watermark, NULL, NULL, 50);
	
		$origin->save( Workflow::getUploadPath('img'). '/test.png');
	} */
}