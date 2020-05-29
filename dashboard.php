<?php
if(!isset($_SESSION['username'])){
  header("location: index.php");
}

$query = "SELECT online_exam_id, online_exam_title 
    FROM online_exam_table 
    WHERE online_exam_status = 'Created' OR online_exam_status = 'Pending'
    AND online_exam_datetime >'".$current_datetime."' 
    ORDER BY online_exam_title ASC";
$sql = mysqli_query($db, $query);
$result = mysqli_fetch_all($sql, MYSQLI_ASSOC);;
$output = '';
foreach($result as $row)
{
    $output .= '<option value="'.$row["online_exam_id"].'">'.$row["online_exam_title"].'</option>';
}
// Exam Details
$output1='';
if(isset($_POST['exam_list'])){
    $exam_id = $_POST['exam_list'];
    $query = "SELECT * FROM online_exam_table 
              WHERE online_exam_id = '".$exam_id."'
        ";
      
      $result = mysqli_query($db, $query);
      // echo "hi";
      $output1 = '
      <div class="card">
        <div class="card-header">Exam Details</div>
        <div class="card-body">
          <table class="table table-striped table-hover table-bordered">
      ';
      foreach($result as $row)
      {
        $output1 .= '
        <tr>
          <td><b>Exam Title</b></td>
          <td>'.$row["online_exam_title"].'</td>
        </tr>
        <tr>
          <td><b>Exam Date & Time</b></td>
          <td>'.$row["online_exam_datetime"].'</td>
        </tr>
        <tr>
          <td><b>Exam Duration</b></td>
          <td>'.$row["online_exam_duration"].' Minute</td>
        </tr>
        <tr>
          <td><b>Exam Total Question</b></td>
          <td>'.$row["total_question"].' </td>
        </tr>
        <tr>
          <td><b>Marks Per Right Answer</b></td>
          <td>'.$row["marks_per_right_answer"].' Mark</td>
        </tr>
        <tr>
          <td><b>Marks Per Wrong Answer</b></td>
          <td>-'.$row["marks_per_wrong_answer"].' Mark</td>
        </tr>
        ';
        $username = $_SESSION['username'];
        $user_check_query = "SELECT * FROM user_table WHERE `user_name`='$username'";
        $result = mysqli_query($db, $user_check_query);
      // $sql = mysqli_query($db, )
      $user = mysqli_fetch_assoc($result);
        $query = "SELECT * FROM user_exam_enroll_table 
          WHERE `exam_id` = '".$exam_id."' 
          AND `user_id` = '".$user['user_id']."'
          ";
      $result = mysqli_query($db, $query);
      // echo $exam_id;
          if(mysqli_num_rows($result) > 0)
          {
        $enroll_button = '
        <tr>
          <td colspan="2" align="center">
            <button type="button" name="enroll_button" class="btn btn-info">You Already Enroll it</button>
          </td>
        </tr>
        ';
          }
       
        else
        {
          $enroll_button = '
          <tr>
            <td colspan="2" align="center">
              <button type="button" name="enroll_button" id="enroll_button" class="btn btn-warning" data-exam_id="'.$row['online_exam_id'].'">Enroll it</button>
            </td>
          </tr>
          ';
        }
        $output1 .= $enroll_button;
      }
      $output1 .= '</table>';
  }
?>
<header style="background-image:url('images/banner.jpg'); background-repeat: no-repeat;
  background-size: 100% 100%; height:700px">
    <div class="container">
    
    <div class="row">
    <div class="col-lg-12">
               				
			<div class="col-sm-10" style="margin-top:15%; margin-bottom:10%; float:left;">
				<h1 style="margin-left:50%">Welcome <span><?php echo $_SESSION['username']; ?></span></h1>
                <br>
				<p style="margin-left:50%"> ExamOn is an online examination portal system desin to facilitate the students and teachers to conduct online examination with an ease... </p>
                <br>
                <br>
                <a style="margin-left:50%" type="button" class="btn btn-outline-info"  href="index.php?info=about">Know More</a>
            </div>
    </div>
	</div>
	<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6">
            <form id="my-form" method="post">
				<select name="exam_list" id="exam_list" class="form-control input-lg" onchange="myfunction()">
					<option value="">Select Exam</option>
					<?php

                    echo $output;

					?>
				</select>
            </form>
				<br />
				
			</div>
			<div class="col-md-3"></div>
		</div>
            
    </div>
    <!-- /.container -->
    </header>
    <section id='s1' >
    <span id="exam_details">
                <?php

                    echo $output1;

					?>
                </span>
    </section>
       <script>
    function myfunction(){
        console.log('hi');
    document.getElementById("my-form").submit();
}
var scrollPos =  $("#s1").offset().top;
 $(window).scrollTop(scrollPos);
 $(document).on('click', '#enroll_button', function(){
				exam_id = $('#enroll_button').data('exam_id');
				$.ajax({
					url:"dbconfig.php",
					method:"POST",
					data:{action:'enroll_exam', page:'index', exam_id:exam_id},
					beforeSend:function()
					{
						$('#enroll_button').attr('disabled', 'disabled');
						$('#enroll_button').text('please wait');
					},
					success:function()
					{
						$('#enroll_button').attr('disabled', false);
						$('#enroll_button').removeClass('btn-warning');
						$('#enroll_button').addClass('btn-success');
						$('#enroll_button').text('Enroll success');
					}
				});
			});
    </script>
    