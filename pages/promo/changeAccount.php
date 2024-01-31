<section class="content-header">
  	<h1>
    	<?php echo $subMenu; ?>
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
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Username</h3>
                </div>
                <form action="" id="dataUsername" method="post">
                    <div class="box-body">
                        <p><i><span class="text-red">Note:</span> <code>Username should be unique</code>, and must contain only letters, numbers and underscores! </i></p>
                        <div class="form-group"> <i class="text-red">*</i>
                           <label>Current Username</label>
                           <input type="text" name="currentUsername" class="form-control" required="" pattern="\w+" disabled="" value="<?php echo $_SESSION['username']; ?>">
                        </div>
                        <div class="form-group"> <i class="text-red">*</i>
                           <label>New Username</label>
                           <input type="text" name="newUsername" class="form-control" required="" pattern="\w+" autocomplete="off">
                        </div>
                        <div class="form-group"> <i class="text-red">*</i>
                           <label>Re-type New Username</label>
                           <input type="text" name="confirmUsername" class="form-control" required="" pattern="\w+" autocomplete="off">
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Password</h3>
                </div>
                <form action="" id="dataPassword" method="post">
                    <div class="box-body">
                        <p><i><span class="text-red">Note:</span> <code>Password is alphanumeric</code>. Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters. </i></p>
                        <div class="form-group"> <i class="text-red">*</i>
                           <label>Current Password</label>
                           <input type="password" name="currentPassword" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required="required"> 
                        </div>
                        <div class="form-group"> <i class="text-red">*</i>
                           <label>New Password</label>
                           <input type="password" name="newPassword" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required="required">
                        </div>
                        <div class="form-group"> <i class="text-red">*</i>
                           <label>Re-type New Password</label>
                           <input type="password" name="confirmPassword" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required="required">
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <input  type="submit" class="btn btn-primary" value="Submit">
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
    
</section>
<script type="text/javascript">
    
    function submit(){


    }
</script>