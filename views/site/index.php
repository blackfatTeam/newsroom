<?php

/* @var $this yii\web\View */

$this->title = 'CMS';
use yii\helpers\Url;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome to CMS</h1>

        <p class="lead">ยินดีต้อนรับเข้าสู่ระบบ CMS</p>

        <p><a class="btn btn-lg btn-success" href="<?= Url::toRoute('contents/list')?>">ไปยังหน้ารายการข่าว</a></p>
    </div>
  
</div>
