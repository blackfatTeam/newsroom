<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


?>
<div class="form-group">
  	<label for="nameEn">Name Thai</label>
	<?php echo Html::activeInput('text', $node, 'name',['class'=>"form-control"])?>
</div>
<div class="form-group">
  	<label for="nameEn">Name English</label>
	<?php echo Html::activeInput('text', $node, 'nameEn',['class'=>"form-control"])?>
</div>
<div class="form-group">
  	<label for="limit">limit</label>
	<?php echo Html::activeInput('number', $node, 'limit',['class'=>"form-control"])?>
</div>
<div class="form-group">
  	<label for="highlight">Highlight</label>
	<?php echo Html::activeRadioList($node, 'highlight', [1=>'Yes',0=>'No'],['class'=>'radio-inline'])?>
</div>