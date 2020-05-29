

	<div class="containter">
		<div class=" d-flex justify-content-center">
			<br /><br />
			<div class="card" id="reg" style="margin-top:50px;margin-bottom: 100px;">
        		<div class="card-header"><h4>User Registration</h4></div>
        		<div class="card-body">
        			   <span id="message"></span>
                       <?php include('errors.php'); ?>
                <form method="post" id="user_register_form"  onsubmit="return signupValidation()" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Enter Username</label>
                    <input type="text" name="user_name" id="user_name" class="form-control" /> 
                    <div class="invalid-feedback">
                      Invalid Username
                        </div>
                  </div>
                  <div class="form-group">
                    <label>Enter Email Address</label>
                    <input type="text" name="user_email_address" id="user_email_address" class="form-control"/>
                    <div class="invalid-feedback">
                      Invalid Email Address
                        </div>
                  </div>
                  <div class="form-group">
                    <label>Enter Password</label>
                    <input type="password" name="user_password" id="user_password" class="form-control" />
                    <div class="invalid-feedback">
                      Invalid Password
                        </div>
                  </div>
                  <div class="form-group">
                    <label>Enter Confirm Password</label>
                    <input type="password" name="confirm_user_password" id="confirm_user_password" class="form-control" />
                    <div class="invalid-feedback">
                      Both passwords must match
                        </div>
                  </div>
                  
                  <div class="form-group">
                    <label>Select Gender</label>
                    <select name="user_gender" id="user_gender" class="form-control">
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                  </select> 
                  </div>
                  <div class="form-group">
                    <label>Enter Address</label>
                    <textarea name="user_address" id="user_address" class="form-control"></textarea>
                  </div>
                  <div class="form-group">
                    <label>Enter Mobile Number</label>
                    <input type="text" name="user_mobile_no" id="user_mobile_no" class="form-control" /> 
                  </div>
                  <div class="form-group">
                    <label>Select Profile Image</label>
                    <input type="file" name="image" id="image" accept="image/*"/>
                  </div>
                  <br />
                  <div class="form-group" align="center">
                    <input type="hidden" name='page' value='register' />
                    <input type="hidden" name="action" value="register" />
                    <input type="submit" name="user_register" id="user_register" class="btn btn-info" value="Register" />
                  </div>
                </form>
          			<div align="center">
          				<p>Already have an account? <a href="index.php">Login</a></p>
          			</div>
        		</div>
      		</div>
      		<br /><br />
      		<br /><br />
		</div>
	</div>

</body>

</html>

<script>

    function signupValidation() {
    // $()
	var valid = true;
	
	$("#user_name").removeClass("is-invalid");
	$("#user_email_address").removeClass("is-invalid");
	$("#user_password").removeClass("is-invalid");
	$("#confirm_user_password").removeClass("is-invalid");
	
	var UserName = $("#user_name").val();
	var email = $("#user_email_address").val();
	var Password = $('#user_password').val();
    var ConfirmPassword = $('#confirm_user_password').val();
	var emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

	if (UserName.trim() == "") {
		$("#user_name").addClass("is-invalid");
		valid = false;
	}
	if (email == "") {
		$("#user_email_address").addClass("is-invalid");
		valid = false;
	} else if (email.trim() == "") {
		$("#user_email_address").addClass("is-invalid");
		valid = false;
	} else if (!emailRegex.test(email)) {
                $("#user_email_address").addClass("is-invalid");
		valid = false;
	}
	if (Password.trim() == "") {
        $("#user_password").addClass("is-invalid");
		valid = false;
	}
	if (ConfirmPassword.trim() == "" || Password != ConfirmPassword) {
		$("#confirm_user_password").addClass("is-invalid");
		valid = false;
	}
	if (valid == false) {
		$('.is-invalid').first().focus();
		valid = false;
	}
	return valid;	
}
</script>