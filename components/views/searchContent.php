<?php
$baseUri = Yii::getAlias('@web');
use app\models\Category;
use app\lib\Workflow;
use yii\helpers\Url;
use yii\helpers\Html;
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
	var section = $(this).attr('data-section');
	doReset(type, section);
	$('input[name=q]').val('');
	$('input[name=qGallery]').val('');
	$('select[name=categoryId]').val('');
});


$('input[name=q]').keypress(function(e) {
    if(e.which == 13) {
        doSearch('content');
    }
});

$('input[name=qGallery]').keypress(function(e) {
    if(e.which == 13) {
        doSearch('gallery');
    }
});

function doSearch(type){
	var q = $('input[name=q]').val();
	var qGallery = $('input[name=qGallery]').val();
	var categoryId = $('select[name=categoryId]').val();
	var arrSelectedTr = $('.selectedTr');
	arrId = [];
	$.each(arrSelectedTr, function( i, tr ) {
		arrId.push($(tr).attr('data-id'));
	});

	if(q.length || qGallery.length || categoryId.length){
		$.get('$url', {
				q: q,
				qGallery: qGallery,
				type: type,
				categoryId: categoryId,
				arrId: arrId
		}).done(function(data) {
			if(typeof data == "string"){
				var data = $.parseJSON(data);
			}
		
			var mainBody = $('.tbodyData[data-type='+type+']');
			if(data.length){
				var resultTr = getItem(data, type);
				$(mainBody).html(resultTr);
			}else{
				var noData = '<td colspan="2" height="100" class="text-center"><h2>ไม่มีข้อมูลที่คุณค้นหา</h2></td>';
				$(mainBody).html(noData);
			}
		});
	}
}		

function doReset(type, section){
	$.get('$urlReset', {
				type: type,
				section: section
		}).done(function(data) {
		if(typeof data == "string"){
			var data = $.parseJSON(data);
		}
		var mainBody = $('.tbodyData[data-type='+type+']');
		if(data.length){
			var resultTr = getItem(data, type);
			$(mainBody).html(resultTr);
		}else{
			var noData = '<td colspan="2" height="100" class="text-center"><h2>ไม่มีข้อมูลที่คุณค้นหา</h2></td>';
			$(mainBody).html(noData);
		}
	});
	
}				
				
function getItem(data, type){
	var cloneDiv = $('#cloneTrData').clone();	
	cloneHtml = '';
	$.each(data, function( i, val ) {
		var tdHtml = $(cloneDiv).html();
		tdHtml = tdHtml.replace('{id}', val.id);
		tdHtml = tdHtml.replace('{title}', val.title);
		tdHtml = tdHtml.replace('{category}', val.category);
		tdHtml = tdHtml.replace('{time}', val.time);
		tdHtml = tdHtml.replace('{status}', val.status);
		cloneHtml += '<tr data-type="'+type+'" data-id="'+ val.id +'">'+ tdHtml + '</tr>';
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
		<div class="caption"><i class="fa fa-reorder"></i>ค้นหาข่าวและแกลอรี่</div>
	</div>
	<div class="portlet-body">
		<div class="tabbable portlet-tabs">
			<ul class="nav nav-tabs">
				<li><a href="#gallery" data-toggle="tab">Gallery</a></li>
				<li class="active"><a href="#content" data-toggle="tab">Content</a></li>
			</ul>
			<div class="tab-content">
				<?= Html::dropDownList('categoryId', $section,[''=> 'เลือกหมวดที่ต้องการค้นหา']+$arrCategory,['class'=>'form-control selectCategory']) ?>
				<div class="tab-pane active" id="content">
					<div class="scroller" style="height:663px">
					
					<div class="input-group">
						<input type="text" class="form-control" placeholder="กรอก ID หรือชื่อข่าวลงที่นี่" name="q">
						<span class="input-group-btn">
						<a class="btn green findConent" href="javascript:;">ค้นหา</a>
						</span>
						<span class="input-group-btn">
						<a class="btn yellow resetbtn" data-type="content" href="javascript:;" data-section="<?php echo $section?$section:'';?>">รีเซ็ต</a>
						</span>
					</div>
					
					
						<div class="table">
						<table class="table table-striped table-bordered">
							<tbody class="tbodyData" data-type="content">
								<?php if (!empty($contentList)){?>
								<?php foreach ($contentList as $lst):
								$category = null;
								if (!empty($lst->categoryId)){
									$category = Category::find()->where(['id'=>$lst->categoryId])->one();
								}
								?>
								<tr data-type="content" data-id="<?php echo $lst->id?>">
									<td><?php echo $lst->id;?>. <?php echo $lst->title?$lst->title:''?></td>
									<td><?php echo $category?$category->name:''?></td>
									<td width="200" class="text-right">
										<?php echo date('Y-m-d | H:i', strtotime($lst->publishTime))?> <img src="<?php echo $baseUri?>/assets/img/<?php echo Workflow::$arrStatusIcon[$lst->status]?>">
									</td>
								</tr>
								<?php endforeach;?>
								<?php }else{?>
								<tr>
									<td colspan="3"><h3 class="text-center">ไม่มีข้อมูลที่จะแสดง</h3></td>
								</tr>
								<?php } ?>
							
							</tbody>
							<tr id="cloneTrData" data-object="" data-id="">
									<td>{id}. {title}</td>
									<td>{category}</td>
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
						<a class="btn yellow resetbtn" data-type="gallery" href="javascript:;" data-section="<?php echo $section?$section:'';?>">รีเซ็ต</a>
						</span>
					</div>
					
					
						<div class="table">
						<table class="table table-striped table-bordered">
							<tbody class="tbodyData" data-type="gallery">
								<?php if (!empty($galleryList)){?>
								<?php foreach ($galleryList as $lst):
								$category = null;
								if (!empty($lst->categoryId)){
									$category = Category::find()->where(['id'=>$lst->categoryId])->one();
								}
								?>
								<tr data-type="gallery" data-id="<?php echo $lst->id?>">
									<td><?php echo $lst->id;?>. <?php echo $lst->title?$lst->title:''?></td>
									<td><?php echo $category?$category->name:''?></td>
									<td width="200" class="text-right">
										<?php echo date('Y-m-d | H:i', strtotime($lst->publishTime))?> <img src="<?php echo $baseUri?>/assets/img/<?php echo Workflow::$arrStatusIcon[$lst->status]?>">
									</td>
								</tr>
								<?php endforeach;?>
								<?php }else{?>
								<tr>
									<td colspan="3"><h3 class="text-center">ไม่มีข้อมูลที่จะแสดง</h3></td>
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