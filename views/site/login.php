<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm ;
$baseUri = Yii::getAlias('@web');
$baseUriCss = $baseUri.'/assets/metronic';
?>

<div class="logo">
	<img src="<?= $baseUriCss?>/img/logo-big.png" alt="" /> 
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
	<!-- BEGIN LOGIN FORM -->

	 <?php $form = ActiveForm::begin(['options'=>['class'=>'login-form']]);?>
		<h3 class="form-title">Login to your account</h3>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>Enter any username and password.</span>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">Username</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="LoginForm[username]"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="LoginForm[password]"/>
			</div>
		</div>
		<div class="form-actions">
			<label class="checkbox">
			<input type="checkbox" name="remember" value="1"/> Remember me
			</label>
			<button type="submit" class="btn green pull-right">
			Login <i class="m-icon-swapright m-icon-white"></i>
			</button>            
		</div>
	<?php ActiveForm::end(); ?>
</div>