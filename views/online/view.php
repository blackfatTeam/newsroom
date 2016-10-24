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
			<?php $arrSection = array_splice(OnlineConfig::$arrSection, 0,2);?>
			<div class="col-md-4">
				<?php foreach ($arrSection as $key => $lst):?>
				<div class="portlet box grey">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-edit"></i> <?php echo $lst['title']?> (3 of 6)</div>
						<div class="actions">
							<a href="<?= Url::toRoute(['online/edit', 'section' => $key, 'web' => $web])?>" class="btn green"><i class="fa fa-pencil"></i> แก้ไข</a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="table">
					<table class="table table-striped table-bordered">
						<tbody class="tbodySelect">

							<tr class="selectedTr" data-object="content" data-id="1">
								<td width="100">
									<a href="javascript:;" class="pull-left"> <img class="img-responsive" src="<?php echo $baseUri?>/assets/img/thumbnail.jpg"/>
									</a>
								</td>
								<td>
									<p>1001. Gems Pavilion ฉลองเปิด Iconic Flagship Boutique</p>
									<p><a href="javascript:;"> <i class="fa fa-pencil" title="แก้ไจ"></i> </a> <a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
								</td>
							</tr>
							<tr class="selectedTr" data-object="content" data-id="1">
								<td width="100">
									<a href="javascript:;" class="pull-left"> <img class="img-responsive" src="<?php echo $baseUri?>/assets/img/thumbnail.jpg"/>
									</a>
								</td>
								<td>
									<p>1001. Gems Pavilion ฉลองเปิด Iconic Flagship Boutique</p>
									<p><a href="javascript:;"> <i class="fa fa-pencil" title="แก้ไจ"></i> </a> <a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
								</td>
							</tr>
							<tr class="selectedTr" data-object="content" data-id="1">
								<td width="100">
									<a href="javascript:;" class="pull-left"> <img class="img-responsive" src="<?php echo $baseUri?>/assets/img/thumbnail.jpg"/>
									</a>
								</td>
								<td>
									<p>1001. Gems Pavilion ฉลองเปิด Iconic Flagship Boutique</p>
									<p><a href="javascript:;"> <i class="fa fa-pencil" title="แก้ไจ"></i> </a> <a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
								</td>
							</tr>
							

						</tbody>
						
					</table>
				</div>
					</div>
				</div>
				<?php endforeach;?>
			</div>
			
			<?php $arrSection = array_splice(OnlineConfig::$arrSection, 0,2);?>
			<div class="col-md-4">
				<?php foreach ($arrSection as $lst):?>
				<div class="portlet box grey">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-edit"></i> <?php echo $lst['title']?> (3 of 6)</div>
						<div class="actions">
							<a href="#" class="btn green"><i class="fa fa-pencil"></i> แก้ไข</a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="table">
					<table class="table table-striped table-bordered">
						<tbody class="tbodySelect">

							<tr class="selectedTr" data-object="content" data-id="1">
								<td width="100">
									<a href="javascript:;" class="pull-left"> <img class="img-responsive" src="<?php echo $baseUri?>/assets/img/thumbnail.jpg"/>
									</a>
								</td>
								<td>
									<p>1001. Gems Pavilion ฉลองเปิด Iconic Flagship Boutique</p>
									<p><a href="javascript:;"> <i class="fa fa-pencil" title="แก้ไจ"></i> </a> <a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
								</td>
							</tr>
							<tr class="selectedTr" data-object="content" data-id="1">
								<td width="100">
									<a href="javascript:;" class="pull-left"> <img class="img-responsive" src="<?php echo $baseUri?>/assets/img/thumbnail.jpg"/>
									</a>
								</td>
								<td>
									<p>1001. Gems Pavilion ฉลองเปิด Iconic Flagship Boutique</p>
									<p><a href="javascript:;"> <i class="fa fa-pencil" title="แก้ไจ"></i> </a> <a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
								</td>
							</tr>
							<tr class="selectedTr" data-object="content" data-id="1">
								<td width="100">
									<a href="javascript:;" class="pull-left"> <img class="img-responsive" src="<?php echo $baseUri?>/assets/img/thumbnail.jpg"/>
									</a>
								</td>
								<td>
									<p>1001. Gems Pavilion ฉลองเปิด Iconic Flagship Boutique</p>
									<p><a href="javascript:;"> <i class="fa fa-pencil" title="แก้ไจ"></i> </a> <a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
								</td>
							</tr>
							

						</tbody>
						
					</table>
				</div>
					</div>
				</div>
				<?php endforeach;?>
			</div>
			
			<?php $arrSection = array_splice(OnlineConfig::$arrSection, 0,2);?>
			<div class="col-md-4">
				<?php foreach ($arrSection as $lst):?>
				<div class="portlet box grey">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-edit"></i> <?php echo $lst['title']?> (3 of 6)</div>
						<div class="actions">
							<a href="#" class="btn green"><i class="fa fa-pencil"></i> แก้ไข</a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="table">
					<table class="table table-striped table-bordered">
						<tbody class="tbodySelect">

							<tr class="selectedTr" data-object="content" data-id="1">
								<td width="100">
									<a href="javascript:;" class="pull-left"> <img class="img-responsive" src="<?php echo $baseUri?>/assets/img/thumbnail.jpg"/>
									</a>
								</td>
								<td>
									<p>1001. Gems Pavilion ฉลองเปิด Iconic Flagship Boutique</p>
									<p><a href="javascript:;"> <i class="fa fa-pencil" title="แก้ไจ"></i> </a> <a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
								</td>
							</tr>
							<tr class="selectedTr" data-object="content" data-id="1">
								<td width="100">
									<a href="javascript:;" class="pull-left"> <img class="img-responsive" src="<?php echo $baseUri?>/assets/img/thumbnail.jpg"/>
									</a>
								</td>
								<td>
									<p>1001. Gems Pavilion ฉลองเปิด Iconic Flagship Boutique</p>
									<p><a href="javascript:;"> <i class="fa fa-pencil" title="แก้ไจ"></i> </a> <a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
								</td>
							</tr>
							<tr class="selectedTr" data-object="content" data-id="1">
								<td width="100">
									<a href="javascript:;" class="pull-left"> <img class="img-responsive" src="<?php echo $baseUri?>/assets/img/thumbnail.jpg"/>
									</a>
								</td>
								<td>
									<p>1001. Gems Pavilion ฉลองเปิด Iconic Flagship Boutique</p>
									<p><a href="javascript:;"> <i class="fa fa-pencil" title="แก้ไจ"></i> </a> <a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
								</td>
							</tr>
							

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