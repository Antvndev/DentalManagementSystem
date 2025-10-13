// Elements
    const searchInput = document.getElementById('searchInput');
    const patientsGrid = document.getElementById('patientsDisplay');
    const noResults = document.getElementById('noResults');

    // Utility: create a single patient card (DOM-safe)
    function createCard(p) {
      const card = document.createElement('div');
      card.className = 'mini-card';

      const h3 = document.createElement('h3');
      h3.className = 'patient-name';
      const fullName = [p.first_name, p.middle_name, p.last_name].filter(Boolean).join(' ');
      h3.textContent = fullName;
      card.appendChild(h3);

      const infoRow = document.createElement('div');
      infoRow.className = 'info-row';

      const genderSpan = document.createElement('span');
      const genderIcon = document.createElement('i');
      genderIcon.className = (p.gender && p.gender.toLowerCase().startsWith('м')) ? 'bx bx-male-sign' : 'bx bx-female-sign';
      genderSpan.appendChild(genderIcon);
      genderSpan.appendChild(document.createTextNode(' ' + (p.gender || '')));
      infoRow.appendChild(genderSpan);

      const ageSpan = document.createElement('span');
      ageSpan.innerHTML = "<i class='bx bx-calendar-heart'></i> " + (p.age ? (p.age + 'г.') : '');
      infoRow.appendChild(ageSpan);

      card.appendChild(infoRow);

      const phoneDiv = document.createElement('span');
      phoneDiv.className = 'phone';
      const strong = document.createElement('strong');
      strong.textContent = 'Тел: ';
      phoneDiv.appendChild(strong);
      phoneDiv.appendChild(document.createTextNode(p.phone || ''));
      card.appendChild(phoneDiv);

      const detailsBtn = document.createElement('button');
      detailsBtn.textContent = 'Детайли ->';
      detailsBtn.onclick = () => {
        location.href = 'Patient_Details.php?id=' + encodeURIComponent(p.id || '');
      };
      card.appendChild(detailsBtn);

      return card;
    }

    // Render function: replace grid contents
    function renderCards(people) {
      patientsGrid.innerHTML = ''; // clear
      if (!people || people.length === 0) {
        noResults.style.display = 'block';
        return;
      }
      noResults.style.display = 'none';
      const frag = document.createDocumentFragment();
      people.forEach(p => {
        frag.appendChild(createCard(p));
      });
      patientsGrid.appendChild(frag);
    }

    // Fetch from server endpoint
    async function fetchPatients(q = '') {
      try {
        const url = 'patients_search.php?q=' + encodeURIComponent(q);
        const res = await fetch(url, { cache: 'no-store' });
        if (!res.ok) {
          console.error('Fetch error', res.status);
          renderCards([]);
          return;
        }
        const data = await res.json();
        renderCards(data);
      } catch (err) {
        console.error('Error fetching patients', err);
        renderCards([]);
      }
    }

    // Debounce input to limit requests
    let debounceTimer = null;
    searchInput.addEventListener('input', () => {
      const q = searchInput.value.trim();
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => fetchPatients(q), 220);
    });

    // Initial load: fetch all (or limited)
    fetchPatients('');