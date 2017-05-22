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
			<?php $arrSection = array_splice($arrItem, 0,8);?>
			<div class="col-md-4">
				<?php 
				foreach ($arrSection as $key => $lst):
				$arrColumn = [17,18,19,20];
				$limit = 12;
				/* if (in_array($lst['categoryId'], $arrColumn)){
					$limit = 1;
				} */
				?>
				<div class="portlet box grey">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-edit"></i> <?php echo $lst['title']?> (<?php echo isset($lst['data'])?count($lst['data']):'0'?> of <?php echo $limit ?>)</div>
						<div class="actions">
							<a href="<?= Url::toRoute(['online/edit', 'section' => $lst['categoryId'], 'web' => $web])?>" class="btn green"><i class="fa fa-pencil"></i> แก้ไข</a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="table">
					<table class="table table-striped table-bordered">
						<tbody class="tbodySelect">
							<?php if (!empty($lst['data'])){?>
							<?php foreach ($lst['data'] as $data):?>
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
			
		
			<?php $arrSection = array_splice($arrItem, 0,8);?>
			<div class="col-md-4">
				<?php 
				foreach ($arrSection as $key => $lst):
				$arrColumn = [17,18,19,20];
				$limit = 12;
				/* if (in_array($lst['categoryId'], $arrColumn)){
					$limit = 1;
				} */
				?>
				<div class="portlet box grey">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-edit"></i> <?php echo $lst['title']?> (<?php echo isset($lst['data'])?count($lst['data']):'0'?> of <?php echo $limit?>)</div>
						<div class="actions">
							<a href="<?= Url::toRoute(['online/edit', 'section' => $lst['categoryId'], 'web' => $web])?>" class="btn green"><i class="fa fa-pencil"></i> แก้ไข</a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="table">
					<table class="table table-striped table-bordered">
						<tbody class="tbodySelect">
							<?php if (!empty($lst['data'])){?>
							<?php foreach ($lst['data'] as $data):?>
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
			
			<?php $arrSection = array_splice($arrItem, 0,8);?>
			<div class="col-md-4">
				<?php 
				foreach ($arrSection as $key => $lst):
				$arrColumn = [17,18,19,20];
				$limit = 12;
				/* if (in_array($lst['categoryId'], $arrColumn)){
					$limit = 1;
				} */
				?>
				<div class="portlet box grey">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-edit"></i> <?php echo $lst['title']?> (<?php echo isset($lst['data'])?count($lst['data']):'0'?> of <?php echo $limit?>)</div>
						<div class="actions">
							<a href="<?= Url::toRoute(['online/edit', 'section' => $lst['categoryId'], 'web' => $web])?>" class="btn green"><i class="fa fa-pencil"></i> แก้ไข</a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="table">
					<table class="table table-striped table-bordered">
						<tbody class="tbodySelect">
							<?php if (!empty($lst['data'])){?>
							<?php foreach ($lst['data'] as $data):?>
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