<section class="content-header">
  	<h1>
    	<?php echo $module ?>
  	</h1>
  	<ol class="breadcrumb">
    	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="#"><?php echo $module; ?></a></li>
    	<li class="active"><?php echo $subMenu; ?></li>
  	</ol>
</section>
<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="pull-right">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-company" data-backdrop='static' data-keyboard='false'>
                    Add Company
                </button>
            </div>
        </div>

        <div class="box-body">
          	<table id="dt-company" class="table table-striped table-hover table1">
                <thead>
                	<tr>
                		<th>Company Name</th>
                		<th>Action</th>
                	</tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
   	<!-- /.box -->

</section>

<div id="update-company" class="modal fade">
    <div class="modal-dialog" style="width: 37%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Update Company</h4>
            </div>
            <div class="modal-body">
                <div class="update-company"></div>
            </div>
            <div class="modal-footer">
                <span class="loadingSave"></span>
                <button type="button" class="btn btn-primary update_company">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>

<div id="add-company" class="modal fade">
    <div class="modal-dialog" style="width: 37%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Company</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Company</label>
                    <div class="input-group">
                        <input class="form-control" type="text" name="company" style="text-transform:uppercase" autocomplete="off" onkeyup="inputField(this.name)">
                        <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="loadingSave"></span>
                <button type="button" class="btn btn-primary add_company">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
