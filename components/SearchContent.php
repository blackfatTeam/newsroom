<?php
namespace app\components;
use app\models\Contents;
use app\models\Gallary;
use app\models\Category;
use yii\base\Widget;

use app\lib\Conf;
use yii\helpers\Url;
use app\lib\Auth;
use app\lib\Workflow;

class SearchContent extends Widget {
	public $section;
	public $arrId;
	public function run() {
		$categoryId = null;
		if (!empty($this->section)){
			$arrCategory = [1,2];
			if (!in_array($this->section, $arrCategory)){
				$categoryId = $this->section;
			}
		}
		
		$query = Contents::find();
		$query->orderBy('publishTime desc');
		$query->andWhere('theme =:theme', [':theme' => 1]);
		if (!empty($this->arrId)){
			$query->andWhere(['not in','id',$this->arrId]);
		}
		if (!empty($categoryId)){
			$query->andWhere('categoryId =:categoryId', [':categoryId' => $categoryId]);
		}
		$query->andWhere('status =:status', [':status' => Workflow::STATUS_PUBLISHED]);
		$query->limit(30);
		$contentList = $query->all();
		
		$queryGallery = Contents::find();
		$queryGallery->orderBy('publishTime desc');
		$queryGallery->andWhere('theme =:theme', [':theme' => 2]);
		if (!empty($this->arrId)){
			$queryGallery->andWhere(['not in','id',$this->arrId]);
		}
		if (!empty($categoryId)){
			$queryGallery->andWhere('categoryId =:categoryId', [':categoryId' => $categoryId]);
		}
		$queryGallery->andWhere('status =:status', [':status' => Workflow::STATUS_PUBLISHED]);
		$queryGallery->limit(30);
		$galleryList = $queryGallery->all();
		
		$findCategory = Category::find();
		$findCategory->andWhere('active =:active', [':active' => 1]);
		$resultCategory = $findCategory->all();
		
		$arrCategory = [];
		if (!empty($resultCategory)){
			foreach ($resultCategory as $lst){
				$arrCategory[$lst->id] = $lst->name;
			}
			
		}

		echo $this->render('searchContent', [
				'contentList' => $contentList,
				'galleryList' => $galleryList,
				'arrCategory' => $arrCategory,
				'section' => $this->section
		]);
	}	
}

