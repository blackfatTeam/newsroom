<?php

use yii\helpers\Url;

use app\lib\Workflow;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;
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


EOT;
$this->registerJs($str);

$jProvinces = json_encode($provinces);
$str = <<<EOT
province = $jProvinces;

	 $('#tagSug').select2({
	 		tags: true,
	 		multiple: true,
			data: province,
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
			<div class="caption"><i class="fa fa-reorder"></i>Content List</div>
			<div class="actions">
				<a href="javascript:;" class="btn btn-danger deleteBtn" style="display:none;"><i class="fa fa-minus-circle"></i> Delete</a>
				<a class="btn btn-warning" data-toggle="collapse" data-parent="" href="#searchForm"><i class="fa fa-search"> Search</i></a>
				<a href="<?= Url::toRoute('contents/edit')?>" class="btn green"><i class="fa fa-plus"></i> New Content</a>
				<a class="btn btn-info resetBtn" data-parent="" href="javascript:;"><i class="fa fa-refresh"></i> Refresh</a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="col-md-12">
					<div id="searchForm" class="collapse" style="height: 0px;">
						<div class="panel-body">
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-body">
										<div class="form-group">
											<?= Html::textInput('title',$search['title'],['class'=>'form-control','placeholder'=>'Title'])?>
											<span class="help-block">Title (หัวข้อ เนื้อหา)</span>
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
								<div class="col-md-3">
									<div class="form-body">
										<div class="form-group">
											<?= Html::textInput('web',$search['web'],['class'=>'form-control select2','id'=>'tagSug'])?>
											<span class="help-block">Province (จังหวัด)</span>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-body">
										<div class="form-group">
											<?php echo DatePicker::widget([
													'name' => 'publishTime',
													'language'=>'th',
													'value'=>$search['publishTime'],
													'type' => DatePicker::TYPE_COMPONENT_APPEND,
													'options'=>['placeholder'=>'ทุกวัน'],
													'pluginOptions' => [
															'autoclose'=>true,
															'format' => 'yyyy-mm-dd',
															'todayHighlight'=>true
														]
													]);
												?>
											<span class="help-block">Publish time (วันที่เผยแพร่)</span>
										</div>
									</div>
								</div>
								<div class="col-md-1">
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
			
			<div class="clearfix"></div>		
			<div class="table-responsive">
				<table class="table table-hover table-bordered">
					<thead>
						<tr class="text-center">
							<th class="text-center"><div class="checkList"><i class="fa fa-check-square-o"></i></div></th>
							<th >ชื่อข่าว</th>
							<th class="text-center">สถานะ</th>
							<th class="text-center">เวลาแสดง</th>
							<th class="text-center">จำนวนรูปที่อัพโหลด</th>							
							<th class="text-center">อัพเดทล่าสุด</th>
							<th class="text-center">จังหวัด</th>
							<th class="text-center">สร้างโดย</th>
							<th class="text-center">หมวดหมู่</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($contentList as $i=>$model){?>
						<tr>
							<td  align="center"><?= Html::input('checkbox','selectContent[]',$model['id'],['class'=>'form-control'])?></td>
							<td title="<?= $model['title']?>">
								<a class="" href="<?= Url::toRoute(['contents/edit','id'=>$model['id']])?>">
								<?= $model['id']?> <?= mb_substr($model['title'], 0,40,'UTF-8')?>
								</a>
							</td>
							<td  align="center">
								<i class="fa <?= Workflow::$arrStatusFaIcon[$model['status']]['icon']?>" 
									title="<?= Workflow::$arrStatusTh[$model['status']]?>">
								</i>
								<?php if($model['thumbnail']!=null){?>
								<i class="fa <?= Workflow::ICON_THUMBNAIL?>"
									title="มีรูป thumbnail แล้ว">
								</i>
								<?php }?>
								
							</td>
							<td align="center"><?= date('d/m/Y H:i',strtotime($model['publishTime']))?></td>
							<td align="center"><?= $model['amountImage']?></td>
							<td align="center"><?= date('d/m/Y H:i',strtotime($model['lastUpdateTime']))?></td>
							<td align="center"><?= isset(Workflow::$arrWeb[$model['web']])?Workflow::$arrWeb[$model['web']]:'-'?></td>
							<td align="center"><?= $model['createByStr']?></td>
							<td align="center"><?= isset($arrCate[$model['categoryId']])?$arrCate[$model['categoryId']]:'-' ?></td>
							<td align="center">
								<a class="btn btn-sm btn-warning" href="<?= Url::toRoute(['contents/edit','id'=>$model['id']])?>"><i class="fa fa-edit"></i> Edit</a>
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