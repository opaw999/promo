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
    
    $lastname = "";
    $firstname = "";

    if (!empty($_GET['lastname'])) { ?>

        <script type="text/javascript">
            
            $(".searchTable").show();
        </script><?php

        $lastname = mysql_real_escape_string($_GET['lastname']);
        $firstname = mysql_real_escape_string($_GET['firstname']);
        $fields = "app_id, lastname, firstname, middlename, birthdate, home_address, civilstatus, photo, suffix";
        $query = mysql_query("SELECT $fields FROM `applicant` WHERE lastname = '$lastname' AND firstname LIKE '%$firstname%' ORDER BY firstname")or die(mysql_error());
    
    } else { ?>

        <style type="text/css">
            .searchTable {
                display: none;
            }
        </style><?php
    }
?>
<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <p><i class="text-red">Note:</i> Lastname is required.</p>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label><?php echo $subMenu; ?></label>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <input type="text" name="lastnameApp" class="form-control" placeholder="Lastname" autocomplete="off" style="text-transform: uppercase;" onkeyup="inputField(this.name)" value="<?php echo $lastname; ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="firstnameApp" class="form-control" placeholder="Firstname" autocomplete="off" style="text-transform: uppercase;" value="<?php echo $firstname; ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary" onclick="searchApplicant()"><i class="fa fa-search"></i> Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="searchTable">
                        <table class="table table-hover table2">
                            <thead style="display: none;">
                                <tr>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                    $counter = 0;
                                    while ($row = mysql_fetch_array($query)) {
                                        
                                        $counter++;
                                        $photo = $row['photo'];
                                        if(empty($photo)){
                                            $photo = '../images/users/icon-user-default.png';
                                        }

                                        $fullname = "";
                                        if (!empty($row['suffix'])) {
                                            
                                            $fullname = $row['lastname'].", ".$row['firstname']." ".$row['suffix'].", ".$row['middlename'];
                                        } else {

                                            $fullname = $row['lastname'].", ".$row['firstname']." ".$row['middlename'];
                                        } ?>

                                            <tr>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <img class="" src="<?php echo $photo; ?>" alt="" style="width: 100%; height: 100%;">
                                                        </div>
                                                        <div class="col-md-11">
                                                            <?php 

                                                                echo "<p><span class='text-red'>[".$counter."]</span> <strong>".$row['app_id']." <a href='?p=profile&&module=Promo&&com=".$row['app_id']."'>".utf8_decode(ucwords(strtolower($fullname)))."</a></strong> &nbsp;";
                                                                echo "<br><strong>Civil Status: </strong><i>".$row['civilstatus']."</i> &nbsp;<strong>Birthdate: </strong><i>".date('F d, Y', strtotime($row['birthdate']))."</i> &nbsp;<strong>Home Address: </strong><i>".$row['home_address']."</i></p>";
                                                            ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>
<script type="text/javascript">
    
    function searchApplicant(){

        var firstname = $("[name ='firstnameApp']").val();
        var lastname = $("[name ='lastnameApp']").val();

        if(lastname == ""){

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons:{
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK'){

                        if (lastname == "") {

                            $("[name = 'lastnameApp']").css("border-color","#dd4b39");
                        }
                    }           

                }
            });
        } else {

            var loc = document.location;
            window.location = loc+"&&firstname="+firstname+"&&lastname="+lastname;
        }
    }
</script>