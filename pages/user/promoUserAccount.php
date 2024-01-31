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
<?php 

    $query = mysql_query("SELECT menuId, menu_name, submenu_name, administrator, promo1, promo2 FROM `promo_menu` ORDER BY menu_name ASC")or die(mysql_error());
?>
<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
          	<h3 class="box-title">HR-Promo Access Roles</h3>
        </div>

        <div class="box-body">
          	<table id="" class="table table-striped table-hover table1">
                <thead>
                	<tr>
                		<th>Module</th>
                		<th>Title</th>
                		<th>Admin</th>
                		<th>PromoIncharge</th>
                        <th>Encoder</th>
                	</tr>
                </thead>
                <tbody>
                    <?php 

                       
                        while ($row = mysql_fetch_array($query)) {
                           
                            echo "<tr>
                                    <td>".$row['menu_name']."</td>
                                    <td>".$row['submenu_name']."</td>
                                    <td><label class='btn btn-success btn-xs btn-block btn-flat'>yes</label></td>"; ?>
                                    <td>
                                        <select name="promo1" onchange="promoAccess(this.name,this.value,'<?php echo $row['menuId']; ?>')">
                                            <option value="yes" <?php if($row['promo1'] == "yes"): echo "selected=''"; endif; ?>>yes</option>
                                            <option value="no" <?php if($row['promo1'] == "no"): echo "selected=''"; endif; ?>>no</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="promo2" onchange="promoAccess(this.name,this.value,'<?php echo $row['menuId']; ?>')">
                                            <option value="yes" <?php if($row['promo2'] == "yes"): echo "selected=''"; endif; ?>>yes</option>
                                            <option value="no" <?php if($row['promo2'] == "no"): echo "selected=''"; endif; ?>>no</option>
                                        </select>
                                    </td><?php
                            echo "</tr>";
                        }
                    ?>
                </tbody>
                <tfoot>
                	<tr>
                		<th>Module</th>
                		<th>Title</th>
                		<th>Admin</th>
                		<th>PromoIncharge</th>
                        <th>Encoder</th>
                	</tr>
                </tfoot>
            </table>
        </div>

        <!-- /.box-body -->
    </div>
   	<!-- /.box -->

</section>
<script type="text/javascript">
    
    function promoAccess(name,access,menuId){

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=promoAccess",
            data : { name:name, access:access, menuId:menuId },
            success : function(data){

                data = data.trim();
                if (data == "Ok") {

                    succSave("Promo User Access Successfully Updated");
                } else {

                    alert(data);
                }
            }
        });
    }
</script>