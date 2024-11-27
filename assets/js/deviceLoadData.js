document.addEventListener("DOMContentLoaded", async () => {
  const loadingOverlay = document.getElementById("loadingOverlay");

  // Function to fetch parts
  async function getPartList(partType) {
    if (partType) {
      try {
        const response = await fetch(`/get-parts?part_type=${partType}`);
        if (!response.ok) throw new Error("Failed to fetch parts data");

        return await response.json();
      } catch (error) {
        console.error("Error fetching parts data:", error);
      }
    }
  }

  // Function to populate fields with fetched data
  async function loadPartSelect(partType, publicName, selectWhat) {
    const partList = document.getElementById("partsList");

    try {
      const parts = await getPartList(partType);

      // Create <li> element
      const listItem = document.createElement("li");
      listItem.className =
        "list-group-item text-white d-flex justify-content-between align-items-center";

      // Create the HTML structure
      listItem.innerHTML = `
        <div class="ms-2 me-auto">
          <div class="fw-bold">${publicName}</div>
          <select class="form-select bg-dark text-white no-border" name="${partType}_id" id="${partType}_options" aria-label="${partType} selection"> 
            <option value="0" class="text-secondary" selected>Pasirinkite ${selectWhat} </option>
            ${parts
              .map(
                (part) =>
                  `<option value="${part.id}" data-storage="${part.left_in_storage}">${part.name}</option>`
              )
              .join("")}
          </select>
        </div>
        <div class="ms-2 d-flex flex-column">
          Likutis <span class="badge text-bg-primary rounded-pill" id="${partType}_storage">---</span>
        </div>
      `;

      // Append the <li> to the list
      partList.appendChild(listItem);

      // Add an event listener to the <select>
      const selectElement = listItem.querySelector(`#${partType}_options`);
      const storageBadge = listItem.querySelector(`#${partType}_storage`);

      selectElement.addEventListener("change", (event) => {
        const selectedOption = event.target.selectedOptions[0];
        const storageLeft =
          parseInt(selectedOption.getAttribute("data-storage"), 10) || 0;

        // Update the badge text
        storageBadge.textContent = storageLeft;

        // Update the badge color based on the amount
        storageBadge.classList.remove(
          "text-bg-primary",
          "text-bg-success",
          "text-bg-warning",
          "text-bg-danger"
        );

        if (storageLeft < 5) {
          storageBadge.classList.add("text-bg-danger"); // Red for less than 5
        } else if (storageLeft <= 10) {
          storageBadge.classList.add("text-bg-warning"); // Yellow for 5-10
        } else {
          storageBadge.classList.add("text-bg-success"); // Green for more than 10
        }
      });
    } catch (error) {
      console.error("Failed to load parts:", error);
    }
  }

  // Show loading overlay
  loadingOverlay.style.display = "flex";

  // Load all parts
  await Promise.all([
    loadPartSelect("processor", "Procesorius", "procesorių"),
    loadPartSelect("motherboard", "Motininė plokštė", "motininę plokštę"),
    loadPartSelect("memory", "Atminits", "atmintį"),
    loadPartSelect("storage", "Talpa", "talpą"),
    loadPartSelect("screen", "Ekranas", "ekraną"),
    loadPartSelect("graphics_card", "Vaizdo plokštė", "vaizdo plokštę"),
    loadPartSelect("cooling", "Aušinimas", "aušinimo tipą"),
    loadPartSelect("os", "Operacinė sistema", "operacinė sistemą"),
  ]);

  // Hide loading overlay
  loadingOverlay.style.display = "none";
});
