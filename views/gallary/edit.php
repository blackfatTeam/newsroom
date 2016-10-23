<?php
use yii\bootstrap\Html;
use yii\base\View;
use yii\widgets\ActiveForm;

use yii\helpers\Url;
use app\components\TinyMCE;
use app\lib\Workflow;
use app\components\Uploadfile;
use app\components\LogWidget;

$baseUri = Yii::getAlias('@web');
$postUrl = empty($liveInCate)?Workflow::POST_URL_UNCATE:Workflow::POST_URL;

$str = <<<EOT
	 new Clipboard('#copy-button');
	
EOT;

$this->registerJs($str);
$this->registerJsFile($baseUri.'/assets/tinymce/tinymce.min.js');

$css = <<<EOT

EOT;
$this->registerCss($css);
$this->title = $contents->title;

$this->params['breadcrumbs'][] = ['label' => 'list', 'url' => ['gallary/list']];
$this->params['breadcrumbs'][] = ['label' => $contents->title];
?>


<?php $form = ActiveForm::begin(['id'=>'contentForm','options' => ['enctype' => 'multipart/form-data','method'=>'post']]); ?>

<div class="col-md-12">
	<div class="portlet box grey tabbable" id="content-main" data-id="<?= $contents->id?>" data-type="<?= $type?>">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-reorder"></i>
				Content
			</div>
		</div>
		<div class="portlet-body">
			<div class=" portlet-tabs">
				<ul class="nav nav-tabs">
					<li><a href="#portlet_log" data-toggle="tab">Log</a></li>
					<li><a href="#portlet_tab3" data-toggle="tab">ตั้งค่า</a></li>
					<li><a href="#portlet_relate" data-toggle="tab">ข่าวที่เกี่ยวข้อง</a></li>
					<li class="active"><a href="#portlet_tab1" data-toggle="tab">Gallary</a></li>					
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="portlet_tab1">
						
						<div class="form-body">
							<div class="form-group">  
								<?php $disable = empty($contents->id)?"disabled":'';?>
								<a href="<?= Url::toRoute('contents/edit')?>" class="btn btn-warning pull-right" <?= $disable?>><i class="fa fa-plus"></i> New Content </a>
								<a href="#uploadContentImg" class="btn btn-success pull-right" data-toggle="modal" <?= $disable?>><i class="fa  fa-cloud-upload"></i> Upload Image</a>
							</div>
							<?php if(!empty($contents->id)){?>
							<div class="form-group">
								<label>URL</label>
								<div class="input-group">
									<input class="form-control" type="text" id="post-shortlink" value="<?= $postUrl.$contents->postTitle?>" readonly></input>
									<span class="input-group-btn">
										<a class="btn default" id="copy-button" data-clipboard-target="#post-shortlink"><i class="fa fa-copy"></i></a>
									</span>
								</div>
							</div>
							<?php }?>
							<div class="form-group">
								<label>หัวข้อข่าว</label>
								<?= Html::activeTextInput($contents, 'title',['class'=>'form-control', 'maxlength'=>'140'])?>
								<span class="help-block pull-right"> <code>140 ตัวอักษร</code></span>
							</div>
							<div class="form-group">
								<label>คำโปรยข่าว</label>
								<?= Html::activeTextarea($contents, 'abstract',['maxlength'=>225,'rows'=>2,'class'=>'form-control', 'placeholder'=>'เนื้อหาโดยสรุปของข่าว  225 ตัวอักษร.'])?>
								<span class="help-block pull-right"> โปรยข่าว - เนื้อหาโดยสรุปของข่าว  <code>225 ตัวอักษร</code> </span>
							</div>
							<div class="form-group">
								<label>เนื้อหาข่าว</label>
								<?= Html::activeTextarea($contents, 'content',['id'=>'tinymce_modify','class'=>'form-control', 'rows'=>'20', 'placeholder'=>'', 'style'=>'font-size:14px;'])?>
							</div>		
						</div>
					</div>
					
					<div class="tab-pane" id="portlet_relate">
						<?= yii\base\View::render('relatecontent', [
								'contents' => $contents,
								'relateData' => $relateData
						]); ?>
					</div>
					<div class="tab-pane" id="portlet_tab3">
						<?= yii\base\View::render('confbar',[
								'form'=>$form,
								'contents'=>$contents,
									
						]); ?>
						
						
					</div>
					<div class="tab-pane" id="portlet_log">
						<?= LogWidget::widget([
							'type'=>Workflow::TYPE_CONTENT,
							'id'=>$contents->id,
							'view'=>'content-edit',
						])?>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="form-actions text-center">                           
				<a type="button" class="btn default" href="<?= Url::toRoute('contents/list')?>">ยกเลิก</a>  
				<button type="submit" class="btn green saveBtn">บันทึก</button>                            
			</div>
		</div>
	</div>
</div>

<?= Html::hiddenInput('id',$contents->id);?>
<?= Html::hiddenInput('op','');?>
 <?php ActiveForm::end(); ?>
<div class="clearfix"></div>

<?php echo TinyMCE::widget()?>

<?php $currentAction = !empty($contents->id)?'gallary/edit?id='.$contents->id:'gallary/edit';?>
<?= Uploadfile::widget([
		'currentAction'=>Url::toRoute($currentAction),
		'model'=>$contents,
 		'modalId'=>'uploadContentImg',
		'type'=>$type,
]);?>


