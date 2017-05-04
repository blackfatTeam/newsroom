<?php
$baseUri = Yii::getAlias('@web');
use app\components\SearchContent;
use yii\helpers\Url;
$url = Url::toRoute(['contents/getitem']);
$urlSave = Url::toRoute(['contents/saverelate']);
$str = <<<EOT
$(document).delegate('.deleteTr','click',function(e){
	$(this).parent().parent().parent().remove();
	var countTr = $('tr[class=selectedTr]').length;
	if(countTr == 0){
		$('.tbodySelect').html('<tr class="dumpTr"><td colspan="2">ไม่มีรายการที่เลือกไว้</td></tr>');
	}
	$('.totalCount').html(countTr);
});



$(document).delegate('.saveRelate','click',function(e){
	id = $(this).attr('data-id');
	
	if(id.length){
		var selectTr = $('.tbodySelect').find('.selectedTr');

		arrId = [];
		$.each(selectTr, function( i, tr ) {
			arrId.push($(tr).attr('data-id'));
		});
		
		arrType = [];
		$.each(selectTr, function( i, tr ) {
			arrType.push($(tr).attr('data-type'));
		});
		
		$.get('$urlSave', {
				id: id,
				arrId: arrId,
				arrType: arrType
		}).done(function(data) {
			if(typeof data == "string"){
				var data = $.parseJSON(data);
			}
			toastr.options.positionClass = "toast-top-full-width";
			toastr.options.timeOut = "4000";
			if(data.resultFact === 1){
				toastr.success(data.result);
			}else{
				toastr.warning(data.result);
			}
		
		});
	}
});

$( "tbody" ).sortable({
	connectWith: '.tbodySelect',
	cursor: 'move',
	opacity: 0.5,

	 receive: function(event, ui){
	 	var countTr = $('tr[class=selectedTr]').length;
	 	var limit = parseInt($('.saveRelate').attr('data-limit'));
	 	
	 	dropItem = $(ui.item[0]);
		dropUi = ui;
	 	if(countTr++ < limit){		
			currentTr = dropItem[0];
			type = $(dropItem[0]).attr('data-type');
			var tb = dropItem.parent('tbody');

			$.get('$url', {
					id: dropItem.attr('data-id'),
					type: type
			}).done(function(data) {
				if(typeof data == "string"){
					var data = $.parseJSON(data);
				}
							
				var cloneTr = $('#cloneTrSelect').clone();
				var dropHtml = dropData(data, cloneTr, type);
				dropItem.after(dropHtml);
				dropItem.remove();
				$('.tbodySelect').find('.dumpTr').remove();
				$('.totalCount').html(countTr);	
			});
		}else{
			dropItem.remove();
			alert('เกินจำนวนที่ตั้งไว้');
		}		
				
    }
});

function dropData(data, cloneTr, type){
	var cloneHtml = $(cloneTr).html();
	cloneHtml = cloneHtml.replace('{id}', data.id);
	cloneHtml = cloneHtml.replace('{title}', data.title);
	cloneHtml = cloneHtml.replace('{img}', data.img);
	cloneHtml = cloneHtml.replace('{time}', data.time);
	return '<tr class="selectedTr" data-type="'+type+'" data-id="'+ data.id +'">'+ cloneHtml + '</tr>';
}
EOT;

$this->registerJs($str);
?>
<style>
#cloneTrSelect{
	display: none;
}
</style>
<div class="row">
	<div class="col-md-4">
	
		<!-- Drop item -->
		<div class="portlet box grey tabbable">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-reorder"></i>
					ข่าวที่เกี่ยวข้อง 
				</div>
				<div class="actions">
					<a href="javascript:;" class="btn green btn-sm saveRelate" data-limit="6" data-id="<?php echo $contents->id?$contents->id:'';?>"><i class="fa fa-plus"></i> Save</a>
				</div>
			</div>
			<div class="portlet-body">
				<div class="table">
					<table class="table table-striped table-bordered">
						<tbody class="tbodySelect">
						
							<?php if(!empty($relateData)){?>
							<?php foreach($relateData as $lst):?>
							<tr class="selectedTr" data-type="<?php echo $lst['type']?>" data-id="<?php echo $lst['relateId']?>">
								<td><?php echo $lst['img']?></td>
								<td>
									<p><?php echo $lst['relateId']?>. <?php echo $lst['title']?></p>
									<p>
									<div class="pull-left">
										<?php echo date('Y-m-d H:i น.', strtotime($lst['publishTime']))?>
									</div>
									<div class="pull-right">
										<a href="<?= Url::toRoute(['contents/edit','id'=>$lst['relateId']])?>"> <i class="fa fa-pencil" title="แก้ไขข่าวนี้"></i> </a> 
										<a href="javascript:;" class="deleteTr"> <i class="fa fa-trash-o" title="ลบ"></i> </a>
									</div>
									</p>
								</td>
							</tr>
							<?php endforeach;?>
							<?php }else{?>
							<tr class="dumpTr">
								<td colspan="2">
									ไม่มีรายการที่เลือกไว้
								</td>
							</tr>
							<?php }?>
							

						</tbody>
						<tr id="cloneTrSelect" data-type="{type}" data-id="{id}">
								<td>{img}</td>
								<td>
									<p>{id}. {title}</p>
									<p>
									<div class="pull-left">
										{time}
									</div>
									<div class="pull-right">
										<a href="javascript:;"> <i class="fa fa-pencil" title="แก้ไขข่าวนี้"></i> </a> 
										<a href="javascript:;" class="deleteTr"> <i class="fa fa-trash-o" title="ลบ"></i> </a>
									</div>
									</p>
								</td>
							</tr>
					</table>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<!-- Drop item -->

	</div>
	<div class="col-md-8">

		<!-- Query news -->
		<?= SearchContent::widget(['section' => $section, 'arrId' => $arrId]) ?>
		<!-- Query news -->
		
	</div>
</div>