<?php
use yii\helpers\Url;
use common\models\User;
use yii\helpers\Html;
use app\Conf;
$identity = \Yii::$app->user->getIdentity();
$baseUri = \Yii::getAlias('@web');
$baseUriCss = $baseUri.'/assets/metronic';
$user = \Yii::$app->user;
$uri = Yii::$app->controller->getRoute();
?>
<!-- BEGIN SIDEBAR -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->        
			<ul class="page-sidebar-menu">
				<li>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler hidden-phone"></div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				<!-- BEGIN RESPONSIVE QUICK SEARCH FORM
				<li>					
					<form class="sidebar-search" action="extra_search.html" method="POST">
						<div class="form-container">
							<div class="input-box">
								<a href="javascript:;" class="remove"></a>
								<input type="text" placeholder="Search..."/>
								<input type="button" class="submit" value=" "/>
							</div>
						</div>
					</form>
				</li>			
				END RESPONSIVE QUICK SEARCH FORM -->
				<?php foreach ($arrMenu as $menu){
					$ch=in_array($uri,$menu['group']);
					$active = '';
					$select = '';
					if($ch){
						$active = 'active';
						$select = 'selected';
					}?>
				<li class="<?= $active?>">
					<a href="<?= Url::toRoute([$menu['uri']]);?>">
					<i class="<?= $menu['icon']?>"></i> 
					<span class="title"><?= $menu['title']?></span>
					<?php if(empty($menu['sub'])){?>
					<span class="<?= $select?>"></span>
					<?php }else{?>
					<span class="arrow "></span>
					<?php }?>
					</a>		
					
					<?php if(!empty($menu['sub'])){?>
						
						<ul class="sub-menu">
						<?php foreach($menu['sub'] as $sub){
						?>
							<li>
								<a href="<?= $sub['uri']?>"><?= $sub['title']?></a>
							</li>
						<?php }?>
						</ul>
					<?php }?>			
				</li>				
				<?php }?>
				
				
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
		<!-- END SIDEBAR -->