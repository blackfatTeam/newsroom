<?php
namespace app\lib;

use \Yii;

class Auth {
	//role ตำแหน่ง
	const ADMIN = 'role.admin';

	
	//perm สิทธิ์ การกระทำ
	const USER_EDIT = 'user.edit';
	const USER_DELETE = 'user.delete';
	const CONTENT_EDIT = 'content.edit';
	const CONTENT_LIST = 'content.list';
	const CONTENT_DELETE = 'content.delete';
	

	
	private $arrPerm = [
			self::USER_EDIT=>'จัดการ user',
			self::USER_DELETE=>'ลบ user',
			self::CONTENT_EDIT =>'แก้ไข content',	
			self::CONTENT_LIST =>'ดูหน้า รายการ content',	
			self::CONTENT_DELETE =>'ลบ content',		
	];

	public static $arrUserRole = [
			self::ADMIN => 'admin',	
	
	];

	private $arrRolePerm = [
			self::ADMIN => [
					self::USER_EDIT,self::USER_DELETE,
					self::CONTENT_EDIT,	self::CONTENT_LIST,	self::CONTENT_DELETE,
		
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