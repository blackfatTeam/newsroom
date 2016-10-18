<?php
use app\components\SearchContent;
use yii\helpers\Url;
$url = Url::toRoute(['contents/getitem']);
$str = <<<EOT
$( document ).ready(function() {
   
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
			debugger;
			dropItem.after(dropHtml);	
			dropItem.remove();	
			debugger;
		});
    }	
});

function dropData(data, cloneTr){
	var cloneHtml = $(cloneTr).html();
	cloneHtml = cloneHtml.replace('{id}', data.id);
	cloneHtml = cloneHtml.replace('{title}', data.title);
	return '<tr data-object="content" data-id="'+ data.id +'">'+ cloneHtml + '</tr>';
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
			</div>
			<div class="portlet-body">
				<div class="table">
					<table class="table table-striped table-bordered">
						<tbody class="tbodySelect">
							<tr>
								<td colspan="2">
									ไม่มีรายการที่เลือกไว้
								</td>
								
							</tr>
					
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
