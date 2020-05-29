<?php

//enroll_exam.php

include('dbconfig.php');
if(!isset($_SESSION['username'])){
    header("location: index.php");
}

$username = $_SESSION['username'];
    $user_check_query = "SELECT * FROM user_table WHERE user_name='$username'";
    $result = mysqli_query($db, $user_check_query);
      $user = mysqli_fetch_assoc($result);
      $user_id = $user['user_id'];
      $query = "SELECT * FROM user_exam_enroll_table 
      INNER JOIN online_exam_table 
      ON online_exam_table.online_exam_id = user_exam_enroll_table.exam_id 
      WHERE user_exam_enroll_table.user_id = '$user_id'
      ";
  
      $sql = mysqli_query($db, $query);
      $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

      $current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));
  
      foreach($result as $row)
      {
        $exam_start_time = $row["online_exam_datetime"];
  
        $duration = $row["online_exam_duration"] . ' minute';
  
        $exam_end_time = strtotime($exam_start_time . '+' . $duration);
  
        $exam_end_time = date('Y-m-d H:i:s', $exam_end_time);
  
		$view_exam = '';
        if($current_datetime >= $exam_start_time && $current_datetime <= $exam_end_time)
        {
          
          $query = "UPDATE online_exam_table 
          SET online_exam_status = 'Started' 
          WHERE online_exam_id = '".$row['online_exam_id']."'
          ";
  
          mysqli_query($db, $query);
          
        }
        else
        {
          if($current_datetime > $exam_end_time)
          {
            //exam completed
            // echo "hell";
            $query = "UPDATE online_exam_table 
            SET online_exam_status = 'Completed' 
            WHERE online_exam_id = '".$row['online_exam_id']."'
            ";
  
            mysqli_query($db, $query);
          }					
        }
      }

include('header.php');

?>

<br />
<div class="card">
	<div class="card-header">Online Exam List</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover" id="exam_data_table">
				<thead>
					<tr>
						<th>Exam Title</th>
						<th>Date & Time</th>
						<th>Duration</th>
						<th>Total Question</th>
						<th>Right Answer Mark</th>
						<th>Wrong Answer Mark</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
</div>
</body>
</html>

<script>

$(document).ready(function(){

	var dataTable = $('#exam_data_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"dbconfig.php",
			type:"POST",
			data:{action:'fetch', page:'enroll_exam'}
		},
		"columnDefs":[
			{
				"targets":[7],
				"orderable":false,
			},
		],
	});

});

</script>