<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

class CategoryController extends Controller {
    public function actionIndex()
    {
        return $this->render('index');
    }
}