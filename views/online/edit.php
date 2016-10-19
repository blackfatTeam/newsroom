<?php
$baseUri = Yii::getAlias('@web');
use app\components\SearchContent;
?>
<div class="row">
	<div class="col-md-4">
	
		<!-- Drop item -->
		<div class="portlet box grey tabbable">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-reorder"></i>
					บก. แนะนำ (3 of 6)
				</div>
				<div class="actions">
					<a href="javascript:;" class="btn green btn-sm saveRelate" data-id="1"><i class="fa fa-plus"></i> Save</a>
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