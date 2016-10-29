<?php 
use kartik\file\FileInput;
use yii\helpers\Url ;
use yii\base\View;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use app\lib\Workflow;

$baseUri = Yii::getAlias('@web');
$waterMarkNone = Workflow::WATER_MARK_NONE;
$str = <<<EOT
$( document ).ready(function() {
	var waterMarkNone = $waterMarkNone;
	var currentThumb;
	var btnThumb;
	var btnThumbSet; 

	$('.deleteAllimg').on('click',function(){
		if(confirm('ต้องการลบทั้งหมด หรือไม่')){
			op = $(this).attr('data-action');		
			$('input[name="op"]').val(op);
			
			$('#uploadFile').submit();
		}
		return false;
	});
	$('.imageThumb').on('click',function(){

		window.currentThumb = $(this);
		imgPath = $(this).attr('src');
		id = $(this).attr('data-id');
		isthumb = $(this).attr('data-isthumb');
		caption = $(this).attr('data-caption');
		watermark = $(this).attr('data-watermark');
		
		$('.modal-image-preview').attr('src',imgPath);
		$('input[name="hiddenMediaId"]').val(id);
		$('#formConfigMedia').find('textarea[name="textAreaCaption"]').val(caption);
		
		//set checkbox thumbnail
		if(isthumb == '1'){
			$('#formConfigMedia').find('input[name="checkBoxSetThumbnail"]').attr('checked', true);
			$('#formConfigMedia').find('input[name="checkBoxSetThumbnail"]').parent().addClass('checked');
		}else{
			$('#formConfigMedia').find('input[name="checkBoxSetThumbnail"]').attr('checked', false);
			$('#formConfigMedia').find('input[name="checkBoxSetThumbnail"]').parent().removeClass('checked');
		}
		
		//set radio watermark
		if(typeof(watermark)==='undefined'){
			watermark = waterMarkNone;
		}
		$('input[name="radioWatermark"]:checked').parent().removeClass('checked');
		$('input[name="radioWatermark"]:checked').attr('checked',false);		
	
		$('input[name="radioWatermark"][value='+ watermark +']').attr('checked',true);
		$('input[name="radioWatermark"][value='+ watermark +']').parent().addClass('checked');

	});
		
	$('.btnSaveConfigImage').on('click',function(){

		caption = $('#formConfigMedia').find('textarea[name="textAreaCaption"]').val();
		isThumbnail = $('#formConfigMedia').find('input[name="checkBoxSetThumbnail"]')[0].checked;
		watermark = $('#formConfigMedia').find('input[name="radioWatermark"]:checked').val();
		modelId = $('input[name="id"]').val();
		mediaId = $('input[name="hiddenMediaId"]').val();

		data = {
			modelId : modelId,
			type : $type,
			mediaId : mediaId,
			caption : caption,
			isThumbnail : isThumbnail,
			watermark : watermark
		};

		$.post("$baseUri/media/setconfigmedia",{
			data: data
		}).done(function(data) {
			$('.isThumb').remove();	//set icon thumbnail
			if(isThumbnail){
				isThumbnail = 1;
				window.currentThumb.before('<button class="btn isThumb" style="position: absolute;top:1;background-color:#BE922A;"><i class="fa fa-picture-o"></i></button>');
			}else{
				isThumbnail = 0;
			}

			window.currentThumb.attr('data-isthumb',isThumbnail);
			window.currentThumb.attr('data-caption',caption);
			window.currentThumb.attr('data-watermark',watermark);
		});		
		$('#modalConfigImage').modal('hide');
	});

});
EOT;

$this->registerJs($str, \yii\web\View::POS_READY);
$css = <<<EOT
	.file-preview-image{
		height:120px !important;
	}
EOT;
$this->registerCss($css);
?>


<div class="col-md-12">
	<div class="modal fade" id="<?= $modalId?>" tabindex="-1" role="basic" aria-hidden="true">
		<div class="modal-dialog modal-full">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Preview</h4>
				</div>
				<div class="modal-body">
				
					<?php $form = ActiveForm::begin(['id'=>'uploadFile','action'=>$baseUri.'/media/manage','options' => ['enctype' => 'multipart/form-data','method'=>'post']]); ?>
					<div class="col-md-12">
						<a class="btn red pull-right deleteAllimg" href="javascript:;" data-action="deleteAllimg">ลบอัลบัมทั้งหมด</a>
					</div>
					<div class="clearfix"></div>
					<div class="col-md-12">
						<div class="form-body">
							<div class="form-group">
					
						<?= FileInput::widget([
								'name' => 'upload_ajax[]',
								'options' => ['multiple' => true,'accept' => 'image/*'],
								
								'pluginOptions' => [
									'overwriteInitial'=>false,
									'initialPreviewShowDelete'=>true,
									'initialPreview'=> $initialPreview,
									'initialPreviewConfig'=> $initialPreviewConfig,
									'uploadUrl' => Url::to(['//media/uploadajax']),
									'uploadExtraData' => [
										'modelId' => isset($model->id)?$model->id:null,
										'type'=>$type,
									],
									'maxFileCount' => 150,
									'showCaption' => true,
									'showRemove' => true,
									'showUpload' => true,
									'uploadClass' => 'btn btn-success',
									'removeClass'=> 'btn btn-danger',
									'browseClass' => 'btn btn-primary',
									'browseIcon' => '<i class="fa fa-picture-o"> </i> ',
									'uploadIcon' => '<i class="fa fa-cloud-upload"> </i> ',
									'removeIcon' => '<i class="fa fa-trash-o"> </i> ',
									'browseLabel' =>  'เลือกไฟล์',
									'uploadLabel' =>  'อัพทั้งหมด',
									'removeLabel'=>'ลบทั้งหมด',
								],
								'pluginEvents' => [
								'filepredelete' => "function(event, key) {
						               		 		return (!confirm('Are you sure you want to delete ?'));
						            			}",
								'filedelete' => 'function(event, key) { console.log(\'File is delete\'); }',
								],
								]);
						?>
							</div>
						</div>
					</div>
						<?= Html::hiddenInput('modelId',isset($model->id)?$model->id:'');?>
						<?= Html::hiddenInput('op','');?>
						<?= Html::hiddenInput('type',$type);?>
						<?= Html::hiddenInput('currentAction',$currentAction);?>
					<?php ActiveForm::end(); ?>
						
				</div>
				<div class="modal-footer">
					<button type="button" class="btn default" data-dismiss="modal">Close</button>
					<a href="<?= $currentAction?>" class="btn green" >Save</a>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	
	
	<div class="modal fade" id="modalConfigImage" tabindex="-1" role="basic" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Config</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6 col-md-offset-3">
							<img src="" class="img-responsive col-md-12 modal-image-preview" alt="">
						</div>
					</div>		
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" role="form" id="formConfigMedia">
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-3 control-label">Caption</label>
										<div class="col-md-9">
											<textarea class="form-control" rows="3" name="textAreaCaption"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label  class="col-md-3 control-label">Thumbnail</label>
										<div class="col-md-9">
											<div class="checkbox-list">
												<label class="checkbox-inline">
												<input name="checkBoxSetThumbnail" type="checkbox">
												</label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label  class="col-md-3 control-label">ลายน้ำ</label>
										<div class="col-md-9">
											<div class="radio-list">
												<label class="radio-inline">
												<input type="radio" name="radioWatermark" id="radioWatermark1" value="<?= Workflow::WATER_MARK_NONE?>" checked> ไม่มี
												</label>
												<label class="radio-inline">
												<input type="radio" name="radioWatermark" id="radioWatermark2" value="<?= Workflow::WATER_MARK_1?>" > แบบที่ 1
												</label>
												<label class="radio-inline">
												<input type="radio" name="radioWatermark" id="radioWatermark3" value="<?= Workflow::WATER_MARK_2?>" > แบบที่ 2
												</label>  
											</div>
										</div>
									</div>
								</div>
								<div class="form-actions fluid">
									<div class="col-md-offset-3 col-md-9">
										<button type="button" class="btn default" data-dismiss="modal" aria-hidden="true">Cancel</button> 
										<button type="button" class="btn green btnSaveConfigImage" >Save</button>										                             
									</div>
								</div>
								<input type="hidden" name="hiddenMediaId">
							</form>
						</div>
					</div>
						
				</div><!-- 
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

</div>



