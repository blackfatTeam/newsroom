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
			
	];
}