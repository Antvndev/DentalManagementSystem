document.addEventListener("DOMContentLoaded", () => {
  const addBtn = document.getElementById("add-visit");
  const formContainer = document.getElementById("visit-form-container");
  const form = document.getElementById("visit-form-horizontal");
  const cancelBtn = document.getElementById("cancel-visit-btn");
  const patientId = document.body.dataset.patient;

  // Show the form
  addBtn.addEventListener("click", () => {
    formContainer.classList.remove("hidden");
  });

  // Cancel hides form
  cancelBtn.addEventListener("click", () => {
    form.reset();
    formContainer.classList.add("hidden");
  });

  // Submit form via AJAX
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = Object.fromEntries(new FormData(form).entries());
    formData.patient_id = patientId;

    try {
      const res = await fetch("save_visitation.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData)
      });
    const data = await res.json();
    if (data.success) {
    alert("Посещението е добавено успешно!");
    form.reset();
    formContainer.classList.add("hidden");
    location.reload();
    } else {
    alert("Грешка: " + (data.error || "Неизвестна грешка"));
    }
    } catch (err) {
      console.error(err);
      alert("Грешка при записването на посещението");
    }
  });
});
