<?php
use kartik\date\DatePicker;
use kartik\widgets\TimePicker;
use yii\base\Widget;
use yii\bootstrap\Html;

use app\models\Contents;
use app\lib\Workflow;
use backend\components\Category;
use yii\helpers\Url;

$baseUri = Yii::getAlias('@web');
$tagSearchUri = Url::toRoute(['contents/tagapi']);

$str = <<<EOT

	 $('#tagSug').select2({
	 		tags: true,
	 		multiple: true,
	 		//tokenSeparators: [',', ' '],
		    //minimumInputLength: 2,
		    
		   	createSearchChoice : function (term) { return {id: term, text: term}; },
		   	ajax: {
				url: '$tagSearchUri',
				dataType: 'json',
				data: function (params) {
			      return {
			        q: params
			      };
			    },		
				results: function(data) {	
					return {
						results: $.map(data, function(item) {
							return {
								id: item.value,
								//slug: item.value,
								text: item.value
							};
						}),
					};
				}
			},
			// Take default tags from the input value
		    initSelection: function (element, callback) {
		        var data = [];
		
		        function splitVal(string, separator) {
		            var val, i, l;
		            if (string === null || string.length < 1) return [];
		            val = string.split(separator);
		            for (i = 0, l = val.length; i < l; i = i + 1) val[i] = $.trim(val[i]);
		            return val;
		        }
		
		        $(splitVal(element.val(), ",")).each(function () {
		            data.push({
		                id: this,
		                text: this
		            });
		        });
		
		        callback(data);
		    }, 
			
        });
		

EOT;
$this->registerJs($str);
$css = <<<EOT

EOT;
$this->registerCss($css);

?>

<div class="col-md-4">
	<div class="portlet box grey tabbable">
		<div class="portlet-title">
			<div class="caption"><i class="fa fa-cog"></i>การแสดงผล</div>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="form-body">
				<label>สถานะของข่าว:</label>
				<div class="form-group">
				<?= Html::activeDropDownList($contents, 'status',Workflow::$arrStatusTh,['class'=>'form-control'])?>
					
				</div>
				<div class="form-group">
					<label>เวลาเปิด</label>
				<?php echo DatePicker::widget([
						'name' => 'publishDate',
						'language'=>'th',
						'value'=>date('Y-m-d',strtotime(!empty($contents->publishTime)?$contents->publishTime:date('Y-m-d',time()))),
						'type' => DatePicker::TYPE_COMPONENT_APPEND,
						'options'=>['placeholder'=>'วันที่เผยแพร่'],
						'pluginOptions' => [
								'autoclose'=>true,
								'format' => 'yyyy-mm-dd',
								'todayHighlight'=>true
						]
				]);
					?>
					</div>
				<div class="form-group">
				<?php echo TimePicker::widget([
					    'name' => 'publishTime',
						'value'=>date('H:i:s',strtotime(!empty($contents->publishTime)?$contents->publishTime:date('H:i:s',time()))),
					    'pluginOptions' => [
					        'showSeconds' => true,
					        'showMeridian' => false,
					        'minuteStep' => 1,
					        'secondStep' => 5,
					    ]
					]);
					?>
				</div>
				
				<div class="form-group">
					<label>เวลาปิด</label>
				<?php echo DatePicker::widget([
						'name' => 'expireDate',
						'language'=>'th',
						'value'=>date('Y-m-d',strtotime(!empty($contents->expireTime)?$contents->expireTime:date('Y-m-d',strtotime('+3 month',time())))),
						'type' => DatePicker::TYPE_COMPONENT_APPEND,
						'options'=>['placeholder'=>'วันที่ปิดข่าว'],
						'pluginOptions' => [
								'autoclose'=>true,
								'format' => 'yyyy-mm-dd',
								'todayHighlight'=>true
						]
				]);
					?>
					</div>
				<div class="form-group">
				<?php echo TimePicker::widget([
					    'name' => 'expireTime',
						'value'=>date('H:i:s',strtotime(!empty($contents->expireTime)?$contents->expireTime:date('H:i:s',time()))),
					    'pluginOptions' => [
					        'showSeconds' => true,
					        'showMeridian' => false,
					        'minuteStep' => 1,
					        'secondStep' => 5,
					    ]
					]);
					?>
				</div>
					
					
			</div>
		</div>
	</div>
</div>

<div class="col-md-4">
	<div class="portlet box grey tabbable">
		<div class="portlet-title">
			<div class="caption"><i class="fa fa-cog"></i>ตั้งค่าอื่นๆ</div>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="form-body">
				<div class="form-group">
					<label>ประเภทข่าว</label>
					<?= Html::activeDropDownList($contents, 'categoryId',['']+Workflow::$arrCategory,['class'=>'form-control'])?>
				</div>	
				<div class="form-group">
					<label>รูปแบบ Theme</label>
					<?= Html::activeDropDownList($contents, 'theme',Workflow::$theme,['class'=>'form-control'])?>
				</div>	
	
				<div class="form-group">
					<label>Tags</label>
					<?= Html::activeHiddenInput($contents, 'tags',['class'=>'form-control select2','id'=>'tagSug'])?>
				</div>		
			</div>
		</div>
	</div>
</div>

<div class="col-md-4">
	<div class="portlet box grey tabbable">
		<div class="portlet-title">
			<div class="caption"><i class="fa fa-cog"></i>ชื่อของ URL</div>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="form-body">
				<div class="form-group">
					<label>โพสต์ไตเติ้ล</label>
					<?= Html::activeTextarea($contents, 'postTitle',['maxlength'=>225,'rows'=>4,'class'=>'form-control', 
						'placeholder'=> Workflow::POST_URL.'โพสต์ไตเติ้ล'])?>
					<span class="help-block pull-right"> ระบุคำแสดงแทน เฉพาะโพสต์ไตเติ้ล  เท่านั้น</span>
				</div>	
			</div>
		</div>
	</div>
</div>

