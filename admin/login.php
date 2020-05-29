<?php

//login.php

include('../dbconfig.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/guillaumepotier/Parsley.js@2.9.1/dist/parsley.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../style/style.css" />
</head>
<body>
  
<nav class="navbar navbar-expand-lg navbar-light sticky-top" role="navigation" style="background:#424141">
        <div class="container" >
            
            <div class="navbar-header">
            <a class="navbar-brand" href="/ExamOn/admin/index.php" style="color:#FFFFFF">ExamOn</a>
            </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
      
    	
        <div class="collapse navbar-collapse  justify-content-end" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav ">
                    
					 <li class="nav-item" style="color:#FFFFFF">
                        <a class="nav-link" style="color:#FFFFFF" href="/ExamOn"><i class="fa fa-home fa-fw"></i>View Site</a>
                    </li>
                    </ul>
              </div>

      </nav>
  <div class="container">
      <div class="row">
        <div class="col-md-3">

        </div>
        <div class="col-md-6" style="margin-top:20px;">
          
          <span id="message">
          <?php
          if(isset($_GET['verified']))
          {
            echo '
            <div class="alert alert-success">
              Your email has been verified, now you can login
            </div>
            ';
          }
          ?>
          </span>
          <div class="card">
            <div class="card-header">Admin Login</div>
            <div class="card-body">
            <form class=" px-4 py-3" method='post' id='admin_login_form' onsubmit="return loginValidation()">
            <?php include('../errors.php'); ?>
              <div class="form-group">
      <label for="username">Username</label>
      <input type="text" class="form-control" name="username" id="username" placeholder="Username" >
      <div class="invalid-feedback">
        Please enter valid username!
      </div>
      </div>
  
  <div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="Password">
      <div class="invalid-feedback">
       Password required
      </div>
    </div>
                <div class="form-group">
                  <input type="hidden" name="page" value="login" />
                  <input type="hidden" name="action" value="login" />
                  <input type="submit" name="admin_login" id="admin_login" class="btn btn-info" value="Login" />
                </div>
              </form>
            </div>
          </div>
            
        </div>
        <div class="col-md-3">

        </div>
      </div>
  </div>

</body>
</html>

<script>

function loginValidation() {
	var valid = true;
	
	$("#username").removeClass("is-invalid");
	$("#password").removeClass("is-invalid");
	
	var UserName = $("#username").val();
	
	var Password = $('#password').val();
    

	if (UserName.trim() == "") {
		$("#username").addClass("is-invalid");
		valid = false;
	}
	
	if (Password.trim() == "") {
        $("#password").addClass("is-invalid");
		valid = false;
	}
	if (valid == false) {
		$('.is-invalid').first().focus();
		valid = false;
	}
	return valid;	
}
</script>