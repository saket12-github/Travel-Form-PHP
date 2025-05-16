<?php

$insert = false;
if (isset($_POST['submit'])){  //$_SERVER["REQUEST_METHOD"] == "POST"
    // Set connection variables
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "trip";
    // Create a database connection
    $conn = mysqli_connect($server, $username, $password, $dbname);

    // Check for connection success
    if(!$conn){
        die("connection to this database failed due to" . mysqli_connect_error());
    }
    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $hasError = false;
    $nameErr = $ageErr = $emailErr = $phoneErr = $genderErr = "";
    $name = $age = $email = $phone = $gender = $desc = "";

    $name = test_input($_POST['name']);
    if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $nameErr = "Only letters and white space allowed";
        $hasError = true;
    }
    $gender = test_input($_POST['gender']);
    $age = test_input($_POST['age']);
    if ($age < 18 || $age > 150) {
        $ageErr = "Sorry, you cannot register for the trip.";
        $hasError = true;
    }
    $email = test_input($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        $hasError = true;
    }
    $phone = test_input($_POST['phone']);
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $phoneErr = "Invalid Phone Number";
        $hasError = true;
    }
    $desc = test_input($_POST['desc']);
    if (!$hasError) {
        $sql = "INSERT INTO `trip`.`trip` (`name`, `age`, `gender`, `email`, `phone`, `other`, `dt`) VALUES ('$name', '$age', '$gender', '$email', '$phone', '$desc', current_timestamp());";
        if (mysqli_query($conn, $sql)) {
            $insert = true;
        } else {
            echo "ERROR: $sql <br>" . mysqli_error($conn);
        }
    }
    if ($insert) {
    $name = $gender = $age = $email = $phone = $desc = "";
    }
    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Travel Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    
    <div class="container">
        <div class="form-container">
            <h1>Welcome to Image Online Trip form</h3>
            <p>Enter your details and submit this form to confirm your participation in the trip </p>
            <p>Today's date is <?php echo date("Y-m-d"); ?>, Please fill the form before <?php echo date("Y-m-d",strtotime("May 31 2025 23:59:59"));?> </p>
            <?php
                if($insert == true){
                echo "<p class='submitMsg'>Thanks for submitting your form. We are happy to see you joining us for the US trip</p>";
                }
                ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <input type="text" name="name" id="name" placeholder="Enter your name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                    <span class="error-message" id="name-error"><?php echo $nameErr ?? ''; ?></span>
                    <input type="text" name="age" id="age" placeholder="Enter your Age" value="<?php echo htmlspecialchars($age ?? ''); ?>" required>
                    <span class="error-message" id="age-error"><?php echo $ageErr ?? ''; ?></span>
                    <select name="gender" id="gender" required>
                        <option value="" disabled selected>Select your gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="others">Others</option>
                    </select>
                    <span class="error-message" id="gender-error"></span>
                    <input type="email" name="email" id="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    <span class="error-message" id="email-error"><?php echo $emailErr ?? ''; ?></span>
                    <input type="phone" name="phone" id="phone" placeholder="Enter your phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>" required>
                    <span class="error-message" id="phone-error"><?php echo $phoneErr ?? ''; ?></span>
                    <span class="error-message" id="desc-error"></span>
                    <textarea name="desc" id="desc" cols="30" rows="10" placeholder="Enter any other information here" value="<?php echo htmlspecialchars($desc ?? ''); ?>"></textarea>
                    
                    <button class="btn" name="submit">Submit</button> 
                </form>
                <form method="post">
                    <button class="btn" name="show_names">Show Registered Users</button>
                </form>
                                
                <?php
                if (isset($_POST['show_names'])) {
                    // DB connection
                    $servername = "localhost";
                    $username = "root";
                    $password = ""; // Default for XAMPP
                    $database = "trip";

                    // Create connection
                    $conn = mysqli_connect($servername, $username, $password, $database);

                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Fetch names
                    $sql = "SELECT name FROM trip";
                    $result = mysqli_query($conn, $sql);

                    echo "<style>
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 15px;
                        }
                        th, td {
                            border: 1px solid #ccc;
                            padding: 10px;
                            text-align: center;
                        }
                        th {
                            background-color: #f2f2f2;
                            font-weight: bold;
                        }
                        tr:nth-child(even) {
                            background-color: #f9f9f9;
                        }
                        .container {
                            max-width: 600px;
                            margin: auto;
                            font-family: Arial, sans-serif;
                        }
                        h2 {
                            text-align: center;
                            color: #333;
                        }
                    </style>";

                    echo "<div class='container'>";
                    echo "<h2>Registered Users:</h2>";

                    // Start table
                    echo "<table border='1' cellpadding='8' cellspacing='4' width='100%'>";
                    echo "<tr><th>SNo</th><th>Name</th></tr>";

                    if (mysqli_num_rows($result) > 0) {
                        $serial = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td text-align='center'>" . $serial++ . "</td>";
                            echo "<td text-align='center'>" . htmlspecialchars($row['name']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No users registered yet.</td></tr>";
                    }

                    echo "</table>";
                    echo "</div>";

                    mysqli_close($conn);
                }
            ?>
        </div>
    </div>
    
    <script src="index.js"></script>
    
</body>
</html>
