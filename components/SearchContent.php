<?php
namespace app\components;
use app\models\Contents;
use app\models\Gallary;
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
		
		$queryGallery = Gallary::find();
		$queryGallery->orderBy('publishTime desc');
		$galleryList = $queryGallery->all();
		
		echo $this->render('searchContent', [
				'contentList' => $contentList,
				'galleryList' => $galleryList
		]);
	}	
}

