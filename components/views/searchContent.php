<style>
#cloneTrData{
	display: none;
}
</style>
<div class="portlet box grey tabbable">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-reorder"></i>
					ค้นหาข่าว
				</div>
			</div>
			<div class="portlet-body">
				<div class=" portlet-tabs">
					<ul class="nav nav-tabs">
						<li><a href="#" data-toggle="tab">แกลลอรี่</a></li>
						<li class="active"><a href="#portlet_tab1" data-toggle="tab">ข่าว</a></li>					
					</ul>
					<div class="tab-content">
					
					<input type="text" class="form-control" data-placeholder="กรอก ID หรือชื่อข่าวลงที่นี่หหห">
					
						<div class="table">
					<table class="table table-striped table-bordered">
						<tbody class="tbodyData">
							<?php if (!empty($contentList)){?>
							<?php foreach ($contentList as $lst):?>
							<tr data-object="content" data-id="<?php echo $lst->id?>">
								<td><?php echo $lst->id;?>. <?php echo $lst->title?$lst->title:''?></td>
								<td class="text-right">
									<?php echo date('Y-m-d | H:i', strtotime($lst->publishTime))?> published
								</td>
							</tr>
							<?php endforeach;?>
							<?php }else{?>
							<tr>
								<td colspan="2"><h3 class="text-center">ไม่มีข้อมูลที่จะแสดง</h3></td>
							</tr>
							<?php } ?>
						
						</tbody>
						<tr id="cloneTrData" data-object="{type}" data-id="{id}">
								<td><a href="javascript:;" class="pull-left">{id} {title}</a></td>
								<td>
									{time} {status}
								</td>
							</tr>
					</table>
				</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>