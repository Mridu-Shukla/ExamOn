<?php

//pdf_exam_result.php

include("../dbconfig.php");

require_once('../class/pdf.php');


if(isset($_GET["code"]))
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
	$query = "SELECT user_table.user_id, user_table.user_name, sum(user_exam_question_answer.marks) as total_mark  
	FROM user_exam_question_answer  
	INNER JOIN user_table 
	ON user_table.user_id = user_exam_question_answer.user_id 
	WHERE user_exam_question_answer.exam_id = '$exam_id' 
	GROUP BY user_exam_question_answer.user_id 
	ORDER BY total_mark DESC
	";
	$sql = mysqli_query($db, $query);
	$result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

	$output = '
	<html><body>
	<h2 align="center">Exam Report</h2><br />
	<table width="100%" border="1" cellpadding="5" cellspacing="0">
		<tr>
			<th>Rank</th>
			<th>User Name</th>
			<th>Attendance Status</th>
			<th>Marks</th>
		</tr>
	';

	$count = 1;

	foreach($result as $row)
	{
		$query = "SELECT attendance_status FROM user_exam_enroll_table 
		WHERE exam_id = '$exam_id' 
		AND user_id = '".$row['user_id']."'
		";
		$sql = mysqli_query($db, $query);
		$result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
		foreach($result as $row1)
		{
			$user_status =  $row1["attendance_status"];
		}
		$output .= '
		<tr>
			<td>'.$count.'</td>
			<td>'.$row["user_name"].'</td>
			<td>'.$user_status.'</td>
			<td>'.$row["total_mark"].'</td>
		</tr>
		';

		$count = $count + 1;
	}

	$output .= '</table></body></html>';
	
	$pdf = new Pdf();

	$file_name = 'Exam Report.pdf';

	$pdf->loadHtml($output);

	$pdf->render();
	
	ob_end_clean();
	$pdf->stream("codexworld",array("Attachment"=>0));
	echo $output;
	// exit(0);
}

?>