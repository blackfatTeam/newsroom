<?php

namespace app\lib;

use \Yii;
use app\Auth;
class Conf {
	public static $arrMenu = [ 
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
				'title' => 'Online',
				'icon' => 'fa fa-globe',
				'uri' => 'online/index',
				'group' => [
						'online/index',
						'online/edit'
				],
				'sub'=>[],
				'authen'=>[
				
				]
			],
			
	];
	

}