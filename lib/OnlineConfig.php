<?php

namespace app\lib;

use \Yii;
use app\Auth;
use app\lib\Workflow;

class OnlineConfig {
	
	const ONLINE_EDITORPICK = 'editorpick';
	const ONLINE_PANORAMA = 'panorama';
	const ONLINE_COLUMNPICK = 'columnpick';
	const ONLINE_LIFE = 'life';
	const ONLINE_SOCIAL = 'social';
	const ONLINE_POL = 'pol';
	
	public static $arrSection = [
			self::ONLINE_EDITORPICK => [
					'section' => 'editorPick',
					'title' => 'บก. แนะนำ',
					'limit' => 6,
					'type' => [
						'content',
						'gallery'
					]

			],
			self::ONLINE_PANORAMA => [
				'section' => 'panorama',
				'title' => 'ภาพสไลด์',
				'limit' => 6,
				'type' => [
						'content',
						'gallery'
				]
			],
			self::ONLINE_COLUMNPICK => [
				'section' => 'columnPick',
				'title' => 'คอลัมน์แนะนำ',
				'limit' => 6,
				'type' => [
						'content',
						'gallery'
				]
			],
			self::ONLINE_LIFE => [
				'section' => 'life',
				'title' => 'ไลฟ์สไตล์',
				'limit' => 6,
				'type' => [
						'content',
						'gallery'
				]
				
				],
			self::ONLINE_SOCIAL => [
				'section' => 'social',
				'title' => 'สังคม',
				'limit' => 6,
				'type' => [
						'content',
						'gallery'
				]
			],
			self::ONLINE_POL => [
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