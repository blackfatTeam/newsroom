<?php
namespace app\components;

use yii\base\Widget;

class TinyMCE extends Widget {
	public function run() {
		echo $this->render('tinymce');
	}	
}