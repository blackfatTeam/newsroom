<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Tags;
use app\models\Hottopic;
use app\lib\Workflow;
use yii\filters\AccessRule;
use app\lib\Auth;


class HottopicController extends Controller
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
						//'only'=>[''],
						'rules'=>[
								[
									'allow'=> true,
									'roles'=>[
											
											//Auth::NEWS_MAN,
											Auth::ADMIN,
											Auth::EDITOR,
											//Auth::REWRITE
											Auth::REWRITE_CENTER,
											Auth::EDITOR_CENTER
									]
								],
						]
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
	
	public function actionEdit(){

		$identity = \Yii::$app->user->getIdentity();
		$web = Yii::$app->request->get('web');
		
		if(yii::$app->request->post()){
			$op = Yii::$app->request->post('op');
			$web = Yii::$app->request->post('web');
			
			if($op == 'order'){
				$orderItem = Yii::$app->request->post('orderItem');				
				$arrOrder = json_decode($orderItem);
				$r=[];
				foreach ($arrOrder as $order=>$id){
					$model = Hottopic::findOne($id);
					$model->orderNo = $order;
					$model->lastUpdateBy = $identity->id;
					$model->lastUpdateTime = date('Y-m-d H:i:s',time());
					$r[]=$model->save();
				}
				if(!in_array(false, $r)){
					Yii::$app->session->setFlash('message.level', 'success');
					Yii::$app->session->setFlash('message.content', 'บันทึกข้อมูล');
			
				}else{
					Yii::$app->session->setFlash('message.level', 'warning');
					Yii::$app->session->setFlash('message.content', 'บันทึกไม่สำเร็จ');
				}
			}elseif($op == 'add'){
				$title = Yii::$app->request->post('title');
				$tags = Yii::$app->request->post('tags');
				$status = Yii::$app->request->post('status');
				
				$tmp = Hottopic::find()->where(['web'=>$web])->orderBy('orderNo desc')->one();
				$num = !empty($tmp)?$tmp->orderNo+1:0;
				
				$model = new Hottopic();
				$model->title = $title;
				$model->tags = $tags;
				$model->orderNo = $num;
				$model->web = $web;
				$model->status = $status;
				$model->createBy = $identity->id;
				$model->createTime = date('Y-m-d H:i:s',time());
				$model->lastUpdateBy = $identity->id;
				$model->lastUpdateTime = date('Y-m-d H:i:s',time());
				
				if($model->save()){
					Yii::$app->session->setFlash('message.level', 'success');
					Yii::$app->session->setFlash('message.content', 'บันทึกข้อมูล');
						
				}else{
					Yii::$app->session->setFlash('message.level', 'warning');
					Yii::$app->session->setFlash('message.content', 'บันทึกไม่สำเร็จ');
				}
			}elseif($op == 'edit'){
				$id = Yii::$app->request->post('id');
				$title = Yii::$app->request->post('title');
				$tags = Yii::$app->request->post('tags');
				$status = Yii::$app->request->post('status');
								
				$model = Hottopic::findOne($id);

				if(!empty($model)){
					$model->title = $title;
					$model->tags = $tags;
					$model->status = $status;
					$model->lastUpdateBy = $identity->id;
					$model->lastUpdateTime = date('Y-m-d H:i:s',time());
					
					if($model->save()){
						Yii::$app->session->setFlash('message.level', 'success');
						Yii::$app->session->setFlash('message.content', 'บันทึกข้อมูล');
					
					}else{
						Yii::$app->session->setFlash('message.level', 'warning');
						Yii::$app->session->setFlash('message.content', 'บันทึกไม่สำเร็จ');
					}
				}else{
						Yii::$app->session->setFlash('message.level', 'warning');
						Yii::$app->session->setFlash('message.content', 'บันทึกไม่สำเร็จ');
					}
				
			}elseif($op == 'delete'){
				$id = Yii::$app->request->post('id');
				$r = Hottopic::deleteAll(['id'=>$id]);
				if($r){
					$arrHottopic = Hottopic::find()->where(['web'=>$web])->orderBy('orderNo asc')->all();
					$r=[];
					foreach ($arrHottopic as $order => $model){
						$model->orderNo = $order;
						$model->lastUpdateBy = $identity->id;
						$model->lastUpdateTime = date('Y-m-d H:i:s',time());
						$r[]=$model->save();
					}
					if(!in_array(false, $r)){
						Yii::$app->session->setFlash('message.level', 'success');
						Yii::$app->session->setFlash('message.content', 'บันทึกข้อมูล');
					
					}else{
						Yii::$app->session->setFlash('message.level', 'warning');
						Yii::$app->session->setFlash('message.content', 'บันทึกไม่สำเร็จ');
					}
				}else{
						Yii::$app->session->setFlash('message.level', 'warning');
						Yii::$app->session->setFlash('message.content', 'บันทึกไม่สำเร็จ');
				}
				
			}

		}
		$arrHottopic = Hottopic::find()->where(['web'=>$web])->orderBy('orderNo asc')->all();
		
		return $this->render('edit',[
				'arrHottopic'=>$arrHottopic,
				'web'=>$web,
		]);
	}
}