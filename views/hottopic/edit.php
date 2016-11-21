<?php

use yii\helpers\Url;

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use app\models\Hottopic;
use app\lib\Workflow;

$baseUri = Yii::getAlias('@web');
$tagSearchUri = Url::toRoute(['contents/tagapi']);

$str = <<<EOT

initTags();
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
	$('#hottopicFormAdd').find('input[name="tags"]').val('');
	//$('.select2-choices').children().remove();
	$('#hottopicFormAdd').find('select[name="status"]').val('');	
	$('#modal-hottopicFormAdd').modal('show');
	
});

$('.edit-form').on('dblclick',function(){
	id = $(this).data('id');
	title = $(this).data('title');
	tags = $(this).data('tags');
	status = $(this).data('status');

	$('#hottopicFormAdd').find('button[type="submit"]').text("Edit")
	$('#hottopicFormAdd').find('input[name="op"]').val('edit');
		
	$('#hottopicFormAdd').find('input[name="id"]').val(id);
	$('#hottopicFormAdd').find('input[name="title"]').val(title);
	$('#hottopicFormAdd').find('input[name="tags"]').val(tags);
	$('#hottopicFormAdd').find('select[name="status"]').val(status);
						
	//init tags
	initTags();
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
function initTags(){
	 $('#tagSug').select2({
	 		tags: true,
	 		multiple: true,
	 		//tokenSeparators: [',', ' '],
		    //minimumInputLength: 2,
		    
		   	createSearchChoice : function (term) { return {id: term, text: term}; },
		   	ajax: {
				url: '$tagSearchUri',
				dataType: 'json',
				data: function (params) {
			      return {
			        q: params
			      };
			    },		
				results: function(data) {	
					return {
						results: $.map(data, function(item) {
							return {
								id: item.value,
								//slug: item.value,
								text: item.value
							};
						}),
					};
				}
			},
			// Take default tags from the input value
		    initSelection: function (element, callback) {
		        var data = [];
		
		        function splitVal(string, separator) {
		            var val, i, l;
		            if (string === null || string.length < 1) return [];
		            val = string.split(separator);
		            for (i = 0, l = val.length; i < l; i = i + 1) val[i] = $.trim(val[i]);
		            return val;
		        }
		
		        $(splitVal(element.val(), ",")).each(function () {
		            data.push({
		                id: this,
		                text: this
		            });
		        });
		
		        callback(data);
		    }, 
			
        });
		
}
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
				<a   class="btn green" id="btn-addItem"><i class="fa fa-plus"></i> Add</a>
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
							<th>Tags</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
						<tbody class="orderAble">
							<?php foreach($arrHottopic as $i => $hot){?>
							<tr data-id="<?= $hot->id?>" data-title="<?= $hot->title?>" data-tags="<?= $hot->tags?>" data-status="<?= $hot->status?>" class="edit-form">
								<td><?php echo $i+1?></td>
								<td><?php echo $hot->title?></td>
								<td><?php echo $hot->tags?></td>
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
					<label for="tags" class="col-md-2 control-label">Tags</label>
					<div class="col-md-10">
						<input type="text" name="tags" class="form-control" id="tagSug" placeholder="tags">
					</div>
				</div>
				<div class="form-group">
					<label for="status" class="col-md-2 control-label">Status</label>
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

