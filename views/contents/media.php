<?php 
use kartik\file\FileInput;
use yii\helpers\Url ;
use yii\base\View;

$str = <<<EOT
$( document ).ready(function() {
	var currentThumb;
	var btnThumb;
	var btnThumbSet; 

	$('.deleteAllimg').on('click',function(){
		if(confirm('ต้องการลบทั้งหมด หรือไม่')){
			op = $(this).attr('data-action');		
			$('input[name="op"]').val(op);
			$('input[name="tab"]').val('#portlet_tab2');
			$('#contentForm').submit();
		}
		return false;
	});
	$('.imageThumb').on('click',function(){
		
		imgPath = $(this).attr('src');
		id = $(this).attr('data-id');
		isthumb = $(this).attr('data-isthumb');
		
		$('#modal-image-preview').attr('src',imgPath);
		$('.setConfigImage').attr('data-id',id);
		window.currentThumb = $(this);
		window.btnThumb = '<button class="btn isThumb" style="position: absolute;top:1;background-color:#BE922A;"><i class="fa fa-camera"></i></button>';	
		
		if(isthumb=='1'){
			$('.setConfigImage').attr('data-action','clear');
			$('.setConfigImage').removeClass('dark').addClass('default');
			$('.setConfigImage').find('.fa').removeClass('fa-camera').addClass('fa-times');
		}else{
			$('.setConfigImage').attr('data-action','set');
			$('.setConfigImage').removeClass('default').addClass('dark');
			$('.setConfigImage').find('.fa').removeClass('fa-times').addClass('fa-camera');
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
		contentId = $('input[name="id"]').val();
		
		$.get("setconfigimage",{
			contentId:contentId,
			imageId:imageId,
			action:ac,
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
				'uploadUrl' => Url::to(['//contents/uploadajax']),
				'uploadExtraData' => [
					'contentsId' => $contents->id,
				],
				'maxFileCount' => 150,
				'showCaption' => true,
				'showRemove' => true,
				'showUpload' => true,
				'uploadClass' => 'btn btn-success',
				'removeClass'=> 'btn btn-danger',
				'browseClass' => 'btn btn-primary',
				'browseIcon' => '<i class="fa fa-camera"> </i> ',
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
<div class="col-md-12">
	<?php // dosamigos\gallery\Gallery::widget(['items' => $contents->getThumbnails($contents->id,$contents->title)]);?>
	
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
							<img id='modal-image-preview' src="" class="img-responsive col-md-12" alt="">
						</div>
					</div>		
						
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="btn dark btn-block btn-lg setConfigImage" data-action="set"><i class="fa fa-camera"></i> Thumbnail</a>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
</div>
