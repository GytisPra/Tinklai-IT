document.addEventListener("DOMContentLoaded", async () => {
  const loadingOverlay = document.getElementById("loadingOverlay");

  async function getAllPartList() {
    try {
      const response = await fetch(`/get-all-parts`);
      if (!response.ok) throw new Error("Failed to fetch parts data");

      return await response.json();
    } catch (error) {
      console.error("Error fetching parts data:", error);
    }
  }

  async function loadParts(selectConfig) {
    const allParts = await getAllPartList();
    const partList = document.getElementById("partsList");

    selectConfig.forEach(async (config) => {
      const listItem = document.createElement("li");
      listItem.className =
        "list-group-item text-white d-flex justify-content-between align-items-center";

      // Initialize the innerHTML for the listItem
      let innerHTMLContent = `<div class="ms-2 me-auto d-flex flex-column" id="${config.partType}"> 
        <div class="fw-bold">${config.nameLt}</div>`;

      // Ensure the partType exists in allParts before trying to access it

      if (allParts[config.partType]) {
        allParts[config.partType].forEach((part) => {
          const selectedIds =
            deviceData && deviceData[`${config.partType}_ids`]
              ? deviceData[`${config.partType}_ids`]
                  .split(",")
                  .map((id) => id.trim()) // Convert to array and trim any extra spaces
              : [];

          const isChecked = selectedIds.some((id) => id === part.id.toString()) // Check if the part.id is in the selectedIds array
            ? "checked"
            : "";

          innerHTMLContent += `<div class="form-check">
            <input name="${
              config.partType
            }_id" class="form-check-input bg-dark" id="${
            part.id
          }" type="checkbox" value="${part.id}" data-storage="${
            part.left_in_storage
          }" ${isChecked ? "checked" : ""}/>
            <label id="${part.id}_label" class="form-check-label" for="${
            part.id
          }">${part.name}</label>
          </div>`;
        });
      }

      //       <div class="form-check">
      //   <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
      //   <label class="form-check-label" for="flexCheckDefault">
      //     Default checkbox
      //   </label>
      // </div>

      // Set the listItem's innerHTML after all the content has been prepared
      listItem.innerHTML = innerHTMLContent;

      partList.appendChild(listItem);
    });
  }

  function updateStorageBadge(selectedOption, storageBadge) {
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
  }

  const selectConfig = [
    {
      partType: "processor",
      nameLt: "Procesorius",
      selectWhat: "procesorių",
    },
    {
      partType: "motherboard",
      nameLt: "Motininė plokštė",
      selectWhat: "motininę plokštę",
    },
    {
      partType: "memory",
      nameLt: "Atmintis",
      selectWhat: "atmintį",
    },
    {
      partType: "storage",
      nameLt: "Talpa",
      selectWhat: "talpą",
    },
    {
      partType: "graphics_card",
      nameLt: "Vaizdo plokštė",
      selectWhat: "vaizdo plokštę",
    },
    {
      partType: "screen",
      nameLt: "Ekranas",
      selectWhat: "ekraną",
    },
    {
      partType: "cooling",
      nameLt: "Aušinimas",
      selectWhat: "vaizdo plokštę",
    },
    {
      partType: "os",
      nameLt: "Operacinė sistema",
      selectWhat: "operacinę sistemą",
    },
  ];

  loadingOverlay.style.display = "flex";
  await Promise.all([loadParts(selectConfig)]);
  loadingOverlay.style.display = "none";
});
