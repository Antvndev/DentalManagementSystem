<?php include 'config.php'; ?>

<?php
// Reset entire DB info if button is pressed
if (isset($_POST['reset_patients'])) {

    // Delete dependent tables first
    $conn->query("DELETE FROM dental_chart");
    $conn->query("ALTER TABLE dental_chart AUTO_INCREMENT = 1");

    $conn->query("DELETE FROM visitations");
    $conn->query("ALTER TABLE visitations AUTO_INCREMENT = 1");

    // Now delete patients
    $conn->query("DELETE FROM patients");
    $conn->query("ALTER TABLE patients AUTO_INCREMENT = 1");

    header("Location: Patients_List.php");
    exit();
}

// Add a new patient
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['reset_patients'])) {
    $first  = $_POST['first_name'];
    $middle = $_POST['middle_name'];
    $last   = $_POST['last_name'];
    $egn    = $_POST['egn'];  // NEW
    $gender = $_POST['gender'];
    $age    = $_POST['age'];
    $phone  = $_POST['phone'];

    $sql = "INSERT INTO patients (first_name, middle_name, last_name, egn, gender, age, phone) 
            VALUES ('$first', '$middle', '$last', '$egn', '$gender', '$age', '$phone')";

    if ($conn->query($sql)) {
        header("Location: Patients_List.php");
        exit();
    } else {
        die('❌ Error: ' . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script>
    (function() {
      try {
        const saved = localStorage.getItem('theme');
        if (saved) document.documentElement.setAttribute('data-theme', saved);
      } catch (e) {}
    })();
  </script>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/add_patient.css">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <title>Add Patient</title>
</head>

<body>
  <form method="POST" autocomplete="off">
        <button onclick="location.href='Patients_List.php'" class="back-btn" type="button">
        <i class='bx bx-left-arrow-alt'></i>
        </button>
        <h2>New Patient</h2>

        <input type="text" name="first_name" placeholder="First name" required><br><br>
        <input type="text" name="middle_name" placeholder="Middle name"><br><br>
        <input type="text" name="last_name" placeholder="Last name" required><br><br>

        <div class="gender-row">
        <label for="male">Male
            <input type="radio" id="male" name="gender" value="Male" required>
        </label>
        <label for="female">Female
            <input type="radio" id="female" name="gender" value="Female">
        </label>
        </div>

        <input type="text" name="egn" placeholder="ЕГН" pattern="\d{10}" maxlength="10" required>
        <input type="number" name="age" placeholder="Age" min="0" max="120" required>
        
        <input type="text" name="phone" placeholder="Phone number"><br><br>

        <button type="submit">Add Patient</button>
        <!-- 
            <button type="submit" name="reset_patients" class="reset-btn">
                Reset Patients (Dev Only)
            </button> -->
    </form>
</body>
</html>
<script src="JSScripts/ThemeSwitcher.js"></script>
