<?php 

if(!isset($_SESSION['username'])){
    header("location: index.php");
}
$username = $_SESSION['username'];
$user_check_query = "SELECT * FROM user_table WHERE user_name='$username'";
$result = mysqli_query($db, $user_check_query);
$user = mysqli_fetch_assoc($result);
?>
<div class="containter">
		<div class="d-flex justify-content-center">
			<br /><br />
			<span id="message"></span>
			<div class="card" style="width:30%;margin-top:50px;margin-bottom: 100px;">
        		<div class="card-header"><h4>Profile</h4></div>
        		<div class="card-body">
				<div style="text-align:center;">
				<img src="./uploads/<?php echo $user["user_image"]; ?>" class="img-thumbnail" width="200" />
				</div>
        			<form method="post" id="profile_form">
        				<div class="form-group">
					        <label>Username</label>
					        <input type="text" name="user_name" id="user_name" class="form-control" value="<?php echo $_SESSION["username"]; ?>" disabled />
					    </div>
                        <div class="form-group">
					        <label>Email Address</label>
					        <input type="text" name="email" id="email" class="form-control" value="<?php echo $user['user_email_address'] ; ?>" disabled />
					    </div>
					    <div class="form-group">
					        <label>Select Gender</label>
					        <select name="user_gender" id="user_gender" class="form-control" disabled>
					          	<option value="Male">Male</option>
					          	<option value="Female">Female</option>
					        </select>
					    </div>
					    <div class="form-group">
					        <label>Enter Address</label>
					        <textarea name="user_address" id="user_address" class="form-control"><?php echo $user["user_address"]; ?></textarea>
					    </div>
					    <div class="form-group">
					        <label>Enter Mobile Number</label>
					        <input type="text" name="user_mobile_no" id="user_mobile_no" class="form-control" value="<?php echo $user["user_mobile_no"]; ?>" />
					    </div>
					    <div class="form-group">
					        <label>Select Profile Image - </label>
					        <input type="file" name="user_image" id="user_image" accept="image/*" /><br />
					        <input type="hidden" name="hidden_user_image" value="<?php echo $user["user_image"]; ?>" />
					    </div>
					    <br />
					    <div class="form-group" align="center">
					        <input type="hidden" name="page" value="profile" />
					        <input type="hidden" name="action" value="profile" />
					        <input type="submit" name="user_profile" id="user_profile" class="btn btn-info" value="Save" />
					    </div>
					    
          			</form>
        		</div>
      		</div>
      		<br /><br />
      		<br /><br />
		</div>
	</div>

</body>

</html>

<script>

$(document).ready(function(){
	
	$('#profile_form').on('submit', function(event){

		event.preventDefault();

		$('#user_name').attr('required', 'required');

		$('#user_address').attr('required', 'required');

		$('#user_mobile_no').attr('required', 'required');

		$('#user_image').attr('required', 'required');

		$('#user_image').attr('accept', 'image/*');

			$.ajax({
				url:"dbconfig.php",
				method:"POST",
				data: new FormData(this),
				dataType:"json",
				contentType: false,
				cache: false,
				processData:false,
				success:function(data)
				{
					if(data.success)
					{
						location.reload(true);
					}
					else
					{
						$('#message').html('<div class="alert alert-danger">'+data.error+'</div>');
					}
					$('#user_profile').attr('disabled', false);
					$('#user_profile').val('Save');
				}
			});
	});
});

</script>