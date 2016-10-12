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
		
$('.categoryClick').on('click',function(){
		type = $(this).attr('catetype');
		id = $(this).attr('parentId');

			$("#categoriesuncate").removeAttr('checked');
			$('#categoriesuncate').parent().removeClass('checked');
						
		if(type=='sub'){			
			$('#'+id).attr('checked','checked');
			$('#'+id).parent().addClass('checked');
						
			
		}else if(type=='uncate'){

			$(".categoryClick").removeAttr('checked');
			$('.categoryClick').parent().removeClass('checked');

			$('#categoriesuncate').attr('checked','checked');
			$('#categoriesuncate').parent().addClass('checked');

		}
		if($(".categoryClick[parentid='"+id+"']:checked").length > 0){
			$('#'+id).attr('checked','checked');
			$('#'+id).parent().addClass('checked');
		}		
});

$('.saveBtn').on('click',function(){
		if($(".categoryClick:checked").length <= 0){
			alert('กรุณาเลือก Category');
			return false;
		}
		
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
				<div class="form-group">
					<label>โพสต์ไตเติ้ล</label>
					<?= Html::activeTextarea($contents, 'postTitle',['maxlength'=>225,'rows'=>4,'class'=>'form-control', 
						'placeholder'=> Workflow::POST_URL.'โพสต์ไตเติ้ล'])?>
					<span class="help-block pull-right"> ระบุคำแสดงแทน เฉพาะโพสต์ไตเติ้ล  เท่านั้น</span>
				</div>
				<div class="form-group">
				<?= Html::activeDropDownList($contents, 'status',Workflow::$arrStatusTh,['class'=>'form-control'])?>
					
				</div>
				<div class="form-group">
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
					
					
			</div>
		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="portlet box grey tabbable">
		<div class="portlet-title">
			<div class="caption"><i class="fa fa-cog"></i> หมวดหมู่</div>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="form-body">
				<div class="form-group">
					<label  class="">รายการหมวดหมู่</label>
					<div class="checkbox-list">
						<ul>
						<?php $checked = empty($liveInCate)?true:false;
						?>
							<li>
								<label>
								<?= Html::checkbox('tmp',$checked,['class'=>'categoryClick','id'=>'categoriesuncate','parentId'=>'categoriesuncate','catetype'=>'uncate'])?><span style="color:red;">Uncategory</span>
								</label>
							</li>
						<?php foreach ($arrCategory as $cate){
							$parent = $cate['parent'];
							$sub = $cate['sub'];
							
							$checked = false;
							if(isset($liveInCate[$parent->id])){
								$checked = true;
							}
							$catId = 'categories'.$parent->id;
	
						?>
						
							<li>
								<label>
								<?= Html::checkbox('categories['.$parent->id.']',$checked,['class'=>'categoryClick','id'=>$catId,'parentId'=>$catId,'catetype'=>'parent'])?><?= $parent->title?>
								</label>
								<?php if($sub){?>
								<ul>
									<?php foreach ($sub as $s){
										$checked = false;
										if(isset($liveInCate[$s->id])){
											$checked = true;
										}
									?>
										<li>
											<label>
											<?= Html::checkbox('categories['.$s->id.']',$checked,['class'=>'categoryClick','parentId'=>$catId,'catetype'=>'sub'])?><?= $s->title?>
											</label>
										</li>
									<?php }?>
								</ul>
								<?php }?>
							</li>
						
						<?php }?>
						</ul>
					</div>
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
