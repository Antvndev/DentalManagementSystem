<?php
include 'config.php';

// Get patient ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid patient ID');
}
$patient_id = intval($_GET['id']);

// Fetch patient from DB
$stmt = $conn->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die('Patient not found');
}
$patient = $result->fetch_assoc();

// Fetch saved chart for this patient
$chartData = [];
$stmt = $conn->prepare("SELECT tooth_number, `condition` FROM dental_chart WHERE patient_id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $chartData[$row['tooth_number']] = $row['condition'];
}
$stmt->close();

$condition_labels = [
    'caries'          => 'Кариес',
    'crown'           => 'Коронка',
    'implant'         => 'Имплант',
    'missing'         => 'Изваден',
    'pulpitis'        => 'Пулпит',
    'periodontitis'   => 'Периодонтит',
    'periodontitis_gum' => 'Пародонтит',
    'root'            => 'Корен',
    'obturate'        => 'Обтурация',
    'fracture'        => 'Фрактура',
    'clear'           => 'Изчисти'
];


function render_tooth($num, $chartData, $labels) {
    $numEsc = htmlspecialchars($num);

    $cond = isset($chartData[$num]) && $chartData[$num] !== '' ? $chartData[$num] : '';

    // Determine if tooth is marked as "fixed" (filling)
    $isFixed = $cond === 'filling';
    $fixedClass = $isFixed ? ' fixed' : '';

    // If the tooth has a condition, but is not filling, set data-condition
    $condAttr = (!$isFixed && $cond) ? ' data-condition="' . htmlspecialchars($cond) . '"' : '';

    // Tooltip title
    $title = '';
    if ($isFixed) {
        $title = 'Пломба';
    } elseif ($cond && isset($labels[$cond])) {
        $title = htmlspecialchars($labels[$cond]);
    }
    $titleAttr = $title ? ' title="' . $title . '"' : '';

    // Return tooth div with small fixed button
    return <<<HTML
<div class="tooth{$fixedClass}" data-tooth="{$numEsc}"{$condAttr}{$titleAttr}>
    {$numEsc}
    <span class="fixed-btn">✔</span>
</div>
HTML;
}

?>
<!DOCTYPE html>
<html lang="bg" data-theme="light">
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
    <link rel="stylesheet" href="css/Patient_Details.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <title>Patient Details</title>
  </head>
  <body data-patient="<?= htmlspecialchars($patient_id) ?>">
    <div class="Header">
      <button onclick="location.href='Patients_List.php'" class="back-btn">
        <i class='bx bx-left-arrow-alt'></i>
      </button>

      <h1><?= htmlspecialchars($patient['first_name'] . ' ' . $patient['middle_name'] . ' ' . $patient['last_name']) ?></h1>

      <div class="Tab-Bar">
        <button class="Tab-Link" onclick="openTab(event, 'PatientChart')">Лечебен Картон</button>
        <button class="Tab-Link" onclick="openTab(event, 'Photos')">Снимки</button>
      </div>
    </div>

    <section class="Tab-container">

    <!-- Patient chart tab -->
    <div id="PatientChart" class="Tab-Content">
      
      <!-- Tooth Chart -->
      <section class="Tooth-Chart">
        <h2>Зъбен Картон</h2>

        <!-- Upper row -->
        <div class="teeth upper">
          <div class="quadrant" id="Q1">
            <?= render_tooth('18', $chartData, $condition_labels) ?>
            <?= render_tooth('17', $chartData, $condition_labels) ?>
            <?= render_tooth('16', $chartData, $condition_labels) ?>
            <?= render_tooth('15', $chartData, $condition_labels) ?>
            <?= render_tooth('14', $chartData, $condition_labels) ?>
            <?= render_tooth('13', $chartData, $condition_labels) ?>
            <?= render_tooth('12', $chartData, $condition_labels) ?>
            <?= render_tooth('11', $chartData, $condition_labels) ?>
          </div>

          <div class="mid-gap"></div>

          <div class="quadrant" id="Q2">
            <?= render_tooth('21', $chartData, $condition_labels) ?>
            <?= render_tooth('22', $chartData, $condition_labels) ?>
            <?= render_tooth('23', $chartData, $condition_labels) ?>
            <?= render_tooth('24', $chartData, $condition_labels) ?>
            <?= render_tooth('25', $chartData, $condition_labels) ?>
            <?= render_tooth('26', $chartData, $condition_labels) ?>
            <?= render_tooth('27', $chartData, $condition_labels) ?>
            <?= render_tooth('28', $chartData, $condition_labels) ?>
          </div>
        </div>

        <!-- Lower row -->
        <div class="teeth lower">
          <div class="quadrant" id="Q4">
            <?= render_tooth('48', $chartData, $condition_labels) ?>
            <?= render_tooth('47', $chartData, $condition_labels) ?>
            <?= render_tooth('46', $chartData, $condition_labels) ?>
            <?= render_tooth('45', $chartData, $condition_labels) ?>
            <?= render_tooth('44', $chartData, $condition_labels) ?>
            <?= render_tooth('43', $chartData, $condition_labels) ?>
            <?= render_tooth('42', $chartData, $condition_labels) ?>
            <?= render_tooth('41', $chartData, $condition_labels) ?>
          </div>

          <div class="mid-gap"></div>

          <div class="quadrant" id="Q3">
            <?= render_tooth('31', $chartData, $condition_labels) ?>
            <?= render_tooth('32', $chartData, $condition_labels) ?>
            <?= render_tooth('33', $chartData, $condition_labels) ?>
            <?= render_tooth('34', $chartData, $condition_labels) ?>
            <?= render_tooth('35', $chartData, $condition_labels) ?>
            <?= render_tooth('36', $chartData, $condition_labels) ?>
            <?= render_tooth('37', $chartData, $condition_labels) ?>
            <?= render_tooth('38', $chartData, $condition_labels) ?>
          </div>
        </div>

        <div class="condition-toolbar">
          <button class="condition" data-condition="caries">Кариес</button>
          <button class="condition" data-condition="pulpitis">Пулпит</button>
          <button class="condition" data-condition="periodontitis">Периодонтит</button>
          <button class="condition" data-condition="periodontitis_gum">Пародонтит</button>
          <button class="condition" data-condition="root">Корен</button>
          <button class="condition" data-condition="obturate">Обтурация</button>
          <button class="condition" data-condition="implant">Имплант</button>
          <button class="condition" data-condition="fracture">Фрактура</button>
          <button class="condition" data-condition="missing">Изваден</button>
          <button class="condition" data-condition="crown">Коронка</button>
        </div>
        
        <button class="condition" data-condition="clear">Изчисти</button>
        <button id="save-chart" class="save-btn">Запази картата</button>
      </section>
      <!-- END Tooth Chart -->

      <!-- Visitations -->
      <section class="Visitations-Section">
        <div class="visit-header">
          <h2>Посещения</h2>
          <button id="add-visit" class="add-btn"> + Ново посещение</button>
        </div>

        <div id="visit-form-container" class="hidden">
          <form id="visit-form-horizontal" class="visit-form-horizontal">
            <input type="date" name="visit_date" value="<?= date('Y-m-d') ?>" required>
            <input type="text" name="tooth_number" placeholder="Номер на зъб /и" required>
            <input type="text" name="diagnosis" placeholder="Диагноза" required>
            <input type="text" name="treatment" placeholder="Лечение">
            <input type="text" name="procedure_used" placeholder="Процедура">
            <input type="text" name="notes" placeholder="Забележки">

            <button type="submit">Добави</button>
            <button type="button" id="cancel-visit-btn">Отказ</button>
          </form>
        </div>

        <table class="visitation-table">
          <thead>
            <tr>
              <th>Дата</th>
              <th>Зъб</th>
              <th>Диагноза</th>
              <th>Лечение</th>
              <th>Процедура</th>
              <th>Забележки</th>
            </tr>
          </thead>
          <tbody id="visits-body">
            <?php
            $vstmt = $conn->prepare("SELECT * FROM visitations WHERE patient_id = ? ORDER BY visit_date DESC, id DESC");
            $vstmt->bind_param("i", $patient_id);
            $vstmt->execute();
            $vresult = $vstmt->get_result();
            while ($v = $vresult->fetch_assoc()):
            ?>
              <tr>
                <td><?= htmlspecialchars($v['visit_date']) ?></td>
                <td><?= htmlspecialchars($v['tooth_number']) ?></td>
                <td><?= htmlspecialchars($v['diagnosis']) ?></td>
                <td><?= htmlspecialchars($v['treatment']) ?></td>
                <td><?= htmlspecialchars($v['procedure_used']) ?></td>
                <td><?= htmlspecialchars($v['notes']) ?></td>
              </tr>
            <?php endwhile; $vstmt->close(); ?>
          </tbody>
        </table>
      </section><!-- END Visitations -->
    </div> <!-- END PatientChart -->

    <!-- Photos Tab -->
    <!-- Naming the file must be with lowercase ie.: id_firstname_lastname_-->
    <div id="Photos" class="Tab-Content">
      <div id="photo-gallery" class="photo-gallery">
        <?php
        $photoDir = "patient_photos/";
        $patientName = preg_replace('/\s+/', '_', strtolower($patient['first_name'] . '_' . $patient['last_name']));
        $patientId   = $patient_id;
        $pattern = $photoDir . $patientId . "_{$patientName}_*.{jpg,jpeg,png,gif}";
        $images = glob($pattern, GLOB_BRACE);

        if (!empty($images)) {
            foreach ($images as $img) {
                $imgPath = htmlspecialchars($img);
                echo "<img src='$imgPath' alt='Patient Photo' class='photo-thumb'>";
            }
        } else {
            echo "<p>Няма качени снимки за този пациент.</p>";
        }
        ?>
      </div>
    </div> <!-- END Photos -->
  </section> <!-- END Tab-container -->
</body>
</html>

<script src="JSScripts/TabSwitcher.js"></script>
<script src="JSScripts/ToothChart.js"></script>
<script src="JSScripts/Visitations.js"></script>
<script src="JSScripts/ThemeSwitcher.js"></script>
