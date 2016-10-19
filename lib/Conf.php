<?php

namespace app\lib;

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
				'sub'=> [
						[
								'title' => 'กรุงเทพ',
								'uri' => 'online/view',
						],
						[
								'title' => 'กาญจนบุรี',
								'uri' => 'online/view',
						],
						[
								'title' => 'นครราชสีมา',
								'uri' => 'online/view',
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
	

}