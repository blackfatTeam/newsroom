<div class="row">
	<div class="col-md-4">
	
		<!-- Drop item -->
		<div class="portlet box grey tabbable">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-reorder"></i>
					ข่าวที่เกี่ยวข้อง
				</div>
			</div>
			<div class="portlet-body">
				<div class="table">
					<table class="table table-striped table-bordered">
						<tbody class="ui-sortable">
							<tr style="display: none;" data-object="content" data-id="{id}">
								<td><a href="javascript:;" class="pull-left"> {thumbnail}
								</a></td>
								<td>
									<p> {id}. {title}</p>
									<p>ดู: {view} <a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
								</td>
							</tr>
							<tr data-object="content" data-id="0">
								<td><a href="#" class="pull-left"> <img src="http://placehold.it/100x60" class="media-object">
								</a></td>
								<td>
									<p>0. [ยังไม่มีรายการ]</p>
								
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
						<tbody class="ui-sortable">
							<tr data-object="content" data-id="{id}">
								<td><a href="javascript:;" class="pull-left">{id} {title}</a></td>
								<td>
									{time}					
								</td>
								<td>
									{status}
								</td>
							</tr>
							<tr data-object="content" data-id="{id}">
								<td><a href="javascript:;" class="pull-left">{id} {title}</a></td>
								<td>
									{time}					
								</td>
								<td>
									{status}
								</td>
							</tr>
							<tr data-object="content" data-id="{id}">
								<td><a href="javascript:;" class="pull-left">{id} {title}</a></td>
								<td>
									{time}					
								</td>
								<td>
									{status}
								</td>
							</tr>
					
						</tbody>
					</table>
				</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	
		<!-- Query news -->
		
	</div>
</div>
