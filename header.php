
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Online Examination Portal">
    <meta name="author" content="Mridu Shukla">
	<title>ExamOn</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
  	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/gh/guillaumepotier/Parsley.js@2.9.1/dist/parsley.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  	<link rel="stylesheet" href="style/style.css" />
      <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top" role="navigation" style="background:#424141">
        <div class="container" >
            
            <div class="navbar-header">
            <a class="navbar-brand" href="/ExamOn/" style="color:#FFFFFF">ExamOn</a>
            </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
      <?php  if (isset($_SESSION['username'])) { ?>
    	
        <div class="collapse navbar-collapse  justify-content-end" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav ">
                    
					 <li class="nav-item" style="color:#FFFFFF">
                        <a class="nav-link" style="color:#FFFFFF" href="/ExamOn/"><i class="fa fa-home fa-fw"></i>Dashboard</a>
                    </li>
					
					<li class="nav-item" style="color:#FFFFFF">
                        <a class="nav-link" style="color:#FFFFFF" href="index.php?info=profile"><i class="fa fa-asterisk fa-fw"></i>Profile</a>
                    </li>
					
	                <li class="nav-item">
                    <a class="nav-link" style="color:#FFFFFF" href="enroll_exam.php">Enroll Exam</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" style="color:#FFFFFF" href="change_password.php">Change Password</a>
                    </li>

                    <li class="nav-item" >
                    <a class="nav-link" style="color:#FFFFFF" href="index.php?logout='1'"><i class="fa fa-sign-out fa-fw"></i>Logout</a></li>
	
                </ul>
            </div>
      <?php }else { ?>
            <div class="collapse navbar-collapse  justify-content-end" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav ">
                    
					 <li class="nav-item" style="color:#FFFFFF">
                        <a class="nav-link" style="color:#FFFFFF" href="/ExamOn/"><i class="fa fa-home fa-fw"></i>Home</a>
                    </li>
					
					<li class="nav-item" style="color:#FFFFFF">
                        <a class="nav-link" style="color:#FFFFFF" href="index.php?info=about"><i class="fa fa-asterisk fa-fw"></i>About</a>
                    </li>
					
					<li class="nav-item" >
                    <a class="nav-link" style="color:#FFFFFF" href="index.php?info=register"><i class="fa fa-sign-in fa-fw"></i>Register</a></li>
	  
	 <li class="nav-item" >
                        <a class="nav-link" style="color:#FFFFFF" href="index.php?info=contact"><i class="fa fa-phone fa-fw"></i>Contact</a>
                    </li>
	
                </ul>
            </div>
            <?php } ?>
        </div>
    </nav>

