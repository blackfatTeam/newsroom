<?php
use app\controllers\MediaController;
use yii\bootstrap\Html;
use app\lib\Workflow;

?>
<?php echo Html::img(MediaController::getUripreview([
		'width'=>100,
		'height'=>100,
		'wartermark'=>Workflow::WATER_MARK_1,
		'mediaId'=>4]),['class'=>'img-responsive'])?>

