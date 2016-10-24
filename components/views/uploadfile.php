<?php 
use kartik\file\FileInput;
use yii\helpers\Url ;
use yii\base\View;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$baseUri = Yii::getAlias('@web');

$str = <<<EOT
$( document ).ready(function() {
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
		debugger;
		imgPath = $(this).attr('src');
		id = $(this).attr('data-id');
		isthumb = $(this).attr('data-isthumb');
		
		$('.modal-image-preview').attr('src',imgPath);
		$('.setConfigImage').attr('data-id',id);
		window.currentThumb = $(this);
		window.btnThumb = '<button class="btn isThumb" style="position: absolute;top:1;background-color:#BE922A;"><i class="fa fa-picture-o"></i></button>';	
		
		if(isthumb=='1'){
			$('.setConfigImage').attr('data-action','clear');
			$('.setConfigImage').removeClass('dark').addClass('default');
			$('.setConfigImage').find('.fa').removeClass('fa fa-picture-o').addClass('fa-times');
		}else{
			$('.setConfigImage').attr('data-action','set');
			$('.setConfigImage').removeClass('default').addClass('dark');
			$('.setConfigImage').find('.fa').removeClass('fa-times').addClass('fa fa-picture-o');
		}
	});
		
	$('.setConfigImage').on('click',function(){
		ac = $(this).attr('data-action');
		id = $(this).attr('data-id');

		result = setConfigImage(id,ac);
		$('.isThumb').remove();		
		$('.imageThumb[data-isthumb="1"]').attr('data-isthumb','0');
		
		if(ac=='set'){
			window.currentThumb.attr('data-isthumb','1');
			window.currentThumb.before(window.btnThumb);
		}
		

		$('#modalConfigImage').modal('hide');
	});
	function setConfigImage(imageId,ac){
		modelId = $('input[name="id"]').val();

		$.post("$baseUri/media/setthumbnail",{
			modelId: modelId,
			imageId: imageId,
			action: ac,
			type: $type
		}).done(function(data) {
			
		});		
	}
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
							<form class="form-horizontal" role="form">
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-3 control-label">Caption</label>
										<div class="col-md-9">
											<textarea class="form-control" rows="3"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label  class="col-md-3 control-label">Thumbnail</label>
										<div class="col-md-9">
											<div class="checkbox-list">
												<label class="checkbox-inline">
												<input type="checkbox" value="option1">
												</label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label  class="col-md-3 control-label">ลายน้ำ</label>
										<div class="col-md-9">
											<div class="radio-list">
												<label class="radio-inline">
												<input type="radio" name="optionsRadios" id="optionsRadios25" value="option1" checked> ไม่มี
												</label>
												<label class="radio-inline">
												<input type="radio" name="optionsRadios" id="optionsRadios26" value="option2" > แบบที่ 1
												</label>
												<label class="radio-inline">
												<input type="radio" name="optionsRadios" id="optionsRadios27" value="option3" > แบบที่ 2
												</label>  
											</div>
										</div>
									</div>
								</div>
								<div class="form-actions fluid">
									<div class="col-md-offset-3 col-md-9">
										<button type="button" class="btn default" data-dismiss="modal" aria-hidden="true">Cancel</button> 
										<button type="button" class="btn green" data-dismiss="modal" aria-hidden="true">Save</button>										                             
									</div>
								</div>
							</form>
						</div>
					</div>
						
				</div><!-- 
				<div class="modal-footer">
					<a href="javascript:;" class="btn dark btn-block btn-lg setConfigImage" data-action="set"><i class="fa fa-picture-o"></i> Thumbnail</a>
				</div> -->
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	
	<div class="modal fade" id="modalPreview" tabindex="-1" role="basic" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Preview</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6 col-md-offset-3">
							<img  src="" class="img-responsive col-md-12 modal-image-preview" alt="">
						</div>
					</div>		
						
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
</div>



