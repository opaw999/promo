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
    <div class="row">
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $subMenu; ?></h3>
                </div>

                <div class="box-body">

                    <input type="hidden" name="hrCode" value="<?php echo $hrCode; ?>">
                    <div class="form-group">
                        <label>Statistics By</label>
                        <input type="text" name="statistics" class="form-control" disabled="" value="Business Unit">
                    </div>
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Prepared By</label>
                        <input type="text" name="preparedBy" class="form-control" style="text-transform: uppercase;" placeholder="Firstname Lastname" onkeyup="inputField(this.name)">
                    </div>
                     <div class="form-group"> <i class="text-red">*</i>
                        <label>Submitted To</label>
                        <input type="text" name="submittedTo" class="form-control" style="text-transform: uppercase;" placeholder="Firstname Lastname" onkeyup="inputField(this.name)">
                    </div>
                </div>
                <div class="box-footer">
                    <button type="button" class="btn btn-primary" onclick="genReport('excel')"> Generate in Excel <img src="../images/icons/excel-xls-icon.png"></button>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>
<script type="text/javascript">

    function genReport(code){

        var hrCode = $("[name = 'hrCode']").val();
        var statistics = $("[name = 'statistics']").val();
        var preparedBy = $("[name = 'preparedBy']").val();
        var submittedTo = $("[name = 'submittedTo']").val();

        if (preparedBy == "" || submittedTo == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons:{
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK'){

                        if (preparedBy == "") {

                            $("[name = 'preparedBy']").css("border-color","#dd4b39");
                        }

                        if (submittedTo == "") {

                            $("[name = 'submittedTo']").css("border-color","#dd4b39");
                        }
                    }           

                }
            });
        } else {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Generate report now?",
                buttons:{
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK'){
                            
                        if (code == "excel") {

                            if (hrCode == "asc") {

                                window.open("pages/reports/statistics_xls.php?statistics="+statistics+"&&preparedBy="+preparedBy+"&&submittedTo="+submittedTo);
                            } else {

                                window.open("pages/reports/ceboStatistics_xls.php?statistics="+statistics+"&&preparedBy="+preparedBy+"&&submittedTo="+submittedTo);
                            }

                        } 
                    }           

                }
            });
        }
    }

</script>