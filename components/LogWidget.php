<?php
namespace app\components;

use yii\base\Widget;
use app\models\Log;

class LogWidget extends Widget {
	public $type;
	public $table;
	public $id;
	public $view;
	public function run() {
		
		$query = Log::find();		
		if(!empty($this->type)){
			$query->where(['type'=>$this->type]);
		}
		if(!empty($this->id)){
			$query->andWhere(['modelId'=>$this->id]);
		}

		
		$models = $query->all();
		
		
		if($this->view == "content-edit"){
			if(empty($this->id)){
				$models = [];
			}
		}
		
		
		$results=[];
		foreach ($models as $model){
			
			$des = json_decode($model->description);
			
			$results[] = $model->getAttributes()+[
			'title'=>$des->{'title'},
			'table'=>$des->{'table'},
			'fullname'=>$des->{'fullname'},
			];
			
		}
		echo $this->render('log',[
			'results'=>$results,
			'type'=>$this->type,
		]);
	}	
}