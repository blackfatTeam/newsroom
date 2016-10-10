<?php
namespace app\components;

use yii\base\Widget;

class Message extends Widget {
	public function run() {
		if (\Yii::$app->session->hasFlash('message.content')) {
			$content = \Yii::$app->session->getFlash('message.content');
			$level = \Yii::$app->session->getFlash('message.level');
			if (empty($level)) $level = 'info';
		echo <<<EOT
<div class="alert alert-$level"><button class="close" data-dismiss="alert" type="button">×</button>$content</div>
EOT;
		}
	}	
}