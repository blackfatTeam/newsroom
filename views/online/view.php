<?php 
use app\lib\OnlineConfig;
use yii\helpers\Url;
$baseUri = Yii::getAlias('@web');
?>
<div class="portlet box grey">
	<div class="portlet-title">
		<div class="caption"><i class="fa fa-reorder"></i> จัดการข่าวในหน้า <?php echo $web?> </div>
		<div class="tools">
			<a href="javascript:;" class="collapse"></a>
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			<?php $arrSection = array_splice($arrItem, 0,2);?>
			<div class="col-md-4">
				<?php foreach ($arrSection as $key => $lst):?>
				<div class="portlet box grey">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-edit"></i> <?php echo OnlineConfig::$arrSection[$key]['title']?> (<?php echo count($lst)?> of <?php echo OnlineConfig::$arrSection[$key]['limit']?>)</div>
						<div class="actions">
							<a href="<?= Url::toRoute(['online/edit', 'section' => $key, 'web' => $web])?>" class="btn green"><i class="fa fa-pencil"></i> แก้ไข</a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="table">
					<table class="table table-striped table-bordered">
						<tbody class="tbodySelect">
							<?php if (!empty($lst)){?>
							<?php foreach ($lst as $data):?>
							<tr data-object="content" data-id="<?php echo $data['id']?>">
								<td width="100">
									<?php echo $data['img']?>
								</td>
								<td>
									<p><?php echo $data['id']?>. <?php echo $data['title']?></p>
									<p>
									<div class="pull-left">
										<?php echo $data['time']?>
									</div>
									</p>
								</td>
							</tr>
							<?php endforeach;?>
							<?php }else{?>
							<tr>
								<td colspan="2" height="100">
									<h3 class="text-center">ยังไม่ได้เลือกข่าว</h3>
								</td>
							</tr>
							<?php }?>
						</tbody>
						
					</table>
				</div>
					</div>
				</div>
				<?php endforeach;?>
			</div>
			
		
			<?php $arrSection = array_splice($arrItem, 0,2);?>
			<div class="col-md-4">
				<?php foreach ($arrSection as $key => $lst):?>
				<div class="portlet box grey">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-edit"></i> <?php echo OnlineConfig::$arrSection[$key]['title']?> (<?php echo count($lst)?> of <?php echo OnlineConfig::$arrSection[$key]['limit']?>)</div>
						<div class="actions">
							<a href="<?= Url::toRoute(['online/edit', 'section' => $key, 'web' => $web])?>" class="btn green"><i class="fa fa-pencil"></i> แก้ไข</a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="table">
					<table class="table table-striped table-bordered">
						<tbody class="tbodySelect">
							<?php if (!empty($lst)){?>
							<?php foreach ($lst as $data):?>
							<tr data-object="content" data-id="<?php echo $data['id']?>">
								<td width="100">
									<?php echo $data['img']?>
								</td>
								<td>
									<p><?php echo $data['id']?>. <?php echo $data['title']?></p>
									<p>
									<div class="pull-left">
										<?php echo $data['time']?>
									</div>
									</p>
								</td>
							</tr>
							<?php endforeach;?>
							<?php }else{?>
							<tr>
								<td colspan="2" height="100">
									<h3 class="text-center">ยังไม่ได้เลือกข่าว</h3>
								</td>
							</tr>
							<?php }?>
						</tbody>
						
					</table>
				</div>
					</div>
				</div>
				<?php endforeach;?>
			</div>
			
			<?php $arrSection = array_splice($arrItem, 0,3);?>
			<div class="col-md-4">
				<?php foreach ($arrSection as $key => $lst):?>
				<div class="portlet box grey">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-edit"></i> <?php echo OnlineConfig::$arrSection[$key]['title']?> (<?php echo count($lst)?> of <?php echo OnlineConfig::$arrSection[$key]['limit']?>)</div>
						<div class="actions">
							<a href="<?= Url::toRoute(['online/edit', 'section' => $key, 'web' => $web])?>" class="btn green"><i class="fa fa-pencil"></i> แก้ไข</a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="table">
					<table class="table table-striped table-bordered">
						<tbody class="tbodySelect">
							<?php if (!empty($lst)){?>
							<?php foreach ($lst as $data):?>
							<tr data-object="content" data-id="<?php echo $data['id']?>">
								<td width="100">
									<?php echo $data['img']?>
								</td>
								<td>
									<p><?php echo $data['id']?>. <?php echo $data['title']?></p>
									<p>
									<div class="pull-left">
										<?php echo $data['time']?>
									</div>
									</p>
								</td>
							</tr>
							<?php endforeach;?>
							<?php }else{?>
							<tr>
								<td colspan="2" height="100">
									<h3 class="text-center">ยังไม่ได้เลือกข่าว</h3>
								</td>
							</tr>
							<?php }?>
						</tbody>
						
					</table>
				</div>
					</div>
				</div>
				<?php endforeach;?>
			</div>
		
		</div>
	</div>
</div>