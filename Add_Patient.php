<?php include 'config.php'; ?>

<?php
// Reset patients if button is pressed
if (isset($_POST['reset_patients'])) {
    $sql = "TRUNCATE TABLE patients";
    if ($conn->query($sql) === TRUE) {
        header("Location: Patients_List.php");
        echo "✅ All patients have been cleared!";
        exit();
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first  = $_POST['first_name'];
    $middle = $_POST['middle_name'];
    $last   = $_POST['last_name'];
    $gender = $_POST['gender'];
    $age    = $_POST['age'];
    $phone  = $_POST['phone'];

    $sql = "INSERT INTO patients (first_name, middle_name, last_name, gender, age, phone) 
            VALUES ('$first', '$middle', '$last', '$gender', '$age', '$phone')";

    if ($conn->query($sql)) {
        header("Location: Patients_List.php");
        exit();
    } else {
        die("❌ Error: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light"> 
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>
    (function() {
      try {
        const saved = localStorage.getItem('theme');
        if (saved) {
          document.documentElement.setAttribute('data-theme', saved);
        }
      } catch (e) {
        /* localStorage might be blocked in private mode */
      }
    })();
  </script>
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/add_patient.css">
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
        <title>Add Patient</title>
    </head>
    <body>
        <form method="POST" autocomplete="off">
            <button onclick="location.href='Patients_List.php'" class="back-btn">
                <i class='bx bx-left-arrow-alt'></i>
            </button>
            <h2>Нов Пациент</h2>
            <input type="text" name="first_name" placeholder="Име" required><br><br>
            <input type="text" name="middle_name" placeholder="През Име"><br><br>
            <input type="text" name="last_name" placeholder="Фамилия" required><br><br>
            <div class="gender-row">
                <label for="male">Мъж
                <input type="radio" id="male" name="gender" value="Мъж" required>
                </label>
                <label for="female">Жена
                <input type="radio" id="female" name="gender" value="Жена">
                </label>
            </div>
            <input type="number" name="age" placeholder="Възраст" min="0" max="120" required>
            <input type="text" name="phone" placeholder="Телефонен номер"><br><br>
            <button type="submit">Добави Пациент</button>
            <button type="submit" name="reset_patients" class="reset-btn">
                🔄 Reset Patients (Dev Only)
            </button>
        </form>
    </body>
</html>
<script src="JSScripts/ThemeSwitcher.js"></script>
