<?php 
include_once 'config.php';

if ($_POST["REQUEST_METHOD"] == "POST"){
    $from = $_POST["from"];
    $to = $_POST["to"];
    $depart = $_POST["depart"];
    $return = $_POST["return"];
    $adults = $_POST["adults"];
    $children = $_POST["children"];
    $infants = $_POST["infants"];

    if (!empty($from) && !empty($to) && !empty($depart) && !empty($return) && !empty($adults) && !empty($children) && !empty($infants));

    $stmt = $conn->prepare("INSERT INTO ")
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header class="dashboard-container">
        <h3>Hi, where do you like to go?</h3>
        <h4>flight</h4>
    </header>
    
    <form action="dashboard.php" method="POST">
        <div class="col">
              <label for="from">From</label>
        <input type="text" id="from" name="from" placeholder="Select Origin">
        </div>

         <div class="col">
            <label for="to">To</label>
        <input type="text" id="to" name="to" placeholder="Select Destination">
        </div>

           <div class="col">
            <label for="depart">Depart</label>
        <input type="date" id="depart" name="depart" placeholder="Select Destination">
        </div>

         <div class="col">
            <label for="return">Return</label>
        <input type="date" id="return" name="return" placeholder="Select Destination">
        </div>

        <div class="col1">
            <label for="adults">Adults</label>
        <input type="number" id="adults" name="adults" placeholder="0">
        <p>12+ years</p>
        </div>

         <div class="col1">
            <label for="children">Children</label>
        <input type="number" id="children" name="children" placeholder="0">
        <p>2-11 years</p>
        </div>

         <div class="col1">
            <label for="infants">infants</label>
        <input type="number" id="infants" name="infants" placeholder="0">
        <p>under 2 years</p>
        </div>
    <button type="submit" class="btn">Book Flight</button>
    </form>
</body>
</html>