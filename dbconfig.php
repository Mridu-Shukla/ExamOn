<?php
require_once 'class/class.phpmailer.php';

session_start();
date_default_timezone_set('Asia/Calcutta');
// initializing variables
$user_name = "";
$email    = "";
$errors = array(); 
$current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'exam_on');


// REGISTER USER
if (isset($_POST['user_register'])) {
  
  
  $user_name = mysqli_real_escape_string($db, $_POST['user_name']);
  $email = mysqli_real_escape_string($db, $_POST['user_email_address']);
  $password_1 = mysqli_real_escape_string($db, $_POST['user_password']);
  $gender = mysqli_real_escape_string($db, $_POST['user_gender']);
  $address = mysqli_real_escape_string($db, $_POST['user_address']);
  $mob = mysqli_real_escape_string($db, $_POST['user_mobile_no']);
  // $img = mysqli_real_escape_string($db, $_FILES['user_image']['name']);
  $name =$_FILES["image"]['name'];
  if(!empty($name)){
  $extension = pathinfo($name, PATHINFO_EXTENSION);
  $new_name = uniqid() . '.' . $extension;
	$_source_path = $_FILES['image']['tmp_name'];
  $target_path = 'uploads/' . $new_name;

  $img = $new_name;
  move_uploaded_file($_source_path, $target_path);
  }
  $user_check_query = "SELECT * FROM user_table WHERE user_name='$user_name' OR user_email_address='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['user_name'] === $user_name) {
      array_push($errors, "Username already exists");
    }

    if ($user['user_email_address'] === $email) {
      array_push($errors, "Email already exists");
    }
  }

  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

    $query = "INSERT INTO user_table (user_name, user_email_address, user_password, user_gender, user_address, user_mobile_no,user_image,user_created_on) 
  			  VALUES('$user_name', '$email', '$password','$gender','$address', '$mob', '$img', '$current_datetime')";
  	mysqli_query($db, $query);
  	
      // header('location: index.php');
}
}

// LOGIN USER
if (isset($_POST['user_login'])) {
    // $errors = array(); 
    $username = mysqli_real_escape_string($db, $_POST["username"]);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    // array_push($errors, "Wrong username/password combination");
    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM user_table WHERE `user_name`='$username' AND `user_password`='$password'";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) == 1) {
          $_SESSION['username'] = $username;
          $_SESSION['success'] = "You are now logged in";
          header('location: index.php');
        }else {
            array_push($errors, "Invalid username and Password");
        }
    }
  }


// Profile Change 

if(isset($_POST['page'])&&$_POST['page'] == "profile")
	{
		if($_POST['action'] == "profile")
		{
      $user_image = $_POST['hidden_user_image'];
      $img='';
			if($_FILES['user_image']['name'] != '')
			{
        $filedata = $_FILES['user_image']['name'];
          $extension = pathinfo($name, PATHINFO_EXTENSION);
          $new_name = uniqid() . '.' . $extension;
          $_source_path = $_FILES['image']['tmp_name'];
          $target_path = 'uploads/' . $new_name;
        
          $img = $new_name;
          move_uploaded_file($_source_path, $target_path);
          
      }
      $user_check_query = "SELECT * FROM user_table WHERE user_name='".$_SESSION['username']."'";
      $result = mysqli_query($db, $user_check_query);
      $user = mysqli_fetch_assoc($result);
			$query = "UPDATE user_table 
			SET user_address = '".$_POST['user_address']."', user_mobile_no = '".$_POST['user_mobile_no']."', user_image = '$img' 
			WHERE user_id = '".$user['user_id']."'
			";
			 mysqli_query($db, $query);

			$output = array(
				'success'		=>	true
			);

			echo json_encode($output);

		}
	}
// Login Admin

if (isset($_POST['admin_login'])) {
  // $errors = array(); 
  $username = mysqli_real_escape_string($db, $_POST["username"]);
  $password = mysqli_real_escape_string($db, $_POST['password']);
  if (count($errors) == 0) {
      $password = $password;
      $query = "SELECT * FROM admin_table WHERE `admin_username`='$username' AND `admin_password`='$password'";
      $results = mysqli_query($db, $query);
      if (mysqli_num_rows($results) == 1) {
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
      }else {
          array_push($errors, "Invalid username and Password");
      }
  }
}

if(isset($_POST['action']) && $_POST['action'] == 'enroll_exam')
		{
      $username = $_SESSION['username'];
        $user_check_query = "SELECT * FROM user_table WHERE user_name='$username'";
        $result = mysqli_query($db, $user_check_query);
      $user = mysqli_fetch_assoc($result);
			$data = array(
				':user_id'		=>	$user['user_id'],
				':exam_id'		=>	$_POST['exam_id']
      );
      $exam_id = $_POST['exam_id'];
      $uid =$user['user_id'];
			$query = "INSERT INTO user_exam_enroll_table 
			(user_id, exam_id) 
			VALUES ('$uid', '$exam_id')
			";

      mysqli_query($db, $query);

			$query = "SELECT question_id FROM question_table 
			WHERE online_exam_id = '".$_POST['exam_id']."'
			";
			$sql = mysqli_query($db, $query);
      $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);;
			foreach($result as $row)
			{

				$query = "INSERT INTO user_exam_question_answer 
				(user_id, exam_id, question_id, user_answer_option, marks) 
				VALUES ('$uid', '$exam_id', '".$row['question_id']."', '0', '0')
				";
				mysqli_query($db, $query);
			}
		}

	
    if(isset($_POST['page']) && $_POST["page"] == 'enroll_exam')
    {
      if($_POST['action'] == 'fetch')
      {
        $output = array();
        $username = $_SESSION['username'];
        $user_check_query = "SELECT * FROM user_table WHERE `user_name`='$username'";
        $result = mysqli_query($db, $user_check_query);
      // $sql = mysqli_query($db, )
      $user = mysqli_fetch_assoc($result);
        $query = "SELECT * FROM user_exam_enroll_table 
        INNER JOIN online_exam_table 
        ON online_exam_table.online_exam_id = user_exam_enroll_table.exam_id 
        WHERE user_exam_enroll_table.user_id = '".$user['user_id']."' 
        AND (";
  
        if(isset($_POST["search"]["value"]))
        {
           $query .= 'online_exam_table.online_exam_title LIKE "%'.$_POST["search"]["value"].'%" ';
           $query .= 'OR online_exam_table.online_exam_datetime LIKE "%'.$_POST["search"]["value"].'%" ';
           $query .= 'OR online_exam_table.online_exam_duration LIKE "%'.$_POST["search"]["value"].'%" ';
           $query .= 'OR online_exam_table.total_question LIKE "%'.$_POST["search"]["value"].'%" ';
           $query .= 'OR online_exam_table.marks_per_right_answer LIKE "%'.$_POST["search"]["value"].'%" ';
           $query .= 'OR online_exam_table.marks_per_wrong_answer LIKE "%'.$_POST["search"]["value"].'%" ';
           $query .= 'OR online_exam_table.online_exam_status LIKE "%'.$_POST["search"]["value"].'%" ';
        }
  
        $query .= ')';
  
        if(isset($_POST["order"]))
        {
          $query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
          $query .= 'ORDER BY online_exam_table.online_exam_id DESC ';
        }
  
        $extra_query = '';
  
        if($_POST["length"] != -1)
        {
           $extra_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
  
        $sql = mysqli_query($db, $query);

        $filtered_rows = mysqli_num_rows($sql);

        // $filterd_rows = $exam->total_row();
  
        $query .= $extra_query;
        $sql = mysqli_query($db, $query);
        $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
  
        $query = "SELECT * FROM user_exam_enroll_table 
        INNER JOIN online_exam_table 
        ON online_exam_table.online_exam_id = user_exam_enroll_table.exam_id 
        WHERE user_exam_enroll_table.user_id = '".$user['user_id']."'";
  
        $sql = mysqli_query($db, $query);
        $total_rows = mysqli_num_rows($sql);
  
        $data = array();
  
        foreach($result as $row)
        {
          $sub_array = array();
          $sub_array[] = html_entity_decode($row["online_exam_title"]);
          $sub_array[] = $row["online_exam_datetime"];
          $sub_array[] = $row["online_exam_duration"] . ' Minute';
          $sub_array[] = $row["total_question"] . ' Question';
          $sub_array[] = $row["marks_per_right_answer"] . ' Mark';
          $sub_array[] = '-' . $row["marks_per_wrong_answer"] . ' Mark';
          $status = '';
          // echo $row['online_exam_status'];
          if($row['online_exam_status'] == 'Created')
          {
            $status = '<span class="badge badge-success">Created</span>';
          }
  
          if($row['online_exam_status'] == 'Started')
          {
            $status = '<span class="badge badge-primary">Started</span>';
          }
  
          if($row['online_exam_status'] == 'Completed')
          {
            $status = '<span class="badge badge-dark">Completed</span>';
          }
  
          $sub_array[] = $status;				
          $view_exam = '';
          if($row["online_exam_status"] == 'Started')
          {
            $view_exam = '<a href="view_exam.php?code='.$row["online_exam_code"].'" class="btn btn-info btn-sm">View Exam</a>';
          }
          if($row["online_exam_status"] == 'Completed')
          {
            $view_exam = '<a href="view_exam.php?code='.$row["online_exam_code"].'" class="btn btn-info btn-sm">View Result</a>';
          }
  
          
          $sub_array[] = $view_exam;
  
          $data[] = $sub_array;
        }
  
        $output = array(
           "draw"    			=> 	intval($_POST["draw"]),
           "recordsTotal"  	=>  $total_rows,
           "recordsFiltered" 	=> 	$filtered_rows,
           "data"    			=> 	$data
        );
        echo json_encode($output);
      }
    }

if(isset($_POST['page'])){
  if($_POST['page'] == 'exam')
{
   if($_POST['action'] == 'fetch')
   {
      $username = $_SESSION['username'];
$user_check_query = "SELECT * FROM admin_table WHERE admin_username='$username'";
$result = mysqli_query($db, $user_check_query);
$user = mysqli_fetch_assoc($result);
$admin_id = $user['admin_id'];

  $query = "SELECT * FROM online_exam_table 
  WHERE admin_id = '$admin_id' 
  ";

  if(isset($_POST['search']['value']))
  {
	  $query .= 'OR online_exam_title LIKE "%'.$_POST["search"]["value"].'%" ';

	  $query .= 'OR online_exam_datetime LIKE "%'.$_POST["search"]["value"].'%" ';

	  $query .= 'OR online_exam_duration LIKE "%'.$_POST["search"]["value"].'%" ';

	  $query .= 'OR total_question LIKE "%'.$_POST["search"]["value"].'%" ';

	  $query .= 'OR marks_per_right_answer LIKE "%'.$_POST["search"]["value"].'%" ';

	  $query .= 'OR marks_per_wrong_answer LIKE "%'.$_POST["search"]["value"].'%" ';

	  $query .= 'OR online_exam_status LIKE "%'.$_POST["search"]["value"].'%" ';
  }

  // $query .= ' )';
  if(isset($_POST['order']))
  {
  	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
  }
  else
  {
  	$query .= 'ORDER BY online_exam_id DESC';
}

$extra_query = '';
$length = (isset($_POST["length"]))? $_POST["length"] : -1;
$start = (isset($_POST["start"]))? $_POST["start"] : 0;
  if($length > -1)
  {
$extra_query .= 'LIMIT ' . $start . ', ' . $length;
  }
  $sql = mysqli_query($db, $query);

  $filtered_rows = mysqli_num_rows($sql);

  $query .= $extra_query;
  $sql = mysqli_query($db, $query);
  $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

  $query = "SELECT * FROM online_exam_table 
  WHERE admin_id = '$admin_id'
";
$sql = mysqli_query($db, $query);
  $total_rows = mysqli_num_rows($sql);
  $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

  $data = array();

  foreach($result as $row)
  {
	  $sub_array = array();
	  $sub_array[] = html_entity_decode($row['online_exam_title']);

	  $sub_array[] = $row['online_exam_datetime'];

	  $sub_array[] = $row['online_exam_duration'] . ' Minute';

	  $sub_array[] = $row['total_question'] . ' Question';

	  $sub_array[] = $row['marks_per_right_answer'] . ' Mark';


    $sub_array[] = '-' . $row['marks_per_wrong_answer'] . ' Mark';
    
	  $status = '';
	  $edit_button = '';
	  $delete_button = '';
	  $question_button = '';
    $result_button = '';
    $exam_status = $row['online_exam_status'];
	  if($row['online_exam_status'] == 'Pending')
	  {
		  $status = '<span class="badge badge-warning">Pending</span>';
	  }

	  if($row['online_exam_status'] == 'Created')
	  {
		  $status = '<span class="badge badge-success">Created</span>';
	  }

	  if($row['online_exam_status'] == 'Started')
	  {
		  $status = '<span class="badge badge-primary">Started</span>';
	  }

	  if($row['online_exam_status'] == 'Completed')
	  {
		  $status = '<span class="badge badge-dark">Completed</span>';
	  }

	  $current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));

	  $exam_datetime = '';
	  $online_exam_id = $row["online_exam_id"];
	  $exam_code = $row['online_exam_code'];
	  $exam_id = $online_exam_id;
	  $query = "SELECT total_question FROM online_exam_table 
	  WHERE online_exam_id = '$exam_id'
	  ";

	  $sql = mysqli_query($db, $query);
	  $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);;

	  foreach($result as $row)
	  {
		  $exam_question_limit = $row['total_question'];
	  }
	  
	  $query = "SELECT question_id FROM question_table 
	  WHERE online_exam_id = '$exam_id'
";
$sql = mysqli_query($db, $query);
	  $exam_total_question = mysqli_num_rows($sql);;

	  if($exam_total_question >= $exam_question_limit)
	  {
      if($exam_status == 'Pending'){
      $query = "UPDATE online_exam_table 
      SET online_exam_status = 'Created'
      WHERE online_exam_id = '$exam_id'";
      mysqli_query($db, $query);
      }
		  $question_button = '
		  <a href="question.php?code='.$exam_code.'" class="btn btn-warning btn-sm">View Question</a>
      ';
	  }
	  else{
		  $question_button = '
		  <button type="button" name="add_question" class="btn btn-info btn-sm add_question" id="'.$exam_id.'">Add Question</button>
		  ';
    }
    $query = "SELECT online_exam_datetime FROM online_exam_table 
	  WHERE `online_exam_id` = '$online_exam_id'
	  ";
	  

	  $sql = mysqli_query($db, $query);
	  $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);;

	  foreach($result as $row)
	  {
		  $exam_datetime = $row['online_exam_datetime'];
	  }

	  if($exam_datetime > $current_datetime)
	  {
		  $edit_button = '
		  <button type="button" name="edit" class="btn btn-primary btn-sm edit" id="'.$online_exam_id.'">Edit</button>
		  ';

		  $delete_button = '<button type="button" name="delete" class="btn btn-danger btn-sm delete" id="'.$online_exam_id.'">Delete</button>';
	  }
	  else
	  {
		  $result_button = '<a href="exam_result.php?code='.$exam_code.' "class="btn btn-dark btn-sm">Result</a>';
	  }

    $sub_array[] = $status;
    $sub_array[] = '<a href="exam_enroll.php?code='.$exam_code.'" class="btn btn-secondary btn-sm">Enroll</a>';
	  $sub_array[] = $question_button;

	  $sub_array[] = $result_button;

	  $sub_array[] = $edit_button . ' ' . $delete_button;

	  $data[] = $sub_array;
}
        $output = array();
  
        $output = array(
          "draw"				=>	1,
          "recordsTotal"		=>	$total_rows,
          "recordsFiltered"	=>	$filtered_rows,
          "data"				=>	$data
        );
  
        echo json_encode($output);
  
      }



      if($_POST['action'] == 'edit_fetch')
		{
			$query = "SELECT * FROM online_exam_table 
			WHERE online_exam_id = '".$_POST["exam_id"]."'
			";

      $sql = mysqli_query($db, $query);
      $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);;

			foreach($result as $row)
			{
				$output['online_exam_title'] = $row['online_exam_title'];

				$output['online_exam_datetime'] = $row['online_exam_datetime'];

				$output['online_exam_duration'] = $row['online_exam_duration'];

				$output['total_question'] = $row['total_question'];

				$output['marks_per_right_answer'] = $row['marks_per_right_answer'];

				$output['marks_per_wrong_answer'] = $row['marks_per_wrong_answer'];
			}

			echo json_encode($output);
		}

		if($_POST['action'] == 'Edit')
		{
			$query = "UPDATE online_exam_table 
			SET online_exam_title = '".$_POST['online_exam_title']."', online_exam_datetime = '".$_POST['online_exam_datetime'] . ':00'."', online_exam_duration = '".$_POST['online_exam_duration']."', total_question = '".$_POST['total_question']."', marks_per_right_answer = '".$_POST['marks_per_right_answer']."', marks_per_wrong_answer = '".$_POST['marks_per_wrong_answer']."'  
			WHERE online_exam_id = '".$_POST['online_exam_id']."'
			";

      mysqli_query($db, $query);


			$output = array(
				'success'	=>	'Exam Details has been changed'
			);

			echo json_encode($output);
		}
		if($_POST['action'] == 'delete')
		{

			$query = "DELETE FROM online_exam_table 
			WHERE online_exam_id = '".$_POST['exam_id']."'
			";

      mysqli_query($db, $query);


			$output = array(
				'success'	=>	'Exam Details has been removed'
			);

			echo json_encode($output);
		}
    }



    // User Details
    if($_POST['page'] == 'user')
    {
      if($_POST['action'] == 'fetch')
      {
        $output = array();
  
        $query = "SELECT * FROM user_table ";

			if(isset($_POST["search"]["value"]))
			{

			 	$query .= 'WHERE user_email_address LIKE "%'.$_POST["search"]["value"].'%" ';
			 	$query .= 'OR user_name LIKE "%'.$_POST["search"]["value"].'%" ';
			 	$query .= 'OR user_gender LIKE "%'.$_POST["search"]["value"].'%" ';
			 	$query .= 'OR user_mobile_no LIKE "%'.$_POST["search"]["value"].'%" ';
			}
			
			if(isset($_POST["order"]))
			{
				$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
			}
			else
			{
				$query .= 'ORDER BY user_id DESC ';
			}

			$extra_query = '';

			$extra_query = '';
			$length = (isset($_POST["length"]))? $_POST["length"] : -1;
			$start = (isset($_POST["start"]))? $_POST["start"] : 0;
			if($length > -1)
			{
			$extra_query .= 'LIMIT ' . $start . ', ' . $length;
			}
			$sql = mysqli_query($db, $query);
			$filtered_rows = mysqli_num_rows($sql);
			
			  $query .= $extra_query;
			$sql = mysqli_query($db, $query);
			  $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
			
			$query = "
			SELECT * FROM user_table";
			$sql = mysqli_query($db, $query);
			$total_rows = mysqli_num_rows($sql);
			$result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

			$data = array();

			foreach($result as $row)
			{
				$sub_array = array();
				$sub_array[] = '<img src="../uploads/'.$row["user_image"].'" class="img-thumbnail" width="75" />';
				$sub_array[] = $row["user_name"];
				$sub_array[] = $row["user_email_address"];
				$sub_array[] = $row["user_gender"];
				$sub_array[] = $row["user_mobile_no"];
				$sub_array[] = '<button type="button" id="'.$row['user_id'].'" class="btn btn-info btn-sm details">View Details</button>';
				$data[] = $sub_array;
        }
  
        $output = array(
           "draw"    			=> 	1,
           "recordsTotal"  	=>  $total_rows,
           "recordsFiltered" 	=> 	$filtered_rows,
           "data"    			=> 	$data
        );
        echo json_encode($output);	
      }
  

      if($_POST['action'] == 'fetch_data')
      {
        $query = "SELECT * FROM user_table 
        WHERE user_id = '".$_POST["user_id"]."'
        ";
        $sql = mysqli_query($db, $query);
			$result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
        $output = '';
        foreach($result as $row)
        {
          
  
          $output .= '
          <div class="row">
            <div class="col-md-12">
            <div style="text-align:center;">
            <img src="../uploads/'.$row["user_image"].'" class="img-thumbnail" width="200" />
            </div>
              <br />
              <table class="table table-bordered">
                <tr>
                  <th>Name</th>
                  <td>'.$row["user_name"].'</td>
                </tr>
                
                <tr>
                  <th>Email</th>
                  <td>'.$row["user_email_address"].'</td>
                </tr>
                <tr>
                  <th>Gender</th>
                  <td>'.$row["user_gender"].'</td>
                </tr>
                <tr>
                  <th>Mobile No</th>
                  <td>'.$row["user_mobile_no"].'</td>
                </tr>
                <tr>
                  <th>Address</th>
                  <td>'.$row["user_address"].'</td>
                </tr>
                
              </table>
            </div>
          </div>
          ';
        }	
        echo $output;			
      }
    
    }


    // Enrollment List 
    // -------------------------------------------------------------------
    if($_POST['page'] == 'exam_enroll')
	{
		if($_POST['action'] == 'fetch')
		{
      $output = array();
      $query = "SELECT online_exam_id FROM online_exam_table 
		WHERE online_exam_code = '".$_POST['code']."'
		";

    $sql = mysqli_query($db, $query);
    $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

		foreach($result as $row)
		{
			$exam_id = $row['online_exam_id'];
		}

			//  $exam->Get_exam_id();

			$query = "SELECT * FROM user_exam_enroll_table 
			INNER JOIN user_table 
			ON user_table.user_id = user_exam_enroll_table.user_id  
			WHERE user_exam_enroll_table.exam_id = '".$exam_id."'  
			";

			if(isset($_POST['search']['value']))
			{
				$query .= 'OR user_table.user_name LIKE "%'.$_POST["search"]["value"].'%" 
				';
				$query .= 'OR user_table.user_gender LIKE "%'.$_POST["search"]["value"].'%" ';
				$query .= 'OR user_table.user_mobile_no LIKE "%'.$_POST["search"]["value"].'%" ';
			}

			if(isset($_POST['order']))
			{
				$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
			}
			else
			{
				$query .= 'ORDER BY user_exam_enroll_table.user_exam_enroll_id ASC ';
			}

			$extra_query = '';

			if($_POST['length'] != -1)
			{
				$extra_query = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
			}

      $sql = mysqli_query($db, $query);
      // if(!$sql){
      //   echo mysqli_error($db);
      // }
      $filtered_rows = mysqli_num_rows($sql);


      $query .= $extra_query;
      $sql = mysqli_query($db, $query);
      $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

			$query = "SELECT * FROM user_exam_enroll_table 
			INNER JOIN user_table 
			ON user_table.user_id = user_exam_enroll_table.user_id  
			WHERE user_exam_enroll_table.exam_id = '".$exam_id."'
			";
      $sql = mysqli_query($db, $query);

      $total_rows = mysqli_num_rows($sql);

			$data = array();

			foreach($result as $row)
			{
				$sub_array = array();
				$sub_array[] = "<img src='../upload/".$row["user_image"]."' class='img-thumbnail' width='75' />";
				$sub_array[] = $row["user_name"];
				$sub_array[] = $row["user_gender"];
				$sub_array[] = $row["user_mobile_no"];
				$sub_array[] = $row["submission"];
        $result_action = '';
        $query = "SELECT online_exam_status FROM online_exam_table 
          WHERE online_exam_id = '".$exam_id."' 
          "; 
          $sql = mysqli_query($db, $query);
          $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
          $uid = $row['user_id'];
          foreach($result as $row)
          {
            if($row["online_exam_status"]=='Completed'){
              $result_action = '<a href="user_exam_result.php?code='.$_POST['code'].'&id='.$uid.'" class="btn btn-info btn-sm" target="_blank">Result</a>';
            };
          }
				$sub_array[] = $result_action;

				$data[] = $sub_array;
			}

			$output = array(
				"draw"				=>	intval($_POST["draw"]),
				"recordsTotal"		=>	$total_rows,
				"recordsFiltered"	=>	$filtered_rows,
				"data"				=>	$data
			);

			echo json_encode($output);
		}
	}


    // Exam Result

    if($_POST['page'] == 'exam_result')
	{
		if($_POST['action'] == 'fetch')
		{
      $output = array();
      $query = "SELECT online_exam_id FROM online_exam_table 
      WHERE online_exam_code = '".$_POST["code"]."'
      ";
  
      $sql = mysqli_query($db, $query);
			$result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
  
      foreach($result as $row)
      {
        $exam_id =  $row['online_exam_id'];
      }
			$query = "SELECT user_table.user_id, user_table.user_name, user_table.user_gender, user_table.user_mobile_no,user_table.user_image, sum(user_exam_question_answer.marks) as total_mark  
			FROM user_exam_question_answer  
			INNER JOIN user_table 
			ON user_table.user_id = user_exam_question_answer.user_id 
			WHERE user_exam_question_answer.exam_id = '$exam_id' 
			AND (
			";

			if(isset($_POST["search"]["value"]))
			{
				$query .= 'user_table.user_name LIKE "%'.$_POST["search"]["value"].'%" ';
			}

			$query .= '
			) 
			GROUP BY user_exam_question_answer.user_id 
			';

			if(isset($_POST["order"]))
			{
				$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
			}
			else
			{
				$query .= 'ORDER BY total_mark DESC ';
			}

			$extra_query = '';

			if($_POST["length"] != -1)
			{
				$extra_query = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
			}

      $sql = mysqli_query($db, $query);
      if(!$sql)
      {
        echo mysqli_error($db);
      }
      
			$filtered_rows = mysqli_num_rows($sql);
			
			  $query .= $extra_query;
			$sql = mysqli_query($db, $query);
			  $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

			$query = "SELECT user_table.user_name,user_table.user_gender, user_table.user_mobile_no,user_table.user_image, sum(user_exam_question_answer.marks) as total_mark  
			FROM user_exam_question_answer  
			INNER JOIN user_table 
			ON user_table.user_id = user_exam_question_answer.user_id 
			WHERE user_exam_question_answer.exam_id = '$exam_id' 
			GROUP BY user_exam_question_answer.user_id 
			ORDER BY total_mark DESC
			";

      $sql = mysqli_query($db, $query);
      
			$total_rows = mysqli_num_rows($sql);
			$data = array();
			foreach($result as $row)
			{
				$sub_array = array();
				$sub_array[] = '<img src="../uploads/'.$row["user_image"].'" class="img-thumbnail" width="75" />';
        $sub_array[] = $row["user_name"];
        $sub_array[] = $row["user_gender"];
        $sub_array[] = $row["user_mobile_no"];
        $query = "SELECT attendance_status 
        FROM user_exam_enroll_table 
        WHERE exam_id = '$exam_id' 
        AND user_id = '".$row["user_id"]."'
        ";
        $sql = mysqli_query($db, $query);
			  $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
        // $user_att = ''
        foreach($result as $row1)
        {
          $user_att =  $row1["attendance_status"];
        }
				$sub_array[] = $user_att;
				$sub_array[] = $row["total_mark"];
				$data[] = $sub_array;
			}

			$output = array(
				"draw"				=>	intval($_POST["draw"]),
				"recordsTotal"		=>	$total_rows,
				"recordsFiltered"	=>	$filtered_rows,
				"data"				=>	$data
			);

			echo json_encode($output);
		}
  }
  

  // Question   Add, Edit

  if($_POST['page'] == 'question')
	{
		if($_POST['action'] == 'Add')
		{
      $data = trim($_POST['question_title']);
	  	$data = stripslashes($_POST['question_title']);
	  	$data = htmlspecialchars($_POST['question_title']);
			
      
      $online_exam_id		=	$_POST['online_exam_id'];
      $answer_option		=	$_POST['answer_option'];
			$query = "INSERT INTO question_table 
			(online_exam_id, question_title, answer_option) 
			VALUES ('$online_exam_id', '$data', '$answer_option')
      ";
      
      mysqli_query($db,$query);

			$question_id = mysqli_insert_id($db);

      
			for($count = 1; $count <= 4; $count++)
			{
        $data = trim($_POST['option_title_' . $count]);
	  	$data = stripslashes($_POST['option_title_' . $count]);
	  	$data = htmlspecialchars($_POST['option_title_' . $count]);
			

				$query = "INSERT INTO option_table 
				(question_id, option_number, option_title) 
				VALUES ('$question_id', '$count', '$data')
				";

				mysqli_query($db,$query);
			}

			$output = array(
				'success'		=>	'Question Added'
			);

			echo json_encode($output);
		}

		if($_POST['action'] == 'fetch')
		{
			$output = array();
      $exam_id = '';
      
			if(isset($_POST['code']))
			{
        $query = "SELECT online_exam_id FROM online_exam_table 
		WHERE online_exam_code = '".$_POST['code']."'
		";

        $sql = mysqli_query($db, $query);
        $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

		foreach($result as $row)
		{
			$exam_id  = $row['online_exam_id'];
		}
			}
			$query = "SELECT * FROM question_table 
			WHERE online_exam_id = '".$exam_id."' 
			AND (
			";

			if(isset($_POST['search']['value']))
			{
				$query .= 'question_title LIKE "%'.$_POST["search"]["value"].'%" ';
			}

			$query .= ')';

			if(isset($_POST["order"]))
			{
				$query .= '
				ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' 
				';
			}
			else
			{
				$query .= 'ORDER BY question_id ASC ';
			}

			$extra_query = '';

			if($_POST['length'] != -1)
			{
				$extra_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
      }
      
			$sql = mysqli_query($db, $query);
      
			$filtered_rows = mysqli_num_rows($sql);
			
			  $query .= $extra_query;
			$sql = mysqli_query($db, $query);
			  $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

			$query = "SELECT * FROM question_table 
			WHERE online_exam_id = '".$exam_id."'
			";

      $sql = mysqli_query($db, $query);
            
      $total_rows = mysqli_num_rows($sql);

			$data = array();

			foreach($result as $row)
			{
				$sub_array = array();

				$sub_array[] = $row['question_title'];

				$sub_array[] = 'Option ' . $row['answer_option'];

				$edit_button = '';
				$delete_button = '';

        $exam_datetime = '';

        $query = "SELECT online_exam_datetime FROM online_exam_table 
        WHERE online_exam_id = '$exam_id'
        ";
        $qid = $row['question_id'];

        $sql = mysqli_query($db, $query);
        $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

        foreach($result as $row)
        {
          $exam_datetime = $row['online_exam_datetime'];
        }

        if($exam_datetime > $current_datetime)
        {
          $edit_button = '<button type="button" name="edit" class="btn btn-primary btn-sm edit" id='.$qid.'>Edit</button>';

					$delete_button = '<button type="button" name="delete" class="btn btn-danger btn-sm delete" id='.$qid.'>Delete</button>';
        }

				$sub_array[] = $edit_button . ' ' . $delete_button;

				$data[] = $sub_array;
			}

			$output = array(
				"draw"		=>	intval($_POST["draw"]),
				"recordsTotal"	=>	$total_rows,
				"recordsFiltered"	=>	$filtered_rows,
				"data"		=>	$data
			);

			echo json_encode($output);
		}

		if($_POST['action'] == 'edit_fetch')
		{
			$query = "SELECT * FROM question_table 
			WHERE question_id = '".$_POST["question_id"]."'
			";

      $sql = mysqli_query($db, $query);
      $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

			foreach($result as $row)
			{
				$output['question_title'] = html_entity_decode($row['question_title']);

				$output['answer_option'] = $row['answer_option'];

				for($count = 1; $count <= 4; $count++)
				{
					$query = "SELECT option_title FROM option_table 
					WHERE question_id = '".$_POST["question_id"]."' 
					AND option_number = '".$count."'
					";

          $sql = mysqli_query($db, $query);
          $sub_result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

					foreach($sub_result as $sub_row)
					{
						$output["option_title_" . $count] = html_entity_decode($sub_row["option_title"]);
					}
				}
			}

			echo json_encode($output);
		}

		if($_POST['action'] == 'Edit')
		{
			

			$query = "UPDATE question_table 
			SET question_title = '".$_POST['question_title']."', answer_option = '".$_POST['answer_option']."' 
			WHERE question_id = '".$_POST['question_id']."'
      ";
      mysqli_query($db, $query);
      $sql = mysqli_query($db, $query);
			

			for($count = 1; $count <= 4; $count++)
			{
			

				$query = "UPDATE option_table 
				SET option_title = '".$_POST['option_title_' . $count]."'
				WHERE question_id = '".$_POST['question_id']."' 
				AND option_number = '$count'
				";
				 mysqli_query($db, $query);
			}

			$output = array(
				'success'	=>	'Question Edit'
			);

			echo json_encode($output);
    }
    if($_POST['action'] == 'delete')
		{

			$query = "DELETE FROM question_table 
			WHERE question_id = '".$_POST['question_id']."'
			";

      mysqli_query($db, $query);


			$output = array(
				'success'	=>	'Questison Details has been removed'
			);

			echo json_encode($output);
		}
  }


  //View Exam 
  // -------------------------------------------------------------

  if($_POST['page'] == 'view_exam')
	{
		if($_POST['action'] == 'load_question')
		{
			if($_POST['question_id'] == '')
			{
				$query = "SELECT * FROM question_table 
				WHERE online_exam_id = '".$_POST["exam_id"]."' 
				ORDER BY question_id ASC 
				LIMIT 1
				";
			}
			else
			{
				$query = "SELECT * FROM question_table 
				WHERE question_id = '".$_POST["question_id"]."' 
				";
			}

			$sql = mysqli_query($db, $query);
	    $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

			$output = '';

			foreach($result as $row)
			{
				$output .= '
				<h1>'.$row["question_title"].'</h1>
				<hr />
				<br />
				<div class="row">
				';

				$query = "SELECT * FROM option_table 
				WHERE question_id = '".$row['question_id']."'
        ";
        $sql = mysqli_query($db, $query);
        $sub_result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

				$count = 1;

				foreach($sub_result as $sub_row)
				{
					$output .= '
					<div class="col-md-6" style="margin-bottom:32px;">
						<div class="radio">
							<label><h4><input type="radio" name="option_1" class="answer_option" data-question_id="'.$row["question_id"].'" id="'.$count.'"/>&nbsp;'.$sub_row["option_title"].'</h4></label>
						</div>
					</div>
					';

					$count = $count + 1;
				}
				$output .= '
				</div>
				';
				$query = "SELECT question_id FROM question_table 
				WHERE question_id < '".$row['question_id']."' 
				AND online_exam_id = '".$_POST["exam_id"]."' 
				ORDER BY question_id DESC 
        LIMIT 1";
        
        $sql = mysqli_query($db, $query);
	      $previous_result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
				

				$previous_id = '';
				$next_id = '';

				foreach($previous_result as $previous_row)
				{
					$previous_id = $previous_row['question_id'];
				}

				$query = "SELECT question_id FROM question_table 
				WHERE question_id > '".$row['question_id']."' 
				AND online_exam_id = '".$_POST["exam_id"]."' 
				ORDER BY question_id ASC 
				LIMIT 1";
  				$sql = mysqli_query($db, $query);
          $next_result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
  				

  				foreach($next_result as $next_row)
				{
					$next_id = $next_row['question_id'];
				}

				$if_previous_disable = '';
				$if_next_disable = '';

				if($previous_id == "")
				{
					$if_previous_disable = 'disabled';
				}
				
				if($next_id == "")
				{
					$if_next_disable = 'disabled';
        }
        if($if_next_disable == 'disabled'){
          $output .= '
					<br /><br />
				  	<div align="center">
				   		<button type="button" name="previous" class="btn btn-info btn-lg previous" id="'.$previous_id.'" '.$if_previous_disable.'>Previous</button>
				   		<button type="button" name="done" class="btn btn-success btn-lg" id="done">Submit</button>
				  	</div>
				  	<br /><br />';
        }
        else{
          $output .= '
					<br /><br />
				  	<div align="center">
				   		<button type="button" name="previous" class="btn btn-info btn-lg previous" id="'.$previous_id.'" '.$if_previous_disable.'>Previous</button>
				   		<button type="button" name="next" class="btn btn-warning btn-lg next" id="'.$next_id.'" '.$if_next_disable.'>Next</button>
				  	</div>
				  	<br /><br />';
        }
				
			}

			echo $output;
		}
		if($_POST['action'] == 'question_navigation')
		{
			$query = "SELECT question_id FROM question_table 
				WHERE online_exam_id = '".$_POST["exam_id"]."' 
				ORDER BY question_id ASC 
        ";
        $sql = mysqli_query($db, $query);
        $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
			
			$output = '
			<div class="card">
				<div class="card-header">Question Navigation</div>
				<div class="card-body">
					<div class="row">
			';
			$count = 1;
			foreach($result as $row)	
			{
				$output .= '
				<div class="col-md-2" style="margin-bottom:24px;">
					<button type="button" class="btn btn-primary btn-lg question_navigation" data-question_id="'.$row["question_id"].'">'.$count.'</button>
				</div>
				';
				$count++;
			}
			$output .= '
				</div>
			</div></div>
			';
			echo $output;
		}

		if($_POST['action'] == 'user_detail')
		{
      $username = $_SESSION['username'];
      $user_check_query = "SELECT * FROM user_table WHERE `user_name`='$username'";
      $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);
			$query = "SELECT * FROM user_table 
			WHERE `user_id` = '".$user['user_id']."'
      ";
      $sql = mysqli_query($db, $query);
      $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
			
			$output = '
			
					<div class="row">
			';

			foreach($result as $row)
			{
				$output .= '
			
				<div class="col-md-9">
					<table class="table table-bordered">
						<tr>
							<th>Name</th>
							<td>'.$row["user_name"].'</td>
						</tr>
						<tr>
							<th>Email ID</th>
							<td>'.$row["user_email_address"].'</td>
						</tr>
					</table>
				</div>
				';
			}
			$output .= '</div></div></div>';
			echo $output;
		}
		if($_POST['action'] == 'answer')
		{
       $query = " SELECT marks_per_right_answer,marks_per_wrong_answer FROM online_exam_table 
      WHERE online_exam_id = '".$_POST['exam_id']."' 
      ";

      $sql = mysqli_query($db, $query);
      $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

      foreach($result as $row)
      {
        $exam_right_answer_mark= $row['marks_per_right_answer'];
        $exam_wrong_answer_mark =$row['marks_per_wrong_answer'];
      }
			
        $query = "SELECT answer_option FROM question_table 
        WHERE question_id = '".$_POST['question_id']."' 
        ";

        $sql = mysqli_query($db, $query);
        $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

        foreach($result as $row)
        {
          $orignal_answer = $row['answer_option'];
        }

      $marks = 0;
			if($orignal_answer == $_POST['answer_option'])
			{
				$marks = $marks+$exam_right_answer_mark;
			}
			else
			{
				$marks = $marks- $exam_wrong_answer_mark;
      }
      $username = $_SESSION['username'];
        $user_check_query = "SELECT * FROM user_table WHERE `user_name`='$username'";
        $result = mysqli_query($db, $user_check_query);
      $user = mysqli_fetch_assoc($result);
			$query = "UPDATE user_exam_question_answer 
			SET user_answer_option = '".$_POST['answer_option']."', marks = '$marks'
			WHERE user_id = '".$user["user_id"]."' 
			AND exam_id = '".$_POST['exam_id']."' 
			AND question_id = '".$_POST["question_id"]."'
			";
			mysqli_query($db, $query);
    }
    
    if($_POST['action'] == 'submit')
		{
       $username = $_SESSION['username'];
        $user_check_query = "SELECT * FROM user_table WHERE `user_name`='$username'";
        $result = mysqli_query($db, $user_check_query);
      $user = mysqli_fetch_assoc($result);
			$query = "UPDATE user_exam_enroll_table
      SET submission='Yes' 
      WHERE user_id = '".$user['user_id']."'
      AND exam_id = '".$_POST['exam_id']."' 
			";
      mysqli_query($db, $query);
		}
  }

  // Change Password
  // -------------------------------------------------------------------------------------

  if($_POST['page'] == 'change_password')
	{
		if($_POST['action'] == 'change_password')
		{
      $username = $_SESSION['username'];
      $user_check_query = "SELECT * FROM user_table WHERE `user_name`='$username'";
      $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);
    $password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
			$query = "UPDATE user_table 
			SET user_password = '$password'
			WHERE user_id = '".$user['user_id']."'
			";

      mysqli_query($db, $query);

			session_destroy();

			$output = array(
				'success'		=>	'Password has been change'
			);

			echo json_encode($output);
		}
	}
}
?>