<?php 
use yii\helpers\Html;
use app\components\Navigation;
use app\components\Message;
use app\components\Sidebar;
use yii\widgets\Breadcrumbs;


$baseUri = Yii::getAlias('@web');
$baseUriCss = $baseUri.'/assets/metronic';
$thumbnailTmp = '';//Media::getTmp();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
    <?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title);?></title>
	<meta http-equiv="Content-Type">
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<meta name="MobileOptimized" content="320">
	<!-- BEGIN GLOBAL MANDATORY STYLES -->          
	<link href="<?= $baseUriCss?>/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL PLUGIN STYLES --> 
	<link href="<?= $baseUriCss?>/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
	<link href="<?= $baseUriCss?>/plugins/fullcalendar/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?= $baseUriCss?>/plugins/select2/select2_metro.css" />
	<link rel="stylesheet" type="text/css" href="<?= $baseUriCss?>/plugins/bootstrap-toastr/toastr.min.css" />
	<!-- END PAGE LEVEL PLUGIN STYLES -->
	<!-- BEGIN THEME STYLES --> 
	<link href="<?= $baseUriCss?>/css/style-metronic.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/css/style-responsive.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/css/plugins.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/css/pages/tasks.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
	<link href="<?= $baseUriCss?>/css/custom.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/plugins/jquery-tags-input/jquery.tagsinput.css" rel="stylesheet" type="text/css"/>
	<link href="<?= $baseUriCss?>/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
	
	<!-- END THEME STYLES -->
	<link rel="shortcut icon" href="favicon.ico" />
	<script src="<?= $baseUriCss?>/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/jquery-tags-input/jquery.tagsinput.min.js" type="text/javascript" ></script>
	<script src="<?= $baseUri?>/assets/js/clipboard.min.js" type="text/javascript" ></script>
			   
	<script type="text/javascript">
		var baseUri = '<?= $baseUri?>';
		var thumbnailTmp = '<?= $thumbnailTmp?>';
	</script>
	<?php $this->head() ?>
</head>
<style>
.fa-check{
	color: #5eb51f !important;
}
.fa-pencil-square-o{
	color: #f79305 !important;
}
.fa-camera{
	color: #5eb51f !important;
}
table > thead > tr{
	background-color: #666666;
	color: #ffffff;
}
</style>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">
<?php $this->beginBody();?>
	<?= Navigation::widget()?>
	
	<div class="clearfix"></div>
	<!-- BEGIN CONTAINER -->
	<div class="page-container">
		<?= Sidebar::widget()?>
		<!-- BEGIN PAGE -->
		<div class="page-content">

		<?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

			<!-- BEGIN DASHBOARD STATS -->			
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12">
					<?= Message::widget()?>
					<?= $content?>
				</div>
			</div>
		</div>
		<!-- END PAGE -->
	</div>
	<!-- END CONTAINER -->
	<!-- BEGIN FOOTER -->
	<div class="footer">
		<div class="footer-inner">
			2016 &copy; CMS by imake-web
		</div>
		<div class="footer-tools">
			<span class="go-top">
			<i class="fa fa-angle-up"></i>
			</span>
		</div>
	</div>
	<!-- END FOOTER -->
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->   
	<!--[if lt IE 9]>
	<script src="<?= $baseUriCss?>/plugins/respond.min.js"></script>
	<script src="<?= $baseUriCss?>plugins/excanvas.min.js"></script> 
	<![endif]-->   
	
	<script src="<?= $baseUriCss?>/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>   
	<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="<?= $baseUriCss?>/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript" ></script>
	<script src="<?= $baseUriCss?>/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/jquery.blockui.min.js" type="text/javascript"></script>  
	<script src="<?= $baseUriCss?>/plugins/jquery.cookie.min.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script src="<?= $baseUriCss?>/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>   
	<script src="<?= $baseUriCss?>/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>  
	<script src="<?= $baseUriCss?>/plugins/flot/jquery.flot.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/flot/jquery.flot.resize.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>     
	<script src="<?= $baseUriCss?>/plugins/gritter/js/jquery.gritter.js" type="text/javascript"></script>
	<!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
	<script src="<?= $baseUriCss?>/plugins/fullcalendar/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/plugins/jquery.sparkline.min.js" type="text/javascript"></script>  
	<!-- END PAGE LEVEL PLUGINS -->
	
	<script src="<?= $baseUriCss?>/plugins/bootstrap-toastr/toastr.min.js"></script>  
	<!-- END PAGE LEVEL SCRIPTS -->     
	<script src="<?= $baseUriCss?>/scripts/ui-toastr.js"></script>  
	
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="<?= $baseUriCss?>/scripts/app.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/scripts/index.js" type="text/javascript"></script>
	<script src="<?= $baseUriCss?>/scripts/tasks.js" type="text/javascript"></script>     
	<script type="text/javascript" src="<?= $baseUriCss?>/plugins/fancybox/source/jquery.fancybox.pack.js"></script>  
	<script type="text/javascript" src="<?= $baseUriCss?>/plugins/select2/select2.min.js"></script>
	   
	<!-- END PAGE LEVEL SCRIPTS -->  
	<script>
		jQuery(document).ready(function() {    
		   App.init(); // initlayout and core plugins
		   UIToastr.init(); 
		});
	</script>
	
	<!-- <div id="fb-root"></div>
	<script>
	(function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "//connect.facebook.net/en_US/sdk.js";
	     fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));

	</script> -->
	
	<!-- ----facebook -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.5&appId=824973027544739";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	
	<!-- ----twitter -->
	<script>window.twttr = (function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0],
	    t = window.twttr || {};
	  if (d.getElementById(id)) return t;
	  js = d.createElement(s);
	  js.id = id;
	  js.src = "https://platform.twitter.com/widgets.js";
	  fjs.parentNode.insertBefore(js, fjs);
	 
	  t._e = [];
	  t.ready = function(f) {
	    t._e.push(f);
	  };
	 
	  return t;
	}(document, "script", "twitter-wjs"));</script>
		
<?php $this->endBody() ?>
</body>
<!-- END BODY -->
</html>
<?php $this->endPage() ?>