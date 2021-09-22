<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Submit Animal Info</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>

<?php
// define variables and set to empty values
$nameError = $categoryError = $pictureError = $lifespanError = $bioError = $ansError = $recaptchaMsg = $errors = "";
$name = $category = $picture = $lifespan = $bio = $ans = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameError = "Name of the animal is required.";
    } else {
        $name = test_input($_POST["name"]);
    }

    if (empty($_POST["category"])) {
        $categoryError = "Category of the animal is required.";
    } else {
        $category = test_input($_POST["category"]);
    }

    if (empty($_FILES['picture']['name'])) {
        $pictureError = "Photo of the animal is required.";
    } else {
        $picture = test_input($_FILES['picture']['name']);
    }

    if (empty($_POST["lifespan"])) {
        $lifespanError = "Life expectancy of the animal is required.";
    } else {
        $lifespan = test_input($_POST["lifespan"]);
    }

    if (empty($_POST["bio"])) {
        $bioError = "Description of the animal is required.";
    } else {
        $bio = test_input($_POST["bio"]);
    }

    if (empty($_POST["ans"])) {
        $ansError = "Answer is required.";
    } else {
        $ans = test_input($_POST["ans"]);
    }


	$response = $_POST["g-recaptcha-response"];
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array(
		'secret' => '6LfugYMcAAAAAOBWcEn4z4om7hGSQWnDqLfKavQM',
		'response' => $response
	);
	$options = array(
		'http' => array (
			'method' => 'POST',
			'content' => http_build_query($data),
            'header' => "Content-Type: application/x-www-form-urlencoded"
		)
	);
	$context  = stream_context_create($options);
	$verify = file_get_contents($url, false, $context);
	$captcha_success=json_decode($verify);

	if ($captcha_success->success==false) {
		$recaptchaMsg = "Sorry! We couldn't verify that you're not a bot!";
        echo "<span class='info-text'>Please make sure to complete recaptcha properly.</span><br>";
        $errors = "Error";
	}

    if(isset($_POST['btn'])){
        $img_loc =$_FILES['picture']['tmp_name'];
        $image_name = $_FILES['picture']['name'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $moved = move_uploaded_file($img_loc,"upload/".$_POST['name'].".".$image_ext);
        if (!$moved) {
            echo "<span class='info-text'>Couldn't upload the image.</span><br>";
            $errors = "Error";
        }
    }


    if($nameError == "" && $categoryError == "" && $pictureError == "" && $lifespanError == "" && $bioError == ""){
        function get_data() {
            $name = $_POST['name'];
            $file_name='animals'.'.json';
            $dateTime = date('Y-m-d H:i:s');
    
            if(file_exists("$file_name")) { 
                $current_data=file_get_contents("$file_name");
                $array_data=json_decode($current_data, true);
    
                $extra=array(
                    'Name' => $_POST['name'],
                    'Category' => $_POST['category'],
                    'Picture' => $_POST['name'].".".pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION),
                    'Lifespan' => $_POST['lifespan'],
                    'Bio' => $_POST["bio"],
                    'DateTime' => $dateTime
                );
                $array_data[]=$extra;
                return json_encode($array_data);
            }
            else {
                $datae=array();
                $datae[]=array(
                    'Name' => $_POST['name'],
                    'Category' => $_POST['category'],
                    'Picture' => $_POST['name'].".".pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION),
                    'Lifespan' => $_POST['lifespan'],
                    'Bio' => $_POST["bio"],
                    'DateTime' => $dateTime
                );
                return json_encode($datae);   
            }
        }

        $file_name='animals'.'.json';

        if(!file_put_contents("$file_name", get_data())) {
            echo '<span class="info-text">Submission Failure</span>';
            $errors = "Error";
        }

        if ($errors) {
            echo "<span class='info-text'>Submission Failed :(</span><br>";
            echo '<a href="submission.php" class=".btn-info">Submit Again</a>';
            die();
        }

        if (!$errors) {
            header('Location: animals.php');
        }
    }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<div class="container">

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">

        <h1>Submit New Animal Info</h1> or&nbsp; <a href="animals.php" class="btn-info">View Submitted Animal Info</a> 
        <p><span class="error">* denotes required field</span></p>
        <label for="name">Name of the animal:</label>
        <input type="text" name="name" id="name">
        <span class="error">* <?php echo $nameError;?></span>

        <br>
        <br>
        <label for="category">Category:</label>
        <input type="radio" name="category" value="herbivores" id="herbivores">
        <label for="herbivores">Herbivores</label>
        <input type="radio" name="category" value="omnivores" id="omnivores">
        <label for="omnivores">Omnivores</label>
        <input type="radio" name="category" value="carnivores" id="carnivores">
        <label for="carnivores">Carnivores</label>
        <span class="error">* <?php echo $categoryError;?></span>

        <br>
        <br>
        <label for="picture">Upload a photo:</label>
        <input type="file" name="picture" value="" id="animal-pic">
        <span class="error">* <?php echo $pictureError;?></span>
    
        <br>
        <br>
        <label for="">Life expectancy:</label>
        <select name="lifespan" id="lifespan">
            <option value="0-1">0-1 year</option>
            <option value="1-5">1-5 years</option>
            <option value="5-10">5-10 years</option>
            <option value="10+">10+ years</option>
        </select>
        <span class="error">* <?php echo $lifespanError;?></span>
    
        <br>
        <br>
        <label for="bio">Description:</label>
        <span class="error">* <?php echo $bioError;?></span>
        <br>
        <br>
        <textarea name="bio" id="bio" cols="30" rows="10" placeholder="Description of the animal"></textarea>


        <div class="g-recaptcha" data-sitekey="6LfugYMcAAAAAAPAIpGqnHImvFwQh_ZfbQo0W4ik"></div>

        <br>
        <br>
        <input type="submit" value="Submit" name="btn" class="btn-info">
    </form>
    </div>
</body>
</html>