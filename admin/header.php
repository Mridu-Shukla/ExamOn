<?php

//header.php

include('../dbconfig.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  	<title>ExamOn|Admin</title>
  	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/guillaumepotier/Parsley.js@2.9.1/dist/parsley.js"></script>
  	<link rel="stylesheet" href="../style/style.css" />
    <link rel="stylesheet" href="../style/bootstrap-datetimepicker.css" />
    <script src="../style/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

</head>
<body>
	<!-- <div class="jumbotron text-center" style="margin-bottom:0; padding: 1rem 1rem;">
  		<img src="logo.png" class="img-fluid" width="300" alt="Online Examination System in PHP" />
	</div> -->

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
                        <a class="nav-link" style="color:#FFFFFF" href="/ExamOn/admin/index.php"><i class="fa fa-home fa-fw"></i>Dashboard</a>
                    </li>
					
					
					
	                <li class="nav-item">
                    <a class="nav-link" style="color:#FFFFFF" href="exam.php">Exam</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" style="color:#FFFFFF" href="user.php">User</a>
                    </li>

                    <li class="nav-item" >
                    <a class="nav-link" style="color:#FFFFFF" href="logout.php"><i class="fa fa-sign-out fa-fw"></i>Logout</a></li>
	
                </ul>
            </div>
</nav>
	<div class="container-fluid">