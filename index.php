<?php
include('dbconfig.php');

// $exam = new Examination;
// session_start();
// $exam->user_session_public();
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: index.php");
    // include('index.php');
}
include('header.php');
?>
<?php 
					@$info=$_GET['info'];
					if($info!="")
					{
											
						 if($info=="about")
						 {
						 include('about.php');
						 }
						 
						 else if($info=="contact")
						 {
						 include('contact.php');
						 }
						 else if($info=="register")
						 {
						 include('register.php');
                         }
                         else if($info=="profile")
						 {
						 include('profile.php');
						 }
                    }
                    else if(isset($_SESSION['username'])){
                        include('dashboard.php');
                    }
					else
					{
				?>
	<header class="d-flex flex-wrap"  style="background-image:url('images/banner.jpg'); background-repeat: no-repeat;
  background-size: 100% 100%; height:700px">
    <div class="container">
    
    <div class="row">
    <div class="col-lg-8">
               				
			<div class="col-sm-10" style="margin-top:25%; margin-bottom:20%; float:left;">
				<h1 style="margin-left:30%">Welcome to <span>ExamOn</span></h1>
                <br>
				<p style="margin-left:20%"> ExamOn is an online examination portal system desin to facilitate the students and teachers to conduct online examination with an ease... </p>
                <br>
                <br>
                <a style="margin-left:20%" type="button" class="btn btn-outline-info"  href="index.php?info=about">Know More</a>
                			</div>
            </div>
            
    <div class="col-lg-4 card col-md-8 shadow-lg" id="log"  style="margin-top:15%; margin-bottom:20%;">
    <span id="message">
            
            
            </span>
            
  <form class=" px-4 py-3" method='post' id='login_form' onsubmit="return loginValidation()">
  <?php include('errors.php'); ?>
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
        <input type="submit" class="btn btn-primary" name="user_login" id="user_login" value="Login" ></input>
                  </div>
  
</form>
</div>			
			</div>
				<?php } ?>
            <!-- </div> -->
            
    </div>
    <!-- /.container -->
    </header>
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
	
    <?php
include('footer.php');
?>	