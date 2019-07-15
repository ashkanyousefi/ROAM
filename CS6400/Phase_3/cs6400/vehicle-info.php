<?php
include("config.php");
session_start();
header_remove();
$VIN = $_GET["VIN"];
$sql1 = "SELECT mileage, model_name, model_year, description, type, manufacturer FROM vehicle WHERE VIN='$VIN'";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_row($result1);
$mileage = number_format($row1[0]);
$model_name = $row1[1];
$model_year = $row1[2];
$description = $row1[3];
$type = $row1[4];
$manufacturer = $row1[5];
$sql2 = "SELECT purchase_price FROM purchasetransaction WHERE VIN='$VIN'";
$result2 = mysqli_query($conn, $sql2);
$salesprice = mysqli_fetch_assoc($result2)["purchase_price"];
$salesprice = number_format($salesprice * 1.25,2);
$sql3 = "SELECT color FROM vehiclecolor WHERE VIN='$VIN'";
$result3 = mysqli_query($conn, $sql3);
$color = $result3->fetch_assoc()["color"];
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Vehicle Information</title>
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
      <a href='/cs6400/index.php'>Home</a>
      <?php if(isset($_SESSION['login_user'])){
        echo "<a href='/cs6400/logout.php'>Logout</a>";
      }?>
    </div>
    <div>
      <p style="font-size: 20px">Your selected vehicle:</p>
    </div>
    <table id="results" style="width:75%">
      <thead>
        <tr>
          <th>Attribute</th>
          <th>Value</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>VIN</td>
          <td><?php echo $VIN;?></td>
        </tr>
        <tr>
          <td>Vehicle Type</td>
          <td><?php echo $type;?></td>
        </tr>
        <tr>
          <td>Model Year</td>
          <td><?php echo $model_year;?></td>
        </tr>
        <tr>
          <td>Manufacturer</td>
          <td><?php echo $manufacturer;?></td>
        </tr>
        <tr>
          <td>Model Name</td>
          <td><?php echo $model_name;?></td>
        </tr>
        <tr>
          <td>Color(s)</td>
          <td><?php echo $color;?></td>
        </tr>
        <tr>
          <td>Mileage</td>
          <td><?php echo $mileage;?></td>
        </tr>
        <tr>
          <td>Sales Price</td>
          <td><?php echo "$",$salesprice;?></td>
        </tr>
        <tr>
          <td>Description</td>
          <td><?php echo $description;?></td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
