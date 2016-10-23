<?php 
use yii\helpers\Html;

$baseUri = Yii::getAlias('@web');
$baseUriCss = $baseUri.'/assets/metronic';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title);?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<meta name="MobileOptimized" content="320">
	<!-- BEGIN GLOBAL MANDATORY STYLES -->          
	<link href="<?= $baseUriCss?>/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES --> 
	<link rel="stylesheet" type="text/css" href="<?= $baseUriCss?>/plugins/select2/select2_metro.css" />
	<!-- END PAGE LEVEL SCRIPTS -->
	<!-- BEGIN THEME STYLES --> 
	<link href="<?= $baseUriCss?>/css/style-metronic.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/css/style-responsive.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/css/plugins.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
	<link href="<?= $baseUriCss?>/css/pages/login.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/css/custom.css" rel="stylesheet" type="text/css"/>
	<!-- END THEME STYLES -->
	<link rel="shortcut icon" href="favicon.ico" />
	<?php $this->head() ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<?php $this->beginBody() ?>
	

	<?= $content?>

	<div class="copyright">
		2016 &copy; CMS by imake-web
	</div>
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->   
	<!--[if lt IE 9]>
	<script src="<?= $baseUriCss?>/plugins/respond.min.js"></script>
	<script src="<?= $baseUriCss?>/plugins/excanvas.min.js"></script> 
	<![endif]-->   
	<script src="<?= $baseUriCss?>/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript" ></script>
	<script src="<?= $baseUriCss?>/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/jquery.blockui.min.js" type="text/javascript"></script>  
	<script src="<?= $baseUriCss?>/plugins/jquery.cookie.min.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script src="<?= $baseUriCss?>/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>	
	<script type="text/javascript" src="<?= $baseUriCss?>/plugins/select2/select2.min.js"></script>     
	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="<?= $baseUriCss?>/scripts/app.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/scripts/login.js" type="text/javascript"></script> 

<?php $this->endBody() ?>
</body>
<!-- END BODY -->
</html>
<?php $this->endPage() ?>		
		
		