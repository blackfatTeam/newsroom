<?php
return [
    'user.edit' => [
        'type' => 2,
        'description' => 'จัดการ user',
    ],
    'user.delete' => [
        'type' => 2,
        'description' => 'ลบ user',
    ],
    'content.edit' => [
        'type' => 2,
        'description' => 'แก้ไข content',
    ],
    'content.list' => [
        'type' => 2,
        'description' => 'ดูหน้า รายการ content',
    ],
    'content.delete' => [
        'type' => 2,
        'description' => 'ลบ content',
    ],
    'role.admin' => [
        'type' => 1,
        'description' => 'admin',
        'children' => [
            'user.edit',
            'user.delete',
            'content.edit',
            'content.list',
            'content.delete',
        ],
    ],
];
