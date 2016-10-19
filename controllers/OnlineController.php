<?php
namespace app\controllers;
use yii\web\Controller;
use Yii;
use app\models\Contents;

use app\models\Media;

class OnlineController extends Controller{
    public function actionIndex()
    {
    
    	return $this->render('index');
    }
    
    public function actionEdit()
    {
    
    	return $this->render('edit');
    }
}
