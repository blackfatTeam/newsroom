<?php

use yii\helpers\Url;
use app\lib\Workflow;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
$baseUri = Yii::getAlias('@web');
$str = <<<EOT
$('.checkList').on('click',function(){
	if($('[name^="selectContent"]:checked').length == $('[name^="selectContent"]').length)
	{
		$('[name^="selectContent"]').removeAttr('checked');
		$('[name^="selectContent"]').parent().removeClass('checked');
		$('.deleteBtn').attr('style','display:none;');
	}else{
		$('[name^="selectContent"]').attr('checked','checked');
		$('[name^="selectContent"]').parent().addClass('checked');
		$('.deleteBtn').attr('style','display:;');
	}
});
		
$('[name^="selectContent"]').on('click',function(){
	if ($('[name^=selectContent]:checked').length > 0)
	{
		$('.deleteBtn').attr('style','display:;');
	}else{
		$('.deleteBtn').attr('style','display:none;');
	}	
});

$('.deleteBtn').on('click',function(){
	if(confirm('คุณต้องการลบ ใช่หรือไม่')){
		$('input[name="op"]').val('delete');
		$('#contentList').submit();
	}
	return false;
});


$('.searchBtn').on('click',function(){

	$('input[name="op"]').val('search');
	$('#contentList').submit();
});
$('.resetBtn').on('click',function(){

	$('input[name="op"]').val('resetSearch');
	$('#contentList').submit();
});
$('input[name="q"]').on('keydown',function(e){

	if(e.keyCode==13){
		
		$('input[name="op"]').val('search');
		$('#contentList').submit();
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
<?php $form = ActiveForm::begin(['id'=>'contentList','options' => ['method'=>'post','class'=>'form-horizontal']]);?>
	<div class="portlet box grey">
		<div class="portlet-title">
			<div class="caption"><i class="fa fa-reorder"></i>User List</div>
			<div class="actions">
				<a href="javascript:;" class="btn btn-danger deleteBtn" style="display:none;"><i class="fa fa-minus-circle"></i> Delete</a>
				<a class="btn btn-warning" data-toggle="collapse" data-parent="" href="#searchForm"><i class="fa fa-search"> Search</i></a>
				<a href="<?= Url::toRoute('user/edit')?>" class="btn green"><i class="fa fa-plus"></i> เพิ่ม User</a>
				<a class="btn btn-info resetBtn" data-parent="" href="javascript:;"><i class="fa fa-refresh"></i> Refresh</a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="col-md-12">
				<div class="panel">
					<div id="searchForm" class="panel-collapse collapse" style="height: 0px;">
						<div class="panel-body">
							<div class="col-md-12">
								<div class="col-md-9">
									<div class="form-body">
										<div class="form-group">
											<?= Html::textInput('q',$search['q'],['class'=>'form-control','placeholder'=>'Title'])?>
											<span class="help-block">Text (username, name)</span>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-body">
										<div class="form-group">
											<?= Html::dropDownList('status',$search['status'],[''=>'ทุกสถานะ']+Workflow::$arrStatusTh,['class'=>'form-control']);?>
											<span class="help-block">Status (สถานะ)</span>
										</div>
									</div>
								</div>
								<div class="col-md-1 pull-right">
									<div class="form-body">
										<div class="form-group">
											<a class="btn dark searchBtn" href="javascript:;"><i class="fa fa-search"></i> ค้นหา</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="clearfix"></div>		
			<div class="table-responsive">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th><div class="checkList"><i class="fa fa-check-square-o"></i></div></th>
							<th>Username</th>
							<th>FirstName</th>
							<th>LastName</th>
							<th>Status</th>						
							<th>อัพเดทล่าสุด</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($contentList as $i=>$model){?>
						<tr>
							<td><?= Html::input('checkbox','selectContent[]',$model->id,['class'=>'form-control'])?></td>
							<td>
								<a class="" href="<?= Url::toRoute(['user/edit','id'=>$model->id])?>">
								<?= $model->username?>
								</a>
							</td>
							<td>
								<a class="" href="<?= Url::toRoute(['user/edit','id'=>$model->id])?>">
								<?= $model->firstName?>
								</a>
							</td>
							<td>
								<a class="" href="<?= Url::toRoute(['user/edit','id'=>$model->id])?>">
								<?= $model->lastName?>
								</a>
							</td>
							<td>
								<i class="fa <?= isset(Workflow::$arrStatusFaIcon[$model->status]['icon'])?Workflow::$arrStatusFaIcon[$model->status]['icon']:''?>" 
									title="<?= isset(Workflow::$arrStatusTh[$model->status])?Workflow::$arrStatusTh[$model->status]:''?>">
								</i>
								
							</td>
							<td><?= date('d/m/Y H:i',strtotime($model->lastUpdateTime))?></td>
							<td>
								<a class="btn btn-warning" href="<?= Url::toRoute(['user/edit','id'=>$model->id])?>"><i class="fa fa-edit"></i> Edit</a>
							</td>
						</tr>
					<?php }?>
					</tbody>
				</table>
			</div>
			<div class="row">
				<div class="col-md-5 col-sm-12">
					<div class="" id="sample_1_info">รายการที่ <?= $pages->offset+1?> - <?= $pages->offset + count($contentList)?> จากทั้งหมด <?= $pages->totalCount?></div>
				</div>
				<div class="col-md-7 col-sm-12">
				<?php echo LinkPager::widget(['pagination' => $pages, 'options' => ['class'=> 'pagination pull-right']])?>		
				</div>
			</div>
		</div>
	</div>

<?= Html::hiddenInput('op','');?>
 <?php ActiveForm::end(); ?>