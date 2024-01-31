<style type="text/css">
  table.dataTable tr th.select-checkbox.selected::after {
    content: '\2714';
    margin-top: -11px;
    margin-left: -4px;
    text-align: center;
    text-shadow: 1px 1px #b0bed9, -1px -1px #b0bed9, 1px -1px #b0bed9, -1px 1px #b0bed9;
  }

  table.dataTable thead tr th.select-checkbox, table.dataTable tbody th.select-checkbox {
     position: relative; 
  }

  table.dataTable thead tr th.select-checkbox:before, table.dataTable tbody th.select-checkbox:before {
    content: ' ';
    margin-top: -6px;
    margin-left: -6px;
    border: 1px solid black;
    border-radius: 3px;
}
table.dataTable thead tr th.select-checkbox:before, table.dataTable thead tr th.select-checkbox:after, table.dataTable tbody th.select-checkbox:before, table.dataTable tbody th.select-checkbox:after {
    display: block;
    position: absolute;
    top: 1.2em;
    left: 50%;
    width: 12px;
    height: 12px;
    box-sizing: border-box;
}

table.dataTable thead tr th.select-checkbox:before, table.dataTable thead tr th.select-checkbox:after, table.dataTable tbody th.select-checkbox:before, table.dataTable tbody th.select-checkbox:after {
    display: block;
    position: absolute;
    top: 1.2em;
    left: 50%;
    width: 12px;
    height: 12px;
    box-sizing: border-box;
}
</style>
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

  <?php 

        $server = 'talibon_server';

        if (!empty($_GET['server'])) {
            $server = $_GET['server'];
        }
  ?>

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <form>
                <div class="form-group row">
                    <label for="filter" class="col-sm-1 col-form-label">Server:</label>
                    <div class="col-sm-2">
                        <select id="filter_server" class="form-control input-sm" onchange="chosen_server(this.value)">
                              <option value="talibon_server" <?php if ($server == 'talibon_server') {
                                echo "selected=''";
                              } ?>>Alturas Talibon</option>
                              <option value="tubigon_server" <?php if ($server == 'tubigon_server') {
                                echo "selected=''";
                              } ?>>Alturas Tubigon</option>
                        </select>
                    </div>
                  </div>
            </form>
        </div>

        <div class="box-body">
          	<table id="example" class="display table table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Name</th>
                        <th>Store</th>
                        <th>Department</th>
                        <th>Start Date</th>
                        <th>EOC Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button class="btn btn-sm btn-primary update_server"><span class="display_msg">Update Records</span></button>
        </div>
    </div>
   	<!-- /.box -->

</section>
<script type="text/javascript">
  function chosen_server(server) {

    window.location = "?p=server_update&&module=Promo&&server="+server;
  }
</script>