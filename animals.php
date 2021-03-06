<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="style.css">
  <title>Animals</title>
</head>
<body>
<h2 class="center text-medium">Animals</h2>
<?php
$handle = fopen("counter.txt", "r"); 
if(!$handle) { 
    echo "could not open the counter file"; 
} else { 
    $counter =(int )fread($handle,20);
        fclose($handle); 
        $counter++; 
        echo"<span class='counter'>Number of visitors so far: ". $counter . "</span>" ; 
    $handle = fopen("counter.txt", "w" ); 

    fwrite($handle,$counter);
    fclose ($handle); 
}
?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
        <h5>Filters</h5>
        <label for="">Life expectancy:</label>
        <select name="lifespan" id="lifespan">
            <option value="all" selected value>All</option>
            <option value="0-1">0-1 year</option>
            <option value="1-5">1-5 years</option>
            <option value="5-10">5-10 years</option>
            <option value="10+">10+ years</option>
        </select>

        &nbsp;&nbsp;
        <label for="">Category</label>
        <select name="category" id="lifespan">
            <option value="all" selected value>All</option>
            <option value="herbivores">herbivores</option>
            <option value="omnivores">omnivores</option>
            <option value="carnivores">carnivores</option>
        </select>

        &nbsp;&nbsp;
        <input type="submit" value="Submit" name="btn" class="btn-info">

        &nbsp;&nbsp;
        <a href="submission.php" class="btn-info">Add New Animal</a> 
    </form>

  <table>
    <tr>
      <th>Name</th>
      <th>Category</th>
      <th>Picture</th>
      <th>Lifespan</th>
      <th>Description</th>
      <th>Added on</th>
    </tr>
<?php

  // Read the JSON file 
  $json = file_get_contents('animals.json');

  // Decode the JSON file
  $animals = json_decode($json,true);


if(isset($animals)) {

if(isset($_POST['btn'])){
  for ($i = 0; $i < count($animals); $i++) {
    if ($animals[$i]['Category'] == $_POST['category'] || $_POST['category'] == "all") {
      if ($animals[$i]['Lifespan'] == $_POST['lifespan'] || $_POST['lifespan'] == "all") {
        echo "<tr>";
        echo "<td>".$animals[$i]['Name']."</td>";
        echo "<td>".ucwords($animals[$i]['Category'])."</td>";
        echo "<td>";
        echo "<img src='upload/".$animals[$i]['Picture']."'"."width='250'>";
        echo "</td>";
        echo "<td>".$animals[$i]['Lifespan']."</td>";
        echo "<td>".$animals[$i]['Bio']."</td>";
        echo "<td>".$animals[$i]['DateTime']."</td>";
        echo "</tr>";
      }
    }
  }
} else {
  for ($i = 0; $i < count($animals); $i++) {
    echo "<tr>";
    echo "<td>".$animals[$i]['Name']."</td>";
    echo "<td>".ucwords($animals[$i]['Category'])."</td>";
    echo "<td>";
    echo "<img src='upload/".$animals[$i]['Picture']."'"."width='250'>";
    echo "</td>";
    echo "<td>".$animals[$i]['Lifespan']."</td>";
    echo "<td>".$animals[$i]['Bio']."</td>";
    echo "<td>".$animals[$i]['DateTime']."</td>";
    echo "</tr>";
  }
}
}
?>

<img src="" alt="">
  </table>  
</body>
</html>