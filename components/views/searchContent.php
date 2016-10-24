<?php
$baseUri = Yii::getAlias('@web');
use app\lib\Workflow;
use yii\helpers\Url;
$url = Url::toRoute(['contents/generatecontent']);
$urlReset = Url::toRoute(['contents/resetcontent']);
$str = <<<EOT
$(document).delegate('.findConent','click',function(e){
	doSearch('content');
});

$(document).delegate('.findGallery','click',function(e){
	doSearch('gallery');
});

$(document).delegate('.resetbtn','click',function(e){
	var type = $(this).attr('data-type');
	doReset(type);
	$('input[name=q]').val('');
	$('input[name=qGallery]').val('');
});


$(document).keypress(function(e) {
    if(e.which == 13) {
        doSearch('content');
    }
});

function doSearch(type){
	var q = $('input[name=q]').val();
	var qGallery = $('input[name=qGallery]').val();
	if(q.length || qGallery.length){
		$.get('$url', {
				q: q,
				qGallery: qGallery,
				type: type
		}).done(function(data) {
			if(typeof data == "string"){
				var data = $.parseJSON(data);
			}
		
			var mainBody = $('.tbodyData[data-type='+type+']');
			if(data.length){
				var resultTr = getItem(data);
				$(mainBody).html(resultTr);
			}else{
				var noData = '<td colspan="2" height="100" class="text-center"><h2>ไม่มีข้อมูลที่คุณค้นหา</h2></td>';
				$(mainBody).html(noData);
			}
		});
	}
}		

function doReset(type){
	$.get('$urlReset', {
				type: type
		}).done(function(data) {
		if(typeof data == "string"){
			var data = $.parseJSON(data);
		}
		var mainBody = $('.tbodyData[data-type='+type+']');
		if(data.length){
			var resultTr = getItem(data);
			$(mainBody).html(resultTr);
		}else{
			var noData = '<td colspan="2" height="100" class="text-center"><h2>ไม่มีข้อมูลที่คุณค้นหา</h2></td>';
			$(mainBody).html(noData);
		}
	});
	
}				
				
function getItem(data, cloneTr){
	var cloneDiv = $('#cloneTrData').clone();	
	cloneHtml = '';
	$.each(data, function( i, val ) {
		var tdHtml = $(cloneDiv).html();
		tdHtml = tdHtml.replace('{id}', val.id);
		tdHtml = tdHtml.replace('{title}', val.title);
		tdHtml = tdHtml.replace('{time}', val.time);
		tdHtml = tdHtml.replace('{status}', val.status);
		cloneHtml += '<tr class="selectedTr" data-object="content" data-id="'+ data.id +'">'+ tdHtml + '</tr>';
	});			
	return cloneHtml;			
}				

EOT;

$this->registerJs($str);
?>
<style>
#cloneTrData{
	display: none;
}
</style>
<div class="portlet box grey tabbable">
	<div class="portlet-title">
		<div class="caption"><i class="fa fa-reorder"></i>Portlet</div>
	</div>
	<div class="portlet-body">
		<div class="tabbable portlet-tabs">
			<ul class="nav nav-tabs">
				<li><a href="#gallery" data-toggle="tab">Gallery</a></li>
				<li class="active"><a href="#content" data-toggle="tab">Content</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="content">
					<div class="scroller" style="height:663px">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="กรอก ID หรือชื่อข่าวลงที่นี่" name="q">
						<span class="input-group-btn">
						<a class="btn green findConent" href="javascript:;">ค้นหา</a>
						</span>
						<span class="input-group-btn">
						<a class="btn yellow resetbtn" data-type="content" href="javascript:;">รีเซ็ต</a>
						</span>
					</div>
					
					
						<div class="table">
						<table class="table table-striped table-bordered">
							<tbody class="tbodyData" data-type="content">
								<?php if (!empty($contentList)){?>
								<?php foreach ($contentList as $lst):?>
								<tr data-type="content" data-id="<?php echo $lst->id?>">
									<td><?php echo $lst->id;?>. <?php echo $lst->title?$lst->title:''?></td>
									<td width="200" class="text-right">
										<?php echo date('Y-m-d | H:i', strtotime($lst->publishTime))?> <img src="<?php echo $baseUri?>/assets/img/<?php echo Workflow::$arrStatusIcon[$lst->status]?>">
									</td>
								</tr>
								<?php endforeach;?>
								<?php }else{?>
								<tr>
									<td colspan="2"><h3 class="text-center">ไม่มีข้อมูลที่จะแสดง</h3></td>
								</tr>
								<?php } ?>
							
							</tbody>
							<tr id="cloneTrData" data-object="" data-id="">
									<td>{id}. {title}</td>
									<td width="200" class="text-right">
										{time} {status}
									</td>
								</tr>
						</table>
					</div>
					</div>
				</div>
				<div class="tab-pane" id="gallery">
					<div class="scroller" style="height:663px">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="กรอก ID หรือชื่อข่าวลงที่นี่" name="qGallery">
						<span class="input-group-btn">
						<a class="btn green findGallery" href="javascript:;">ค้นหา</a>
						</span>
						<span class="input-group-btn">
						<a class="btn yellow resetbtn" data-type="gallery" href="javascript:;">รีเซ็ต</a>
						</span>
					</div>
					
					
						<div class="table">
						<table class="table table-striped table-bordered">
							<tbody class="tbodyData" data-type="gallery">
								<?php if (!empty($galleryList)){?>
								<?php foreach ($galleryList as $lst):?>
								<tr data-type="gallery" data-id="<?php echo $lst->id?>">
									<td><?php echo $lst->id;?>. <?php echo $lst->title?$lst->title:''?></td>
									<td width="200" class="text-right">
										<?php echo date('Y-m-d | H:i', strtotime($lst->publishTime))?> <img src="<?php echo $baseUri?>/assets/img/<?php echo Workflow::$arrStatusIcon[$lst->status]?>">
									</td>
								</tr>
								<?php endforeach;?>
								<?php }else{?>
								<tr>
									<td colspan="2"><h3 class="text-center">ไม่มีข้อมูลที่จะแสดง</h3></td>
								</tr>
								<?php } ?>
							
							</tbody>
							<tr id="cloneTrData" data-object="" data-id="">
									<td>{id}. {title}</td>
									<td width="200" class="text-right">
										{time} {status}
									</td>
								</tr>
						</table>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>