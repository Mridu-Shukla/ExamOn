<?php

//index.php

include('header.php');
if(!isset($_SESSION['username'])){
    header("location: login.php");
}
        $query = "
		SELECT * FROM user_table";
		$sql = mysqli_query($db, $query);
		$total_users = mysqli_num_rows($sql);
        $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
        
        $query = "
		SELECT * FROM online_exam_table WHERE online_exam_datetime > '$current_datetime'";
		$sql = mysqli_query($db, $query);
        $upcoming_exams = mysqli_num_rows($sql);
        $query = "
		SELECT * FROM online_exam_table";
		$sql = mysqli_query($db, $query);
		$total_exams = mysqli_num_rows($sql);

?>

<div class="container py-5">
    <div class='row'>
        <div class="card col-lg-4" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">REGISTERED USERS</h5>
                <!-- <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6> -->
                <p class="card-text"><?php echo $total_users; ?></p>
                <a href="user.php" class="card-link">View</a>
                <!-- <a href="#" class="card-link">Another link</a> -->
            </div>
        </div>
        <br>
        <div class="card col-lg-4 mx-5" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Upcoming Exams</h5>
                <!-- <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6> -->
                <p class="card-text"><?php echo $upcoming_exams; ?> <span id="piechart"></span></p>
                <a href="exam.php" class="card-link">View</a>
                <!-- <a href="#" class="card-link">Another link</a> -->
            </div>
        </div><br>
        <div class="card col-lg-4 my-5" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Total Exams</h5>
                <!-- <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6> -->
                <p class="card-text"><?php echo $total_exams; ?></p>
                <a href="exam.php" class="card-link">View</a>
                <!-- <a href="#" class="card-link">Another link</a> -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
    var ne = <?php echo $upcoming_exams;?>;
    var te = <?php echo $total_exams;?>;
  var data = google.visualization.arrayToDataTable([
  ['Exams', 'Number'],
  ['Upcoming', ne],
  ['Completed', te-ne],
]);

  // Optional; add a title and set the width and height of the chart
  var options = {'width':300, 'height':150};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}
</script>
  		
	