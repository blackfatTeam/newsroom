<?php

namespace app\lib;

use \Yii;
use app\lib\Auth;
use app\lib\Workflow;

class OnlineConfig {
	
	const ONLINE_ISSUES = 'issues';
	const ONLINE_HOTTOPIC = 'hottopic';
	const ONLINE_OTHER = 'other';
	const ONLINE_CSR = 'csr';
	const ONLINE_HOWTO = 'howto';
	const ONLINE_ADVERTISEMENT = 'advertisement';
	const ONLINE_HOME = 'home';
	
	public static $arrSection = [
			self::ONLINE_HOME => [
					'section' => 'home',
					'title' => 'ข่าวหน้าหลัก',
					'limit' => 3,
					'type' => [
							'content',
							'gallery'
					]
			
			],
			self::ONLINE_ISSUES => [
					'section' => 'issues',
					'title' => 'จุดประเด็น',
					'limit' => 15,
					'type' => [
						'content',
						'gallery'
					]

			],
			self::ONLINE_HOTTOPIC => [
				'section' => 'hotTopic',
				'title' => 'เกาะกระแส',
				'limit' => 15,
				'type' => [
						'content',
						'gallery'
				]
			],
			self::ONLINE_OTHER => [
				'section' => 'other',
				'title' => 'ปกิณกะ',
				'limit' => 15,
				'type' => [
						'content',
						'gallery'
				]
			],
			self::ONLINE_CSR => [
				'section' => 'csr',
				'title' => 'Corporate Social Responsibility',
				'limit' => 15,
				'type' => [
						'content',
						'gallery'
				]
				
				],
			self::ONLINE_HOWTO => [
				'section' => 'howTo',
				'title' => 'Howto',
				'limit' => 15,
				'type' => [
						'content',
						'gallery'
				]
			],
			self::ONLINE_ADVERTISEMENT => [
				'section' => 'ads',
				'title' => 'Advertisement',
				'limit' => 15,
				'type' => [
						'content',
						'gallery'
				]
			],

				
	];


}