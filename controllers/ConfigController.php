<?php
namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;


class ConfigController extends Controller
{
	
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
    	
    					]
    			],
    			[
    					'title' => 'Gallary',
    					'icon' => 'fa fa-picture-o',
    					'uri' => 'gallary/list',
    					'group' => [
    							'gallary/list',
    							'gallary/edit'
    					],
    					'sub'=>[],
    					'authen'=>[
    	
    					]
    			],
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
    									'icon'=>'',
    									'uri'=> Url::toRoute(["online/view"]),
    									'group'=> [
    											Url::toRoute(["online/view"]),
    									],
    							],
    							[
    									'title'=>'กาญจนบุรี',
    									'icon'=>'',
    									'uri'=>Url::toRoute(["online/view"]),
    									'group'=> [
    											Url::toRoute(["online/view"])
    									],
    							],
    							[
    									'title'=>'นครราชสีมา',
    									'icon'=>'',
    									'uri'=>Url::toRoute(["online/view"]),
    									'group'=> [
    											Url::toRoute(["online/view"])
    									],
    							],
    								
    					],
    					'authen'=>[
    	
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
    	
    					]
    			],
    	
    	];
    	return $arrMenu;
    }
	
}