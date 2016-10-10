<?php
namespace app\components;

use yii\base\Widget;

class Navigation extends Widget {
	public function run() {
		echo $this->render('navigation');
	}	
}