<?php
namespace app\controllers;

use \Yii;
use yii\web\Controller;

use app\models\User;
use app\lib\Auth;


class AuthController extends Controller {

	public function actionInit() {
		$auth = new Auth();
		$auth->init();
	}

	public function actionReassign() {
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

	public function actionIndex() {
		$user = new User();
        $user->username = 'it';
        $user->firstName = 'dev';
        $user->lastName = '';
        $user->role=Auth::ADMIN;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword('Fxicdi,gs,jv');
        $user->generateAuthKey();
        $user->save();
        
        $user = new User();
        $user->username = 'admin';
        $user->firstName = 'dev';
        $user->lastName = '';
        $user->role=Auth::ADMIN;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword('admin');
        $user->generateAuthKey();
        $user->save();
	}
}