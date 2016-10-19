<?php
namespace app\controllers;
use yii\web\Controller;
use Yii;
use app\models\Contents;

use app\models\Media;

class OnlineController extends Controller{
    public function actionView()
    {
    
    	return $this->render('view');
    }
    
    public function actionEdit()
    {
    
    	return $this->render('edit');
    }
}
