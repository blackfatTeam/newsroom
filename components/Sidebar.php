<?php
namespace app\components;

use yii\base\Widget;

use app\lib\Conf;
use yii\helpers\Url;
use app\Auth;
use app\Workflow;
use app\OnlineConfig;
use app\controllers\ConfigController;

class Sidebar extends Widget {
	public function run() {

		$identity = \Yii::$app->user->getIdentity();
		$arrMenu = [];
		
		//default
		$arrMenuCountry = ['Online', 'Hot Topic'];
		foreach (ConfigController::getConfig() as $menu){
			$can = false;
			foreach($menu['authen'] as $authen){
				if(\yii::$app->user->can($authen)){
					$can = true;
					break;
				}
			}
			
			if($can){
				if (!in_array($menu['title'], $arrMenuCountry)){
					$arrMenu[] = $menu;
				}else{
					$arrCountry = [];
					foreach ($menu['sub'] as $lst){
						if (!empty($lst['titleEn'])){
							if ($lst['titleEn'] == $identity->web){
								$arrCountry[] = $lst;
							}
						}
					}
					unset($menu['sub']);
					$menu['sub'] = $arrCountry;
					$arrMenu[] = $menu;
				}
			}
		}

				
		echo $this->render('sidebar',[
				'arrMenu'=>$arrMenu
		]);
	}	
}

