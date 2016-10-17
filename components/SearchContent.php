<?php
namespace app\components;
use app\models\Contents;

use yii\base\Widget;

use app\lib\Conf;
use yii\helpers\Url;
use app\Auth;
use app\Workflow;

class SearchContent extends Widget {
	public function run() {
		$query = Contents::find();
		$query->orderBy('publishTime desc');
		$contentList = $query->all();
		
		
		echo $this->render('searchContent', [
				'contentList' => $contentList
		]);
	}	
}

