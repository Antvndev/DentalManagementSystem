<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="bg" data-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <script>
    (function() {
      try {
        const saved = localStorage.getItem('theme');
        if (saved) {
          document.documentElement.setAttribute('data-theme', saved);
        }
      } catch (e) {/* localStorage might be blocked in private mode */}
    })();
  </script>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/Patients_List.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <title>Patients List</title>
</head>
<body>
  <nav class="navbar">
    <a href="Patients_List.php" class="nav-item active"><i class='bx bx-group'></i><span>Patients</span></a>
    <button id="theme-toggle" class="nav-item" aria-label="Theme switch" >
      <i class='bx bx-moon'></i>
    </button>
  </nav>

  <div class="filter-bar" aria-label="Filters">
    <input type="text" id="searchInput" placeholder="Search by name..." autocomplete="off" />
    <button class="add-btn" onclick="location.href='Add_Patient.php'"><i class='bx bx-plus'></i></button>
  </div>

  <!-- Grid container where JS will render cards -->
  <main id="patientsDisplay" aria-live="polite"></main>

  <p id="noResults">There are no patients.</p>

  <noscript>
    <!-- Fallback: server-side rendering if JS is disabled -->
    <main>
      <?php
      $sql = "SELECT id, first_name, middle_name, last_name, egn, gender, age, phone FROM patients ORDER BY last_name";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0):
        while ($row = $result->fetch_assoc()): ?>
          <div class="mini-card">
            <h3 class="patient-name"><?= htmlspecialchars($row['first_name'].' '.$row['middle_name'].' '.$row['last_name']) ?></h3>
            <div class="info-row">
              <span><?= htmlspecialchars($row['gender']) ?></span>
              <span><?= htmlspecialchars($row['age']) ?>г.</span>
            </div>
            <div class="egn"><strong>ЕГН:</strong> <?= htmlspecialchars($row['egn']) ?></div>
            <div class="phone"><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></div>
            <button onclick="location.href='Patient_Details.php?id=<?= urlencode($row['id']) ?>'">Details -></button>
          </div>
      <?php endwhile; else: ?>
        <p>There are no patients.</p>
      <?php endif; ?>
    </main>
  </noscript>
</body>
</html>

<script src="JSScripts/Patient_search.js"></script> 
<script src="JSScripts/ThemeSwitcher.js"></script>