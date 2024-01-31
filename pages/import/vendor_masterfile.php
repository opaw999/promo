<section class="content-header">
  	<h1>
    	Import
  	</h1>
  	<ol class="breadcrumb">
    	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li class="active">Import</li>
    	<li class="active">Vendor Masterfile</li>
  	</ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">File Upload</h3>
                </div>
                <form id="import_file" action="" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                            <label><span class="text-red">*</span> Department</label>
                            <select name="department" class="form-control" required="">
                                <option value="">Select Department</option>
                                <option value="FIXRITE">FIXRITE</option>
                                <option value="FRESH MARKET">FRESH MARKET</option>
                                <option value="SUPERMARKET">SUPERMARKET</option>
                                <option value="HOME AND FASHION">HOME AND FASHION</option>
                                <option value="MEDICINE PLUS">MEDICINE PLUS</option>
                                <option value="SOD">SOD</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><span class="text-red">*</span> Text/CSV File <i>( <strong class="text-red">Note!</strong> Only text/csv file is allow. )</i></label>
                            <div class="input-group">
                                <input class="form-control" type="file" name="textfile" required="">
                                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary button1">Import Data</button>
                            <button class="btn btn-primary button2" style="display:none;" disabled=""><i class='fa fa-spinner fa-spin'></i> Importing Data ...</button>
                        </div>
                    </div>
                </form>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>
        