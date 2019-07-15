<?php
include("config.php");
session_start();

$salesprice = $count = 0;
$search = $_GET["search"];
$searchby = $_GET["search-by"];
 switch ($searchby){
   case "model-year":
    $sql1 = "SELECT VIN, mileage, model_name, model_year, type, manufacturer FROM vehicle WHERE model_year LIKE '%$search%'
            AND VIN NOT IN (SELECT VIN FROM salestransaction)";
    break;
   case "manufacturer":
    $sql1 = "SELECT VIN, mileage, model_name, model_year, type, manufacturer FROM vehicle WHERE manufacturer LIKE '%$search%'
            AND VIN NOT IN (SELECT VIN FROM salestransaction)";
    break;
   case "vin":
    $sql1 = "SELECT VIN, mileage, model_name, model_year, type, manufacturer FROM vehicle WHERE VIN LIKE '%$search%'";
    break;
   case "vehicle-type":
    $sql1 = "SELECT VIN, mileage, model_name, model_year, type, manufacturer FROM vehicle WHERE type LIKE '%$search%'
            AND VIN NOT IN (SELECT VIN FROM salestransaction)";
    break;
   case "color":
    $sql1 = "SELECT vehicle.VIN, mileage, model_name, model_year, type, manufacturer FROM vehicle INNER JOIN vehiclecolor
            ON vehicle.VIN=vehiclecolor.VIN WHERE vehiclecolor.color LIKE '%$search%' AND vehicle.VIN NOT IN (SELECT VIN FROM salestransaction)";
    break;
   case "keyword":
    $sql1 = "SELECT VIN, mileage, model_name, model_year, type, manufacturer FROM vehicle WHERE VIN NOT IN (SELECT VIN FROM salestransaction)
            AND (manufacturer LIKE '%$search%' or model_year LIKE '%$search%' or model_name LIKE '%$search%' or description LIKE '%$search%')";
    break;
 }
$result = mysqli_query($conn, $sql1);
$count = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Search Results</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.1/js/jquery.tablesorter.min.js"></script>
    <script>
      $(document).ready(function(){
        $(function() {
          $("#results").tablesorter({ sortList: [0,0] });
        });
        $('tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = "vehicle-info.php?VIN="+href;
        }
    });
      });
    </script>
  </head>
  <style>
    body{
      padding: 20px;
    }
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
    }
    th, td {
      padding: 5px;
    }
    th {
      text-align: left;
      background-color: #72a1ed;
    }
    tbody tr:hover{
      background-color: #ccc;
      cursor: pointer;
    }
    .topnav {
      background-color: #333;
      overflow: hidden;
      text-align: left;
      height: 50px;
      line-height: 50px;
    }
    .topnav span {
      padding: 10px;
      color: #f2f2f2;
      font-size: 20px;
    }
    .topnav a {
      float: right;
      color: #f2f2f2;
      text-align: center;
      padding: 0px 15px;
      text-decoration: none;
      font-size: 17px;
    }
    .topnav a:hover{
      background-color: #ddd;
      color: black;
    }
  </style>
  <body>
    <div class="topnav">
      <span> Welcome to Burdell's Ramblin' Wrecks! </span>
      <a href='index.php'>Home</a>
      <?php if(isset($_SESSION['login_user'])){
        echo "<a href='logout.php'>Logout</a>";
      }?>
    </div>
    <div style="padding-bottom:20px">
      <h2>Search Results</h2>
      <?php echo $count; ?> vehicles matched your search criteria
    </div>
    <?php if($count > 0){
      echo '
      <table id="results" style="width:100%" class="tablesorter">
      <thead>
      <tr>
      <th>VIN</th>
      <th>Vehicle Type</th>
      <th>Model Year</th>
      <th>Manufacturer</th>
      <th>Model</th>
      <th>Color(s)</th>
      <th>Mileage</th>
      <th>Sales Price</th>
      </tr>
      </thead>
      <tbody>';
      while($row = $result->fetch_assoc()){
        $VIN = $row["VIN"];
        $sql2 = "SELECT color FROM vehiclecolor WHERE VIN='$VIN'";
        $result2 = mysqli_query($conn, $sql2);
        $color = $result2->fetch_assoc()["color"];
        $sql3 = "SELECT purchase_price FROM purchasetransaction WHERE VIN='$VIN'";
        $result3 = mysqli_query($conn, $sql3);
        $price = $result3->fetch_assoc()["purchase_price"];
        $price = number_format($price * 1.25,2);
        echo '<tr>
        <td><a href="', $VIN, '">', $VIN, '</a</td>
        <td>', $row["type"], '</td>
        <td>', $row["model_year"], '</td>
        <td>', $row["manufacturer"], '</td>
        <td>', $row["model_name"], '</td>
        <td>', $color, '</td>
        <td>', number_format($row["mileage"]), '</td>
        <td> $', $price, '</td>
        </tr>';
      }
      echo '</tbody>
      </table>';
    }else {
      echo "<span style='font-size:20px'>Sorry, it looks like we don't have that in stock!</span>";
    }
    ?>
  </body>
</html>
