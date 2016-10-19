<?php
use yii\helpers\Url;
use app\models\User;

$identity = \Yii::$app->user->getIdentity();
$baseUri = \Yii::getAlias('@web');
$baseUriCss = $baseUri.'/assets/metronic';
$user = \Yii::$app->user;


?>
<!-- BEGIN HEADER -->   
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="header-inner">
			<!-- BEGIN LOGO -->  
			<a class="navbar-brand" href="<?= Url::toRoute(['contents/list'])?>">
			<img src="<?= $baseUriCss?>/img/logo.png" alt="logo" class="img-responsive" />
			</a>
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER --> 
			<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<img src="<?= $baseUriCss?>/img/menu-toggler.png" alt="" />
			</a> 
			<!-- END RESPONSIVE MENU TOGGLER -->
			<!-- BEGIN TOP NAVIGATION MENU -->
			<ul class="nav navbar-nav pull-right">
				
				<!-- BEGIN USER LOGIN DROPDOWN -->
				<li class="dropdown user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<img alt="" src="<?= $baseUriCss?>/img/avatar1_small.jpg"/>
					<span class="username"><?= Yii::$app->user->identity->username;?></span>
					<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo Url::toRoute(['user/edit','id'=>$identity->id])?>"><i class="fa fa-user"></i> My Profile</a></li>
						<li><a href="<?php echo Url::toRoute('site/logout')?>"><i class="fa fa-key"></i> Log Out</a></li>
					</ul>
				</li>
				<!-- END USER LOGIN DROPDOWN -->
			</ul>
			<!-- END TOP NAVIGATION MENU -->
		</div>
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->