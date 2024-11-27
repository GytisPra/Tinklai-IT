document.addEventListener("DOMContentLoaded", async () => {
  const loadingOverlay = document.getElementById("loadingOverlay");

  const selectConfig = [
    {
      id: "processor_options",
      jsonName: "processor",
    },
    {
      id: "motherboard_options",
      jsonName: "motherboard",
    },
    {
      id: "memory_options",
      jsonName: "memory",
    },
    {
      id: "storage_options",
      jsonName: "storage",
    },
    {
      id: "screen_options",
      jsonName: "screen",
    },
    {
      id: "graphics_card_options",
      jsonName: "graphics_card",
    },
    {
      id: "cooling_options",
      jsonName: "cooling_option",
    },
    {
      id: "os_options",
      jsonName: "operating_system",
    },
  ];

  // Function to fetch parts
  async function getBaseList() {
    try {
      const response = await fetch(`/get-base-list`);
      if (!response.ok) {
        // More informative error handling
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return await response.json();
    } catch (error) {
      console.error("Error fetching parts data:", error);
      return [];
    }
  }

  async function getPartData(partId) {
    try {
      const response = await fetch(`/get-part?part_id=${partId}`);
      if (!response.ok) {
        throw new Error("Failed to fetch base list");
      }

      return await response.json();
    } catch (error) {
      console.error("Error fetching parts data:", error);
    }
  }

  async function PopulateBaseSelection() {
    const baseSelect = document.getElementById("baseOptions");

    const baseList = await getBaseList();

    baseList.forEach((baseData) => {
      const option = document.createElement("option");
      option.textContent = baseData.base_name;
      option.value = baseData.base_id;
      baseSelect.appendChild(option);
    });

    attachBaseSelectionListener();
  }

  function attachBaseSelectionListener() {
    const baseSelect = document.getElementById("baseOptions");

    baseSelect.addEventListener("change", async (event) => {
      if (event.target.value === "0") {
        loadingOverlay.style.display = "none";
        loadDefaultSelection();
      } else {
        try {
          loadingOverlay.style.display = "flex";

          const response = await fetch(
            `/get-base?base_id=${event.target.value}`
          );
          if (!response.ok) {
            throw new Error("Failed to fetch base data");
          }

          const baseData = await response.json();
          await populateSlects(baseData);
          loadingOverlay.style.display = "none";
        } catch (error) {
          if (event.target.value === 0) {
            loadingOverlay.style.display = "none";
            loadDefaultSelection();
          } else {
            console.error("Base selection error:", error);
          }
        }
      }
    });
  }

  async function populateSlects(baseData) {
    // Use Promise.all to fetch part data concurrently
    loadingOverlay.style.display = "flex";
    const partPromises = selectConfig.map(async (config) => {
      const partSelect = document.getElementById(config.id);
      const partId = baseData[config.jsonName];

      try {
        const partData = await getPartData(partId);

        // Clear previous options
        partSelect.innerHTML = "";

        const partOption = new Option(partData.name, partData.id, true, true);
        partSelect.add(partOption);

        const storageBadge = document.getElementById(
          `${partData.part_type}_storage`
        );
        updateStorageBadge(storageBadge, partData.left_in_storage);
      } catch (error) {
        console.error(`Error populating ${config.jsonName}:`, error);
      }
    });

    await Promise.all(partPromises);
    loadingOverlay.style.display = "none";
  }

  function updateStorageBadge(badgeElement, storageCount) {
    badgeElement.textContent = storageCount;

    // Use a mapping to simplify badge color logic
    const colorMap = {
      danger: storageCount < 5,
      warning: storageCount <= 10,
      success: storageCount > 10,
    };

    // Remove all color classes first
    badgeElement.classList.remove(
      "text-bg-primary",
      "text-bg-success",
      "text-bg-warning",
      "text-bg-danger"
    );

    // Add appropriate color class
    Object.entries(colorMap).forEach(([color, condition]) => {
      if (condition) {
        badgeElement.classList.add(`text-bg-${color}`);
      }
    });
  }

  // Function to populate fields with fetched data
  async function loadPartSelect(partType, publicName, selectWhat) {
    const partList = document.getElementById("partsList");

    try {
      const listItem = document.createElement("li");
      listItem.className =
        "list-group-item text-white d-flex justify-content-between align-items-center";

      listItem.innerHTML = `
          <div class="ms-2 me-auto">
            <div class="fw-bold">${publicName}</div>
            <select class="form-select bg-dark text-white no-border" name="${partType}_id" id="${partType}_options" aria-label="${partType} selection"> 
              <option value="0" class="text-secondary" selected>Pasirinkite ${selectWhat} </option>
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

  async function loadDefaultSelection() {
    const patsList = document.getElementById("partsList");
    patsList.innerHTML = "";

    loadingOverlay.style.display = "flex";
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
    loadingOverlay.style.display = "none";
  }

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
    PopulateBaseSelection(),
  ]);

  // Hide loading overlay
  loadingOverlay.style.display = "none";
});
