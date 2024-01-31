<section class="content-header">
  	<h1>
    	<?php echo $subMenu ?>
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
            <h3 class="box-title"><?php echo $subMenu; ?></h3>
        </div>

        <div class="box-body">
            

          	<table id="failedEpas" class="table table-striped table-hover table1">
                <thead>
                	<tr>
                		<th>Name</th>
                		<th>Startdate</th>
                		<th>EOCdate</th>
                        <?php if($hrCode == "asc") : 

                            $stores = mysql_query("SELECT bunit_acronym FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND status = 'active' AND hrd_location = '$hrCode'")or die(mysql_error());
                            while ($bU = mysql_fetch_array($stores)) {

                                echo "<th width='8%'>".$bU['bunit_acronym']."</th>";
                            }

                        else : 

                            $stores = mysql_query("SELECT bunit_acronym FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND status = 'active' AND hrd_location = '$hrCode'")or die(mysql_error());
                            while ($bU = mysql_fetch_array($stores)) {

                                echo "<th width='8%'>".$bU['bunit_acronym']."</th>";
                            }
                        endif; ?>
                        
                        <th>Action</th>
                	</tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
   	<!-- /.box -->

</section>

<!-- ./Modal -->
<div id="promoAppraisalDetails" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Performance Appraisal For Promodiser/Merchandiser</h4>
            </div>
        <div class="modal-body">
            <div class="promoAppraisalDetails"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    
    function viewdetails(detailsId){

        $("#promoAppraisalDetails").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#promoAppraisalDetails").modal("show");

        $.ajax({
            type : "POST",
            url  : "employee_information_details.php?request=appraisalDetails",
            data : { detailsId:detailsId },
            success : function(data){
            
                $(".promoAppraisalDetails").html(data);  
            }
        });
    }

    function proceedTo(process,empId,recordNo){

        if (process != "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Are you sure you want to Proceed to "+process+"?",
                buttons:{
                    OK: 'Ok',
                    NO : 'Not Now'
                },

                callback: function(button) {
                    if (button == 'OK'){

                        if (process == "Blacklist") {

                            window.location = "?p=addBlacklist&&module=Blacklisted&&empId="+empId
                        } 
                    }           

                }
            });
        }
    }
</script>