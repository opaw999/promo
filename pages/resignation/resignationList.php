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
    <div class="box">
        <div class="box-header with-border">
          	<h3 class="box-title"><?php echo $subMenu; ?></h3>
        </div>

        <div class="box-body">
          	<table id="resignationList" class="table table-striped table-hover table1">
                <thead>
                	<tr>
                		<th>Name</th>
                		<th>Effectivity</th>
                        <th>AddedBy</th>
                        <th>DateUpdated</th>
                        <th>Remarks</th>
                		<th>Letter</th>
                	</tr>
                </thead>
                <tfoot>
                	<tr>
                		<th>Name</th>
                		<th>Effectivity</th>
                        <th>AddedBy</th>
                        <th>DateUpdated</th>
                        <th>Remarks</th>
                		<th>Letter</th>
                	</tr>
                </tfoot>
            </table>
        </div>

        <!-- /.box-body -->
    </div>
   	<!-- /.box -->

</section>

<div id="resignationLetter" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Resignation Letter</h4>
            </div>
        <div class="modal-body">
            <div class="resignationLetter"></div>
        </div>
        <div class="modal-footer">
            <span class="loadingSave"></span>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div id="upload_resignationLetter" class="modal fade">
    <div class="modal-dialog modal-md">
        <form id="dataUploadResignationLetter" action="" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-light-blue color-palette">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Upload Resignation Letter</h4>
                </div>
            <div class="modal-body">
                <div class="upload_resignationLetter"></div>
            </div>
            <div class="modal-footer">
                <span class="loadingSave"></span>
                <button type="submit" class="btn btn-primary">Upload</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    
    function resignationLetter(empId,terminationNo){

        $("#resignationLetter").modal({
            backdrop: 'static',
            keyboard: false
          });

        $("#resignationLetter").modal("show");

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=resignationLetter",
            data : { empId:empId, terminationNo:terminationNo },
            success : function(data){

                data = data.trim();
                $(".resignationLetter").html("<center><img class='img-responsive' src='"+data+"' alt='Photo'></center>");
            }
        });
    }

    function uploadResignationLetter(empId,terminationNo) {

        $("#upload_resignationLetter").modal({
            backdrop: 'static',
            keyboard: false
          });

        $("#upload_resignationLetter").modal("show");

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=uploadResignationLetter",
            data : { empId:empId, terminationNo:terminationNo },
            success : function(data){

                $("div.upload_resignationLetter").html(data);
            }
        });
    }
</script>