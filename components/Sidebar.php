<?php
namespace app\components;

use yii\base\Widget;

use app\lib\Conf;
use yii\helpers\Url;
use app\Auth;
use app\Workflow;
use app\OnlineConfig;

class Sidebar extends Widget {
	public function run() {

		$identity = \Yii::$app->user->getIdentity();
		$arrMenu = [];
		
		//default
		foreach (Conf::$arrMenu as $menu){
			$can = false;
			$can = true;
			foreach($menu['authen'] as $authen){
				if(\yii::$app->user->can($authen)){
					$can = true;
					break;
				}
			}
			if($can){
				$arrMenu[] = $menu;
			}
		}
				
		echo $this->render('sidebar',[
				'arrMenu'=>$arrMenu
		]);
	}	
}

