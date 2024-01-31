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
                <button type="button" class="btn btn-primary btn-sm setup_product_btn">
                    Setup Company Product
                </button>
            </div>
        </div>

        <div class="box-body">
          	<table id="dt_company_product" class="table table-striped table-hover table1">
                <thead>
                	<tr>
                		<th>Company Name</th>
                		<th>Product Name</th>
                		<th>Action</th>
                	</tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
   	<!-- /.box -->

</section>

<div class="modal fade" id="setup_company_product">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Setup Company Product</h4>
            </div>
            <div class="modal-body">
                <div class="setup_company_product">
                    <div class="row">
                        <div class="col-md-6 product_list">
                        </div>
                    </div>
                    <br>
                    <div class="products" style="overflow-y: auto; max-height: 350px;">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary submit_products">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>