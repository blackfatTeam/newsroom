<?php

use yii\helpers\Url;

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use app\models\Hottopic;
use app\lib\Workflow;

$baseUri = Yii::getAlias('@web');
$str = <<<EOT
$( "tbody.orderAble" ).sortable();

		
 $('.saveBtn').on('click',function(){
     
     data = [];
    
     $("tbody.orderAble tr").each(function(i) { 
     
     		id = $(this).data('id');
     		data.push(id); 
	 });

	 var str = JSON.stringify(data);
	 $('input[name="orderItem"]').val(str);
	 $('input[name="op"]').val('order');
     $('#hottopicFormOrder').submit();
 });

$('#btn-addItem').on('click',function(){
	$('#hottopicFormAdd').find('button[type="submit"]').text("Add")
	$('#hottopicFormAdd').find('input[name="op"]').val('add');
	$('#hottopicFormAdd').find('input[name="title"]').val('');
	$('#hottopicFormAdd').find('input[name="link"]').val('');
	$('#hottopicFormAdd').find('select[name="status"]').val('');		
});

$('.edit-form').on('dblclick',function(){
	id = $(this).data('id');
	title = $(this).data('title');
	link = $(this).data('link');
	status = $(this).data('status');
	
	$('#hottopicFormAdd').find('button[type="submit"]').text("Edit")
	$('#hottopicFormAdd').find('input[name="op"]').val('edit');
		
	$('#hottopicFormAdd').find('input[name="id"]').val(id);
	$('#hottopicFormAdd').find('input[name="title"]').val(title);
	$('#hottopicFormAdd').find('input[name="link"]').val(link);
	$('#hottopicFormAdd').find('select[name="status"]').val(status);
	$('#modal-hottopicFormAdd').modal('show');
	
});
$('.btn-delete').on('click',function(){
	if(confirm('ต้องการจะลบ ใช้หรือไม่')){
		id = $(this).data('id');
		$('#hottopicFormAdd').find('input[name="op"]').val('delete');		
		$('#hottopicFormAdd').find('input[name="id"]').val(id);
		$('#hottopicFormAdd').submit();
	}
	
});
EOT;
$this->registerJs($str);
$css = <<<EOT

EOT;
$this->registerCss($css);
$this->title = Yii::$app->controller->action->id;
$uri = Yii::$app->controller->getRoute();

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => [$uri]];
?>
<?php $form = ActiveForm::begin(['id'=>'hottopicFormOrder','options' => ['method'=>'post','class'=>'form-horizontal']]);?>
	<div class="portlet box grey">
		<div class="portlet-title">
			<div class="caption"><i class="fa fa-reorder"></i>Hot Topic</div>
			<div class="actions">
				<a data-toggle="modal" href="#modal-hottopicFormAdd"  class="btn green" id="btn-addItem"><i class="fa fa-plus"></i> Add</a>
				<a href="javascript:;" class="btn btn-info saveBtn"><i class="fa fa-save"></i> Order Save</a>				
			</div>
		</div>
		<div class="portlet-body">
			<div class="row">
		      	<div class="col-md-12">
		      		<h4><i class="fa fa-reorder"></i> Section: <?php echo $web?></h4>
					<table class="table table-striped table-bordered tablePick" id="list-id">
						<tr>
							<th>No.</th>
							<th>Title</th>
							<th>Link</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
						<tbody class="orderAble">
							<?php foreach($arrHottopic as $i => $hot){?>
							<tr data-id="<?= $hot->id?>" data-title="<?= $hot->title?>" data-link="<?= $hot->link?>" data-status="<?= $hot->status?>" class="edit-form">
								<td><?php echo $i+1?></td>
								<td><?php echo $hot->title?></td>
								<td><?php echo $hot->link?></td>
								<td><?php echo isset(Workflow::$arrHottopicStatus[$hot->status])?Workflow::$arrHottopicStatus[$hot->status]:''?></td>
								<td><a class="btn red btn-xs pull-right btn-delete" data-id="<?= $hot->id?>"><i class="fa fa-minus " title="ลบ"></i></a></td>
							</tr>
							<?php }?>
						</tbody>
					</table>        				
				
				</div>
		
		      </div>
	      
	      		
			
		</div>
	</div>
<?= Html::hiddenInput('web',$web);?>
<?= Html::hiddenInput('orderItem','');?>
<?= Html::hiddenInput('op','order');?>
 <?php ActiveForm::end(); ?>
 
 <!-- edit form -->
 <?php $form = ActiveForm::begin(['id'=>'hottopicFormAdd','options' => ['method'=>'post','class'=>'form-horizontal']]);?>
 <div class="modal fade" id="modal-hottopicFormAdd" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">Hot Topic</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="title" class="col-md-2 control-label">Title</label>
					<div class="col-md-10">
						<input type="text" name="title" class="form-control" id="title" placeholder="title">
					</div>
				</div>
				<div class="form-group">
					<label for="link" class="col-md-2 control-label">Link</label>
					<div class="col-md-10">
						<input type="text" name="link" class="form-control" id="link" placeholder="link">
					</div>
				</div>
				<div class="form-group">
					<label for="link" class="col-md-2 control-label">Status</label>
					<div class="col-md-10">
						<?= Html::dropDownList('status','',Workflow::$arrHottopicStatus, ['class'=>'form-control'])?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-info">Add</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<?= Html::hiddenInput('web',$web);?>
<?= Html::hiddenInput('op','');?>
<?= Html::hiddenInput('id','');?>
<?php ActiveForm::end(); ?>

