<?php
namespace app\controllers;

use Yii;
use app\lib\Workflow;
use app\models\User;
use yii\data\Pagination;
use app\lib\Auth;
use yii\filters\AccessRule;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Province;
use yii\helpers\ArrayHelper;
use app\models\Category;


class UseronlyController extends Controller
{
	public function beforeAction($event)
	{
		$this->enableCsrfValidation = false;
		return parent::beforeAction($event);
	}
	public function reassign() {
		$appAuth = new Auth();		
		$auth = Yii::$app->authManager;
		$auth->removeAllAssignments();
		$query = User::find();
		foreach($query->all() as $model) {
			$role = $auth->getRole($model->role);
			if(!empty($role))
				$auth->assign($role, $model->id);
		}
	}
	public function actionEdit(){

		
    	//request
    	$id = Yii::$app->request->post('id');
		if(empty($id)){ 
			$id = Yii::$app->request->get('id');
		}
		$identity = \Yii::$app->user->getIdentity();
		
       	//query
    	$user = User::find()->where(['id'=>$id])->one();
    	if(empty($user)){
    		$user = new User();	
    	}	   	
    	   	
    	if(\Yii::$app->request->post()){
    		$op = Yii::$app->request->post('op');
	    	if($op == 'resetpass'){
	    		$password = '1234';
	    	}else{
	    		$password = Yii::$app->request->post('password');
	    	}
    		$reqstUser = Yii::$app->request->post('User');
			
    		if(empty($user->id)){
    			$user->createBy = $identity->id;
    			$user->createTime = date('Y-m-d H:i:s',time());
    			$user->lastUpdateBy = $identity->id;
    			$user->lastUpdateTime = date('Y-m-d H:i:s',time());
    		}else{
    			$user->lastUpdateBy = $identity->id;
    			$user->lastUpdateTime = date('Y-m-d H:i:s',time());
    		}
    		$user->setAttributes($reqstUser, false);
    		if($password!=null){
    			$user->setPassword($password);
    			$user->generateAuthKey();
    		}
    		if($user->save()){
    			$this->reassign();
    			Yii::$app->session->setFlash('message.level', 'success');
    			Yii::$app->session->setFlash('message.content', 'บันทึกข้อมูล');	    		
    		}else{
    			$error[]=['user'=>['id'=>$user->id]];
    			Yii::$app->session->setFlash('message.level', 'warning');
    			Yii::$app->session->setFlash('message.content', 'บันทึกไม่สำเร็จ');
    		}
    	}
    	
    	//display
    	$editUser = false;
    	$editContent = false;
    	$editPassword = false;
    	if(empty($user->id)){
    		if(\yii::$app->user->can(Auth::ADMIN)){
    			$editUser = true;
    			$editContent = true;
    			$editPassword = true;
    		}    		
    	}elseif($user->id==$identity->id){
    		$editContent = true;
    		$editPassword = true;
    	}elseif(\yii::$app->user->can(Auth::ADMIN)){
    		$editPassword = true;
    	}
    	
    	$viewStatus = false;
    	$viewRole = false;    	
    	if(\yii::$app->user->can(Auth::ADMIN)){
    		$viewStatus=true;
    		$viewRole = true;
    	}
    	
    	//จังหวัดนักข่าว
    	$provinces = [];
    	foreach(Workflow::$arrWeb as $key => $web){
    		$provinces[] = ['id'=>$key,'text'=>$web];
    	}
 
    	$arrSetting = [
			'edit.username'=>$editUser,
			'edit.content'=>$editContent,
			'edit.password'=>$editPassword,
			'view.status'=>$viewStatus,
			'view.role'=>$viewRole,
    	];

    	//get category
    	$query = Category::find()->where(['lvl'=>0]);
    	$models = $query->all();
    	$arrCate = [];
    	foreach ($models as $model){
    		$arrCate[$model->id] = $model->name;
    	}   	
		
    	return $this->render('edit',[
    			'arrSetting'=>$arrSetting,
    			'user'=>$user,
    			'provinces'=>$provinces,
    			'arrCate'=>$arrCate
    	]);
    }
}