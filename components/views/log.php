<?php
$baseUri = Yii::getAlias ( '@web' );
$str = <<<EOT


EOT;
$this->registerJs ( $str );

$css = <<<EOT

EOT;
$this->registerCss ( $css );

?>

<div class="col-md-12">
	<table class="table table-hover table-bordered">
		<thead>
			<tr>
				<th class="text-center">user</th>
				<th class="text-center">action</th>
				<th class="text-center">Title</th>
				<th class="text-center">Time</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($results as $result){?>
			<tr  class="text-center">
				<td><?= $result['userId']." : ".$result['fullname'] ?></td>
				<td><?= $result['action'] ?></td>
				<td><?= $result['title'] ?></td>
				<td><?= date('d/m/Y H:i:s',strtotime($result['createTime'])) ?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
</div>
