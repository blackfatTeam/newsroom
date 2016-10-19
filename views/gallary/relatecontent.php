<?php
use app\components\SearchContent;
use yii\helpers\Url;
$url = Url::toRoute(['contents/getitem']);
$urlSave = Url::toRoute(['contents/saverelate']);
$str = <<<EOT
$(document).delegate('.saveRelate','click',function(e){
	var id = $(this).attr('data-id');
	
	if(id.length){
		var selectTr = $('.tbodySelect').find('.selectedTr');
	
		arrId = [];
		$.each(selectTr, function( i, tr ) {
			arrId.push($(tr).attr('data-id'));
		});
		
		$.post('$urlSave', {
				id: id,
				arrId: arrId
		}).done(function(data) {
			if(typeof data == "string"){
				var data = $.parseJSON(data);
			}
			alert(data);
			
		});
	}
});

$( "tbody" ).sortable({ 	
	connectWith: '.tbodySelect',
	cursor: 'move',
	opacity: 0.5,

	 receive: function(event, ui){
		dropItem = $(ui.item[0]);	
		dropUi = ui;
		var tb = dropItem.parent('tbody');

		$.post('$url', {
				id: dropItem.attr('data-id')
		}).done(function(data) {
			if(typeof data == "string"){
				var data = $.parseJSON(data);
			}
		
			var cloneTr = $('#cloneTrSelect').clone();
			var dropHtml = dropData(data, cloneTr);
			dropItem.after(dropHtml);	
			dropItem.remove();	
			$('.tbodySelect').find('.dumpTr').remove();
		});
    }	
});

function dropData(data, cloneTr){
	var cloneHtml = $(cloneTr).html();
	cloneHtml = cloneHtml.replace('{id}', data.id);
	cloneHtml = cloneHtml.replace('{title}', data.title);
	return '<tr class="selectedTr" data-object="content" data-id="'+ data.id +'">'+ cloneHtml + '</tr>';
	debugger;
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
					<a href="javascript:;" class="btn green btn-sm saveRelate" data-id="<?php echo $contents->id?$contents->id:'';?>"><i class="fa fa-plus"></i> Save</a>
				</div>
			</div>
			<div class="portlet-body">
				<div class="table">
					<table class="table table-striped table-bordered">
						<tbody class="tbodySelect">
							<?php if(!empty($relateData)){?>
							<?php foreach($relateData as $lst):?>
							<tr class="selectedTr" data-object="content" data-id="<?php echo $lst['relateId']?>">
								<td><a href="javascript:;" class="pull-left"> {thumbnail}
								</a></td>
								<td>
									<p><?php echo $lst['relateId']?>. <?php echo $lst['title']?></p>
									<p><a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
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
						<tr id="cloneTrSelect" data-object="{type}" data-id="{id}">
								<td><a href="javascript:;" class="pull-left"> {thumbnail}
								</a></td>
								<td>
									<p>{id}. {title}</p>
									<p><a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
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
		<?= SearchContent::widget() ?>
		<!-- Query news -->
		
	</div>
</div>
