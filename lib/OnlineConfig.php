<?php

namespace app\lib;

use \Yii;
use app\Auth;
use app\lib\Workflow;

class OnlineConfig {
	
	public static $arrOnline = [
		[
			'title' => 'กรุงเทพ',
			'uri' => 'online/list',
		],
	];
	public static $arrSection = [
			[
					'section' => 'editorPick',
					'title' => 'บก. แนะนำ',
					'limit' => 6,
					'type' => [
						'content',
						'gallery'
					]

			],
			[
				'section' => 'panorama',
				'title' => 'ภาพสไลด์',
				'limit' => 6,
				'type' => [
						'content',
						'gallery'
				]
			],
			[
				'section' => 'columnPick',
				'title' => 'คอลัมน์แนะนำ',
				'limit' => 6,
				'type' => [
						'content',
						'gallery'
				]
			],
			[
				'section' => 'life',
				'title' => 'ไลฟ์สไตล์',
				'limit' => 6,
				'type' => [
						'content',
						'gallery'
				]
				
				],
			[
				'section' => 'social',
				'title' => 'สังคม',
				'limit' => 6,
				'type' => [
						'content',
						'gallery'
				]
			],
			[
				'section' => 'pol',
				'title' => 'การเมือง',
				'limit' => 6,
				'type' => [
						'content',
						'gallery'
				]
			],

				
	];


}