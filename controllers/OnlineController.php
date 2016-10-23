<?php
namespace app\controllers;
use yii\web\Controller;
use Yii;
use app\models\Contents;

use app\models\Media;

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
    
    	return $this->render('edit');
    }
}
