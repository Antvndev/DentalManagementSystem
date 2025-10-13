document.addEventListener("DOMContentLoaded", () => {
  let selectedTeeth = new Set();

  const conditionButtons = document.querySelectorAll(".condition");
  const teeth = document.querySelectorAll(".tooth");

  // === Tooth selection ===
  teeth.forEach(tooth => {
    tooth.addEventListener("click", () => {
      tooth.classList.toggle("selected");
      const id = tooth.dataset.tooth;
      if (selectedTeeth.has(id)) selectedTeeth.delete(id);
      else selectedTeeth.add(id);
    });

    // === Fixed button inside tooth ===
    const fixedBtn = tooth.querySelector(".fixed-btn");
    if (fixedBtn) {
      fixedBtn.addEventListener("click", e => {
        e.stopPropagation(); // prevent selecting the tooth
        tooth.classList.toggle("fixed");

        if (tooth.classList.contains("fixed")) {
          tooth.removeAttribute("data-condition");
          tooth.title = "Пломба";
        } else {
          tooth.title = "";
        }
      });
    }
  });

  // === Apply condition after selection ===
  conditionButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      const condition = btn.dataset.condition;

      conditionButtons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");

      selectedTeeth.forEach(id => {
        const tooth = document.querySelector(`[data-tooth='${id}']`);
        if (condition === "clear") {
          tooth.removeAttribute("data-condition");
          tooth.title = "";
          tooth.classList.remove("fixed"); // clear fixed too
        } else if (!tooth.classList.contains("fixed")) { // don't overwrite fixed teeth
          tooth.dataset.condition = condition;
          tooth.title = getConditionName(condition);
        }
        tooth.classList.remove("selected");
      });

      selectedTeeth.clear();
    });
  });

  function getConditionName(key) {
    const map = {
      caries: "Кариес",
      crown: "Коронка",
      filling: "Пломба",
      missing: "Изваден зъб",
      implant: "Имплант",
      pulpitis: "Пулпит",
      periodontitis: "Периодонтит",
      periodontitis_gum: "Пародонтит",
      root: "Корен",
      obturate: "Обтурация",
      fracture: "Фрактура",
      clear: "Изчисти"
    };
    return map[key] || "Неизвестно състояние";
  }

  // === Save chart logic ===
  const saveBtn = document.getElementById("save-chart");
  if (!saveBtn) return;

  saveBtn.addEventListener("click", async () => {
    const patientId = document.body.dataset.patient;
    if (!patientId) return alert("Missing patient id.");

    const chartData = [];
    document.querySelectorAll(".tooth").forEach(t => {
      if (t.classList.contains("fixed")) {
        chartData.push({ tooth: t.dataset.tooth, condition: "filling" });
      } else if (t.dataset.condition) {
        chartData.push({ tooth: t.dataset.tooth, condition: t.dataset.condition });
      }
    });

    if (chartData.length === 0) {
      if (!confirm("Няма маркирани състояния. Искате ли да изчистите всички записи за този пациент?")) return;
    }

    try {
      const res = await fetch("/DentalManagementSystem/Save_DentalChart.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ patient_id: patientId, chart: chartData })
      });
      const data = await res.json();
      if (data.success) alert("Картата е запазена успешно.");
      else alert("Грешка при запазване: " + (data.error || "неизвестна грешка"));
    } catch (err) {
      console.error(err);
      alert("Грешка при изпращане към сървъра.");
    }
  });
});
