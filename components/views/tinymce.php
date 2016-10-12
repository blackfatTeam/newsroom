<?php
use yii\base\View;
use yii\helpers\Url;

$baseUri = Yii::getAlias('@web');
//var_dump($baseUri);exit;
$str = <<<EOT

EOT;
$this->registerJs($str,\yii\web\View::POS_END);
$css = <<<EOT
.modal {
  height: 100%;

  .modal-body {
    height: 80%;
    overflow-y: scroll;
  }
}
i.gallery-icon{background-image: url('$baseUri/assets/tinymce/cms/icon/icon-gallery-view.png'); width: 16px; height: 16px;}
i.porson-icon{background-image: url('$baseUri/assets/tinymce/cms/icon/icon-person.png'); width: 16px; height: 16px;}
i.youtube-embed{background-image: url('$baseUri/assets/tinymce/cms/icon/icon-youtube.jpg'); width: 16px; height: 16px;}
i.facebook-icon{background-image: url('$baseUri/assets/tinymce/cms/icon/icon-facebook.png'); width: 16px; height: 16px;}
i.instagram-icon{background-image: url('$baseUri/assets/tinymce/cms/icon/icon-instagram.gif'); width: 16px; height: 16px;}
i.twitter-icon{background-image: url('$baseUri/assets/tinymce/cms/icon/icon-twitter.png'); width: 16px; height: 16px;}
EOT;
$this->registerCss($css);
//$this->registerCssFile($baseUri . '/assets/tinymce/cms/bootstrap-modal/css/bootstrap-modal-bs3patch.css');
$this->registerJsFile($baseUri.'/assets/tinymce/tinymce.min.js',['position'=>\yii\web\View::POS_END]);
$this->registerJsFile($baseUri.'/assets/tinymce/cms/tinymce_init.js',['position'=>\yii\web\View::POS_END]);
$this->registerJsFile($baseUri.'/assets/tinymce/cms/popup-twitter.js');
//$this->registerJsFile($baseUri.'/assets/tinymce/cms/bootstrap-modal/js/bootstrap-modal.js');
//$this->registerJsFile($baseUri.'/assets/tinymce/cms/bootstrap-modal/js/bootstrap-modalmanager.js');
?>
<div class="clearfix"></div>

<div class="modal fade" id="embeded-media-items" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog modal-wide">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3>แทรกภาพประกอบ</h3>
			</div>
			<div class="modal-body">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>รหัส</th>
							<th>รูป</th>
							<th>Title</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr style="display:none;" data-id="{Id}">
							<td>{id}</td>
							<td>
								<a href="javascript:;" class="pull-left"> <img height="60" alt="" src="{thumbnail}"> </a>
							</td>
							<td>{title}</td>
							<td>
								<a href="javascript:;" data-refId="{id}" data-source="{fullPath}" class="media-select btn default">
								<i class="fa fa-edit"></i> เลือก</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>