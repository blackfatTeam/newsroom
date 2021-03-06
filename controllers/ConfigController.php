<?php
namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\lib\Auth;
use app\lib\Workflow;


class ConfigController extends Controller
{
	public function beforeAction($event)
	{
		$this->enableCsrfValidation = false;
		return parent::beforeAction($event);
	}
    public static function getConfig(){
    	$arrMenu = [
    	
    			[
    					'title' => 'Contents',
    					'icon' => 'fa fa-edit',
    					'uri' => 'contents/list',
    					'group' => [
    							'contents/list',
    							'contents/edit'
    					],
    					'sub'=>[],
    					'authen'=>[
    						Auth::ADMIN,
    						Auth::NEWS_MAN,
    						Auth::REWRITE,
    						Auth::EDITOR,
    						Auth::REWRITE_CENTER,
    						Auth::EDITOR_CENTER
    					]
    			],
    			/* [
    					'title' => 'Gallary',
    					'icon' => 'fa fa-picture-o',
    					'uri' => 'gallary/list',
    					'group' => [
    							'gallary/list',
    							'gallary/edit'
    					],
    					'sub'=>[],
    					'authen'=>[
    						Auth::ADMIN,
    						
    						Auth::REWRITE,
    						Auth::EDITOR
    					]
    			], */
    			[
    					'title' => 'Online',
    					'icon' => 'fa fa-globe',
    					'uri' => 'online/view',
    					'group' => [
    							'online/view',
    							'online/edit'
    					],
    					'sub'=>[
    							[
    									'title'=>'กรุงเทพ',
    									'titleEn' => Workflow::CONTENT_WEB_1,
    									'icon'=>'',
    									'uri'=> Url::toRoute(["online/view", 'web' => 'bkk']),
    									'group'=> [
    											Url::toRoute(["online/view"]),
    									],
    							],
    							[
    									'title'=>'กาญจนบุรี',
    									'titleEn' => Workflow::CONTENT_WEB_2,
    									'icon'=>'',
    									'uri'=>Url::toRoute(["online/view", 'web' => 'kri']),
    									'group'=> [
    											Url::toRoute(["online/view"])
    									],
    							],
    							[
    									'title'=>'นครราชสีมา',
    									'titleEn' => Workflow::CONTENT_WEB_3,
    									'icon'=>'',
    									'uri'=>Url::toRoute(["online/view", 'web' => 'nma']),
    									'group'=> [
    											Url::toRoute(["online/view"])
    									],
    							],
    								
    					],
    					'authen'=>[
    							Auth::ADMIN,
    							Auth::EDITOR,
    							Auth::REWRITE_CENTER,
    							Auth::EDITOR_CENTER
    					]
    			],
    			[
    			
	    			'title' => 'Hot Topic',
	    			'icon' => 'fa fa-globe',
	    			'uri' => 'online/view',
	    			'group' => [
	    					'hottopic/edit',
	    			],
	    			'sub'=>[
    					[
    							'title'=>'กรุงเทพ',
    							'titleEn' => Workflow::CONTENT_WEB_1,
    							'icon'=>'',
    							'uri'=> Url::toRoute(["hottopic/edit", 'web' => 'bkk']),
    							'group'=> [
    									Url::toRoute(["hottopic/edit"]),
    							],
    					],
    					[
    							'title'=>'กาญจนบุรี',
    							'titleEn' => Workflow::CONTENT_WEB_2,
    							'icon'=>'',
    							'uri'=>Url::toRoute(["hottopic/edit", 'web' => 'kri']),
    							'group'=> [
    									Url::toRoute(["hottopic/edit"])
    							],
    					],
    					[
    							'title'=>'นครราชสีมา',
    							'titleEn' => Workflow::CONTENT_WEB_3,
    							'icon'=>'',
    							'uri'=>Url::toRoute(["hottopic/edit", 'web' => 'nma']),
    							'group'=> [
    									Url::toRoute(["hottopic/edit"])
    							],
    					],
    						
    			],
    			'authen'=>[
    					Auth::ADMIN,
    					Auth::EDITOR,
    					Auth::REWRITE_CENTER,
    					Auth::EDITOR_CENTER
    				]
    			],
    			[
    					'title' => 'User Management',
    					'icon' => 'fa fa-user',
    					'uri' => 'user/list',
    					'group' => [
    							'user/list',
    							'user/edit'
    					],
    					'sub'=>[],
    					'authen'=>[
    							Auth::ADMIN,
    					]
    			],
    			
    	
    	];
    	return $arrMenu;
    }
	
}