<?php

//view_exam.php


include('dbconfig.php');

if(!isset($_SESSION['username'])){
    header("location: index.php");
}
$username = $_SESSION['username'];
    $user_check_query = "SELECT * FROM user_table WHERE `user_name`='$username'";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);
$exam_id = '';
$exam_status = '';
$remaining_minutes = '';
$submission_status = '';

if(isset($_GET['code']))
{
    $query = "SELECT online_exam_id FROM online_exam_table 
		WHERE online_exam_code = '".$_GET['code']."'
		";

		$sql = mysqli_query($db, $query);
		$result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

		foreach($result as $row)
		{
			$exam_id = $row['online_exam_id'];
        }
	$query = "SELECT online_exam_status, online_exam_datetime, online_exam_duration FROM online_exam_table 
	WHERE online_exam_id = '$exam_id'
	";

        $sql = mysqli_query($db, $query);
        $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

	foreach($result as $row)
	{
		$exam_status = $row['online_exam_status'];
		$exam_star_time = $row['online_exam_datetime'];
		$duration = $row['online_exam_duration'] . ' minute';
		$exam_end_time = strtotime($exam_star_time . '+' . $duration);

		$exam_end_time = date('Y-m-d H:i:s', $exam_end_time);
        $remaining_sec = strtotime($exam_end_time) - time();
        $remaining_hrs = intval($remaining_sec/3600);
        $remaining_minutes = intval(($remaining_sec - $remaining_hrs*3600)/60);
        $remaining_secs = $remaining_sec - $remaining_hrs*3600 - $remaining_minutes*60;
    }
    $query = "SELECT submission FROM user_exam_enroll_table
    WHERE user_id = '".$user['user_id']."'
    AND exam_id = '$exam_id'";
    $sql = mysqli_query($db, $query);
    $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
    foreach($result as $row)
	{
    $submission_status = $row['submission'];
    }
}
else
{
	header('location:enroll_exam.php');
}

include('header.php');
?>

<br />
<?php
if($exam_status == 'Started' && $submission_status=='No')
{
    
	$query = "UPDATE user_exam_enroll_table 
	SET attendance_status = 'Present' 
	WHERE user_id = '".$user['user_id']."' 
	AND exam_id = '$exam_id'
	";

    mysqli_query($db, $query);

?>
<!-- <div class="container"> -->

<div class="p-3 d-flex mb-5 bg-white rounded">
<span id="user_details_area"></span>		
<div class="rounded shadow " id="timer">
Time Remaining <p id="clock"></p>
</div>
</div>
<!-- </div> -->
<div class="row container text-center">
	<div class="col-md-8 ">
				<div id="single_question_area"></div>
			
		<br />
		<div id="question_navigation_area"></div>
	<!-- </div> -->
	<div class="col-md-4">
		<br />
		<div align="center">
			<div id="exam_timer" data-timer="<?php echo $remaining_minutes; ?>" style="max-width:400px; width: 100%; height: 200px;"></div>
		</div>
		<br />
		<div id="user_details_area"></div>		
	</div>
</div>

<script>

$(document).ready(function(){
	var exam_id = "<?php echo $exam_id; ?>";

	load_question();
	question_navigation();

	function load_question(question_id = '')
	{
		$.ajax({
			url:"dbconfig.php",
			method:"POST",
			data:{exam_id:exam_id, question_id:question_id, page:'view_exam', action:'load_question'},
			success:function(data)
			{
				$('#single_question_area').html(data);
			}
		})
	}

	$(document).on('click', '.next', function(){
		var question_id = $(this).attr('id');
		load_question(question_id);
	});

	$(document).on('click', '.previous', function(){
		var question_id = $(this).attr('id');
		load_question(question_id);
	});

	function question_navigation()
	{
		$.ajax({
			url:"dbconfig.php",
			method:"POST",
			data:{exam_id:exam_id, page:'view_exam', action:'question_navigation'},
			success:function(data)
			{
				$('#question_navigation_area').html(data);
			}
		})
	}

	$(document).on('click', '.question_navigation', function(){
		var question_id = $(this).data('question_id');
		load_question(question_id);
	});

	function load_user_details()
	{
		$.ajax({
			url:"dbconfig.php",
			method:"POST",
			data:{page:'view_exam', action:'user_detail'},
			success:function(data)
			{
				$('#user_details_area').html(data);
			}
		})
	}

	load_user_details();

    var $hrs = "<?php echo $remaining_hrs; ?>"
    var $min = "<?php echo $remaining_minutes; ?>"
    var $secs = "<?php echo $remaining_secs; ?>"

	setInterval(function(){
        
            
        if($secs ==0){
            if($min>0){
                $min -=1;
                $secs += 60;
            }
            else{
                if($hrs>0){
                    $hrs -=1;
                    $min +=59;
                    $secs+=60;

                }
                else{
                    alert("Exam Time Over");
                    location.href = "enroll_exam.php"

                }
            }
        }else{
            
		$('#clock').text($hrs+':'+$min+':'+$secs)}
        $secs -=1

	}, 1000);

	$(document).on('click', '.answer_option', function(){
		var question_id = $(this).data('question_id');

		var answer_option = $(this).attr('id');

		$.ajax({
			url:"dbconfig.php",
			method:"POST",
			data:{question_id:question_id, answer_option:answer_option, exam_id:exam_id, page:'view_exam', action:'answer'},
			success:function(data)
			{

			}
		})
	});
    $(document).on('click', '#done', function(){
		$.ajax({
			url:"dbconfig.php",
			method:"POST",
			data:{exam_id:exam_id,page:'view_exam', action:'submit'},
			success:function(data)
			{
                location.href = "enroll_exam.php"
			}
		})
	});

});
</script>
<?php
}
if($exam_status == 'Completed' || $submission_status=='Yes' )
{
    $username = $_SESSION['username'];
        $user_check_query = "SELECT * FROM user_table WHERE `user_name`='$username'";
        $result = mysqli_query($db, $user_check_query);
      $user = mysqli_fetch_assoc($result);
	$query = "SELECT * FROM question_table 
	INNER JOIN user_exam_question_answer 
	ON user_exam_question_answer.question_id = question_table.question_id 
	WHERE question_table.online_exam_id = '$exam_id' 
	AND user_exam_question_answer.user_id = '".$user["user_id"]."'
	";
	$sql = mysqli_query($db, $query);
    $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
?>
	<div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col-md-8">Online Exam Result</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<tr>
						<th>Question</th>
						<th>Option 1</th>
						<th>Option 2</th>
						<th>Option 3</th>
						<th>Option 4</th>
						<th>Your Answer</th>
						<th>Correct Answer</th>
						<th>Result</th>
						<th>Marks</th>
					</tr>
					<?php
					$total_mark = 0;

					foreach($result as $row)
					{
						$query = "SELECT * FROM option_table 
						WHERE question_id = '".$row["question_id"]."'
                        ";
                        $sql = mysqli_query($db, $query);
                        $sub_result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
						$user_answer = '';
						$orignal_answer = '';
						$question_result = '';

						if($row['marks'] == '0')
						{
							$question_result = '<h4 class="badge badge-dark">Not Attend</h4>';
						}

						if($row['marks'] > '0')
						{
							$question_result = '<h4 class="badge badge-success">Right</h4>';
						}

						if($row['marks'] < '0')
						{
							$question_result = '<h4 class="badge badge-danger">Wrong</h4>';
						}

						echo '
						<tr>
							<td>'.$row['question_title'].'</td>
						';

						foreach($sub_result as $sub_row)
						{
							echo '<td>'.$sub_row["option_title"].'</td>';

							if($sub_row["option_number"] == $row['user_answer_option'])
							{
								$user_answer = $sub_row['option_title'];
							}

							if($sub_row['option_number'] == $row['answer_option'])
							{
								$orignal_answer = $sub_row['option_title'];
							}
						}
						echo '
						<td>'.$user_answer.'</td>
						<td>'.$orignal_answer.'</td>
						<td>'.$question_result.'</td>
						<td>'.$row["marks"].'</td>
					</tr>
						';
					}

					$query = "SELECT SUM(marks) as total_mark FROM user_exam_question_answer 
					WHERE user_id = '".$user['user_id']."' 
					AND exam_id = '".$exam_id."'
					";

                    $sql = mysqli_query($db, $query);
                    $marks_result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

					foreach($marks_result as $row)
					{
					?>
					<tr>
						<td colspan="8" align="right">Total Marks</td>
						<td align="right"><?php echo $row["total_mark"]; ?></td>
					</tr>
					<?php	
					}

					?>
				</table>
			</div>
		</div>
	</div>
<?php
}

?>

</div>
</body>
</html>

