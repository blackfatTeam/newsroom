<?php
namespace app\components;

use yii\base\Widget;
use app\models\Media;
use app\lib\Workflow;

class Uploadfile extends Widget {
	public $modalId = 'modalUpload'; //id ของ modal ที่ใช้ set เวลาเรียก
	public $type;
	public $model;
	public $currentAction;
	//public $initialPreview;
	//public $initialPreviewConfig;
	
	public function run() {

		$initialPreview = [];
		$initialPreviewConfig = [];
		$query = Media::find ();
		
		if($this->model!=null){
			$query->andWhere(['refId'=>$this->model->id]);	
		}
		$query->andWhere(['type'=>$this->type]);
		
		$media = $query->all();

		list($initialPreview,$initialPreviewConfig) = Workflow::getInitialPreview($media,$this->type,$this->model);
		
		//var_dump($initialPreview);exit;
		echo $this->render('uploadfile',[
				'currentAction'=>$this->currentAction,
				'modalId'=>$this->modalId,
				'type'=>$this->type,
				'model'=>$this->model,
				'initialPreview'=>$initialPreview,
				'initialPreviewConfig'=>$initialPreviewConfig,
		]);
	}	
}