document.addEventListener("DOMContentLoaded", () => {
  async function getPartList(partType) {
    if (partType) {
      try {
        const response = await fetch(`/get-parts?part_type=${partType}`);
        if (!response.ok) throw new Error("Failed to fetch parts data");

        const partsData = await response.json();

        return partsData;
      } catch (error) {
        console.error("Error fetching parts data:", error);
      }
    }
  }

  // Function to populate fields with fetched data
  async function loadPartSelect(
    partType,
    publicName,
    selectWhat,
    includeAmout
  ) {
    const partList = document.getElementById("partsList"); // Reference to the <ol>

    try {
      const parts = await getPartList(partType); // Fetch parts data from the database

      console.log(parts);

      // Create <li> element
      const listItem = document.createElement("li");
      listItem.className =
        "list-group-item text-white d-flex justify-content-between align-items-center";

      // Create content inside the <li>
      listItem.innerHTML = `
          <div class="ms-2 me-auto">
            <div class="fw-bold">${publicName}</div>
            <select class="form-select bg-dark text-white no-border" name="${partType}_id" id="${partType}_options" aria-label="${partType} selection"> 
            <option class="text-secondary" selected>Pasirinkite ${selectWhat}</option>
            ${parts
              .map((part) => `<option value="${part.id}">${part.name}</option>`)
              .join("")}
            </select>
          </div>
          ${
            includeAmout
              ? '<div class="ms-2 d-flex flex-column">' +
                'Likutis <span class="badge text-bg-primary rounded-pill">14</span></div>'
              : ""
          }
          
        `;

      // Append the <li> to the <ol>
      partList.appendChild(listItem);
    } catch (error) {
      console.error("Failed to load parts:", error);
    }
  }

  loadPartSelect("processor", "Procesorius", "procesorių", true);
  loadPartSelect("motherboard", "Motininė plokštė", "motininę plokštę", true);
  loadPartSelect("memory", "Atminits", "atmintį", true);
  loadPartSelect("storage", "Talpa", "talpą", true);
  loadPartSelect("screen", "Ekranas", "ekraną", false);
  loadPartSelect("graphics_card", "Vaizdo plokštė", "vaizdo plokštę", true);
  loadPartSelect("cooling", "Aušinimas", "aušinimo tipą", false);
  loadPartSelect("os", "Operacinė sistema", "operacinė sistemą", false);
});
