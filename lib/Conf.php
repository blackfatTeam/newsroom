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
					'title' => 'Categories',
					'icon' => 'fa fa-sitemap',
					'uri' => 'categories/list',
					'group' => [ 
							'categories/list',
							'categories/edit' 
					],
					'sub'=>[],
					'authen'=>[
					]
			],
			[ 
					'title' => 'Editor Pick ',
					'icon' => 'fa fa-bookmark-o',
					'uri' => 'highlight/edit',
					'group' => [ 
							'highlight/edit' 
					],
					'sub'=>[],
					'authen'=>[
					]
			],
			[ 
					'title' => 'Section Image',
					'icon' => 'fa fa-picture-o',
					'uri' => 'background/section',
					'group' => [ 
							'background/section' 
					],
					'sub'=>[],
					'authen'=>[
					]
			],
			[ 
					'title' => 'Background Image',
					'icon' => 'fa fa-picture-o',
					'uri' => 'background/backpage',
					'group' => [ 
							'background/backpage' 
					],
					'sub'=>[],
					'authen'=>[
					]
			],
			[ 
					'title' => 'Instagram Celebrities',
					'icon' => 'fa fa-instagram',
					'uri' => 'instagram/list',
					'group' => [ 
							'instagram/list',
							'instagram/edit' 
					],
					'sub'=>[],
					'authen'=>[
					]
			],
			[
					'title' => 'Featured Video',
					'icon' => 'fa fa-toggle-right',
					'uri' => 'featured/edit',
					'group' => [
							'featured/edit'
					],
					'sub'=>[],
					'authen'=>[
					]
			],
			[
					'title' => 'Static',
					'icon' => 'fa fa-picture-o',
					'uri' => 'staticpage/list',
					'group' => [
							'staticpage/list',
							'staticpage/edit'
					],
					'sub'=>[],
					'authen'=>[]
			],
			[
					'title' => 'Banner',
					'icon' => 'fa fa-picture-o',
					'uri' => 'banner/list',
					'group' => [
							'banner/list',
							'banner/edit'
					],
					'sub'=>[],
					'authen'=>[
					]
			],
			
			

	];
}