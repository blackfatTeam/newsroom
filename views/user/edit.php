<?php
use yii\bootstrap\Html;
use yii\base\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\lib\Workflow;
use app\lib\Auth;
use app\models\User;

$baseUri = Yii::getAlias('@web');
$str = <<<EOT
$('.resetPassBtn').on('click',function(){
	if(confirm('คุณต้องการ reset password ใช่หรือไม่')){
		id = $(this).data('id');
		$('input[name="op"]').val('resetpass');
		$('#contentForm').submit();
	}
	return false;
});
EOT;

$this->registerJs($str);

$jProvinces = json_encode($provinces);
$str = <<<EOT
province = $jProvinces;

	 $('#tagSug').select2({
	 		//tags: true,
	 		//multiple: true,
			data: province,
			maximumSelectionLength: 1
        });


EOT;
$this->registerJs($str);
$css = <<<EOT

EOT;
$this->registerCss($css);
$this->title = $user->username;

$this->params['breadcrumbs'][] = ['label' => 'list', 'url' => ['user/list']];
$this->params['breadcrumbs'][] = ['label' => $user->username];
?>

<?php $form = ActiveForm::begin(['id'=>'contentForm']); ?>

<div class="col-md-12">
	<div class="portlet box grey tabbable">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-reorder"></i>
				User Edit
			</div>
			<div class="actions">
			<?php if(\yii::$app->user->can(Auth::ADMIN)){?>
				<a class="btn btn-danger resetPassBtn" href="javascript:;"><i class="fa fa-refresh"></i> Reset Password</a>
			<?php }?>
			</div>
		</div>
		<div class="portlet-body">
									
			<div class="form-body">
			<?php 
			$disp=true;
			if($arrSetting['edit.username']){
				$disp=false;
			}
			$disp2=true;
			if($arrSetting['edit.content']){
				$disp2=false;
			}
			$disp3=true;
			if($arrSetting['edit.password']){
				$disp3=false;
			}


			?>
				<div class="form-group">
					<label>Username</label>
					<?= Html::activeInput('text', $user, 'username',['class'=>'form-control','readonly'=>$disp, 'required' => 'required'])?>
					<span class="help-block pull-right"> </span>
				</div>
				<div class="form-group">
					<label>Password</label>
					<?= Html::textInput('password','',['class'=>'form-control','readonly'=>$disp3,'placeholder'=>'********', 'required' => 'required'])?>
					<span class="help-block pull-right"> </span>
				</div>
				<div class="form-group">
					<label>First Name</label>
					<?= Html::activeInput('text', $user, 'firstName',['class'=>'form-control','readonly'=>$disp2, 'required' => 'required'])?>
				</div>
				<div class="form-group">
					<label>Last Name</label>
					<?= Html::activeInput('text', $user, 'lastName',['class'=>'form-control','readonly'=>$disp2, 'required' => 'required'])?>
				</div>
				<?php if($arrSetting['view.role']){?>
				<div class="form-group">
					<label>Position</label>
					<?= Html::activeDropDownList($user, 'role', ['' => 'กรูณาเลือกตำแหน่ง']+Auth::$arrUserRole,['class'=>'form-control', 'required' => 'required'])?>
				</div>
				<?php }?>
				<div class="form-group">
					<label>จังหวัดที่สังกัด</label>
					<?php echo Html::activeDropDownList($user, 'web', ['' => 'กรูณาเลือกจังหวัด']+Workflow::$arrWeb,['class'=>'form-control', 'required' => 'required'])?>
					<?php //echo Html::activeHiddenInput($user, 'web',['class'=>'form-control select2','id'=>'tagSug', 'required' => 'required'])?>
				</div>
				<div class="form-group">
					<label>โต๊ะ</label>
					<?= Html::activeDropDownList($user, 'section', ['' => 'กรูณาเลือกโต๊ะข่าว'] + $arrCate,['class'=>'form-control', 'required' => 'required'])?>
				</div>
				<?php if($arrSetting['view.status']){?>
				<div class="form-group">
					<label>Status</label>
					<?= Html::activeDropDownList($user, 'status', [Workflow::STATUS_PUBLISHED=>'เปิด',Workflow::STATUS_REJECTED=>'ปิด'],['class'=>'form-control', 'required' => 'required'])?>
				</div>
				<?php }?>
				
			</div>
			
			<div class="form-actions text-center">                           
				<a type="button" class="btn default" href="<?= Url::toRoute('user/list')?>">ยกเลิก</a>  
				<button type="submit" class="btn green">บันทึก</button>                            
			</div>
			
		</div>
	</div>
</div>
<?= Html::hiddenInput('id',$user->id);?>
<?= Html::hiddenInput('op','');?>
<?php ActiveForm::end(); ?>
