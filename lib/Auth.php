<?php
namespace app\lib;

use \Yii;

class Auth {
	//role ตำแหน่ง

	const ADMIN = 'role.admin';
	const NEWS_MAN = 'role.news_man';
	const REWRITE = 'role.rewrite';
	const EDITOR = 'role.editor';
	
	const REWRITE_CENTER = 'role.rewrite_center';
	const EDITOR_CENTER = 'role.editor_center';
	

	
	//perm สิทธิ์ การกระทำ
	const USER_LIST = 'user.list';
	const USER_EDIT = 'user.edit';
	const USER_DELETE = 'user.delete';
	
	const CONTENT_EDIT = 'content.edit';
	const CONTENT_LIST = 'content.list';
	const CONTENT_DELETE = 'content.delete';
	const CONTENT_LIST_ALL = 'content.list.all'; //ดู เนื้อหาทั้งหมด
	const CONTENT_EDIT_CONFIG = 'content.edit.config'; //ตั้งค่าเนื้อหา
	
	const CONTENT_VIEW_ALL = 'content.view.all'; //ดูทุกจังหวัด ทุกหมวด
	
	
	const GALLERY_EDIT = 'gallery.edit';
	const GALLERY_LIST = 'gallery.list';
	const GALLERY_DELETE = 'gallery.delete';

	const ONLINE_EDIT = 'online.edit';
	const ONLINE_LIST = 'online.list';
	const ONLINE_DELETE = 'online.delete';
	
	
	private $arrPerm = [
			self::USER_LIST=>'เห้นรายการ user',
			self::USER_EDIT=>'จัดการ user',
			self::USER_DELETE=>'ลบ user',
			
			self::CONTENT_EDIT =>'แก้ไข content',	
			self::CONTENT_LIST =>'ดูหน้า รายการ content',	
			self::CONTENT_DELETE =>'ลบ content',		
			self::CONTENT_LIST_ALL =>'ดูเนื้อหาทั้งหมด',
			self::CONTENT_EDIT_CONFIG => 'ใช้งาน tab ตั้งค่าเนื้อหา',
			self::CONTENT_VIEW_ALL => 'ดูข่าวทุกจังหวัด ทุกหมวด',
			
			self::GALLERY_EDIT =>'แก้ไข gallery',
			self::GALLERY_LIST =>'ดูหน้า รายการ  gallery',
			self::GALLERY_DELETE =>'ลบ gallery',
			
			self::ONLINE_EDIT =>'แก้ไข หน้าเลือกข่าว Online',
			self::ONLINE_LIST =>'ดูหน้า รายการ  เลือกข่าว Online',
			self::ONLINE_DELETE =>'ลบ รายการเลือกข่าว Online',
	];

	public static $arrUserRole = [
			self::ADMIN => 'Admin',	
			self::NEWS_MAN => 'นักข่าว',
			self::REWRITE => 'รีไรท์',
			self::EDITOR => 'บก.',
			self::REWRITE_CENTER => 'รีไรท์ กลาง',
			self::EDITOR_CENTER => 'บก. กลาง',
	];

	private $arrRolePerm = [
			self::ADMIN => [
					self::USER_EDIT,self::USER_LIST,self::USER_DELETE,
					self::CONTENT_EDIT,	self::CONTENT_LIST,	self::CONTENT_DELETE, self::CONTENT_LIST_ALL, self::CONTENT_EDIT_CONFIG,self::CONTENT_VIEW_ALL,					
					self::GALLERY_EDIT,	self::GALLERY_LIST,	self::GALLERY_DELETE,
					self::ONLINE_EDIT,	self::ONLINE_LIST,	self::ONLINE_DELETE,
			],			
			self::NEWS_MAN => [
					self::CONTENT_EDIT,	self::CONTENT_LIST,	self::CONTENT_DELETE,
			],
			self::REWRITE => [
					self::CONTENT_EDIT,	self::CONTENT_LIST,	self::CONTENT_DELETE,self::CONTENT_LIST_ALL,
					self::GALLERY_EDIT,	self::GALLERY_LIST,	self::GALLERY_DELETE,
			],
			self::EDITOR => [
					self::CONTENT_EDIT,	self::CONTENT_LIST,	self::CONTENT_DELETE, self::CONTENT_LIST_ALL, self::CONTENT_EDIT_CONFIG,
					self::GALLERY_EDIT,	self::GALLERY_LIST,	self::GALLERY_DELETE,
					self::ONLINE_EDIT,	self::ONLINE_LIST,	self::ONLINE_DELETE,
			],
			self::REWRITE_CENTER => [
					self::CONTENT_EDIT,	self::CONTENT_LIST,	self::CONTENT_DELETE,self::CONTENT_LIST_ALL,self::CONTENT_VIEW_ALL,
					self::GALLERY_EDIT,	self::GALLERY_LIST,	self::GALLERY_DELETE,
			],
			self::EDITOR_CENTER => [
					self::CONTENT_EDIT,	self::CONTENT_LIST,	self::CONTENT_DELETE, self::CONTENT_LIST_ALL, self::CONTENT_EDIT_CONFIG,self::CONTENT_VIEW_ALL,
					self::GALLERY_EDIT,	self::GALLERY_LIST,	self::GALLERY_DELETE,
					self::ONLINE_EDIT,	self::ONLINE_LIST,	self::ONLINE_DELETE,
			],

	];

	public function init() {
		$auth = Yii::$app->authManager;
		$auth->removeAll();
		foreach($this->arrPerm as $permName => $title) {
			$perm = $auth->createPermission($permName);
			$perm->description = $title;
			$auth->add($perm);
		}

		foreach(self::$arrUserRole as $roleName => $title) {
			$role = $auth->createRole($roleName);
			$role->description = $title;
			$auth->add($role);

			// assign role permission
			foreach($this->arrRolePerm[$roleName] as $permName) {
				$perm = $auth->getPermission($permName);
				$auth->addChild($role, $perm);
			}
		}
	}
}