<?php
namespace app\lib;

class Workflow {
	const STATUS_REJECTED = -1;
	const STATUS_DRAFT = 2;
	const STATUS_PUBLISHED = 10;
	
	//type 
	const TYPE_CONTENT = 1;
	//const TYPE_HIGHLIGHT = 2;
	const TYPE_BACKGROUND_PAGE = 3;
	const TYPE_BACKGROUND_SECTION = 4;
	const TYPE_BANNER = 5;
	
	
	const CATEGORY_MAIN = 1;
	const CATEGORY_SUB = 2;
	
	//image size 
	const SIZE_LIT = 250;
	const SIZE_MID = 450;
	const SIZE_FULL = 'full';
	
	const ICON_THUMBNAIL = 'fa-camera';
	const PAGE_SIZE = 30;
	
	const PAGE_SIZE_CONTENT = 20;
	
	const BANNER_LEADERBOARD = 1;
	const BANNER_HIGHLIGHT = 2;
	const BANNER_WIDGET = 3;
	
	public static $arrBanner = array(
		self::BANNER_RECTANGLE => 'Rectangle Banner',
		self::BANNER_HIGHLIGHT => 'Highlight Banner',
		self::BANNER_WIDGET => 'Widget Banner'
	);
	
	public static $arrStatusTh = array(
			self::STATUS_DRAFT=>'กำลังแก้ไข',
			self::STATUS_PUBLISHED=>'แสดงผล',
			self::STATUS_REJECTED => 'ปิด',
	);

	public static $arrStatusIcon = array(
			self::STATUS_REJECTED => 'disable_icon.png',
			self::STATUS_DRAFT=>'abc.png',
			self::STATUS_PUBLISHED=>'enable_icon.png',
	);

	
	public static $arrStatusFaIcon = array(
			self::STATUS_DRAFT=> array('icon'=>'fa-pencil-square-o', 'css'=> 'draft'),
			self::STATUS_PUBLISHED=> array('icon'=>'fa-check', 'css'=> 'published'),
			self::STATUS_REJECTED => array('icon'=>'fa-lock', 'css'=> 'delete'),
	);
	
	public static $arrMediaType = array(
			self::TYPE_CONTENT=>'Contents',
			//self::TYPE_HIGHLIGHT=>'Highlight',
	);

	
	
}
