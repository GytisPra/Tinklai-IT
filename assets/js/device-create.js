document.addEventListener("DOMContentLoaded", () => {
  // Get references to the dropdowns
  const baseSelect = document.getElementById("base_options");
  const baseSpecification = document.getElementById("base_specification");

  // Function to disable/enable dependent selects
  function updateSelects() {
    const isBaseSelected = baseSelect.value !== "0"; // Check if a base is selected

    if (isBaseSelected) {
      baseSpecification.classList.remove("d-none");
    } else {
      baseSpecification.classList.add("d-none");
    }
  }

  // Disable dependent selects initially
  updateSelects();
  populateBaseSelection();

  // Add event listeners to category and base dropdowns
  baseSelect.addEventListener("change", updateSelects);
  baseSelect.addEventListener("change", async () => {
    const baseId = baseSelect.value;

    if (baseId !== "0") {
      try {
        // Fetch data from the backend
        const response = await fetch(`/get-base?base_id=${baseId}`);
        if (!response.ok) throw new Error("Failed to fetch base data");

        const baseData = await response.json();

        // Populate form fields with fetched data
        populateFields(baseData);
      } catch (error) {
        console.error("Error fetching base data:", error);
      }
    }
  });

  async function populateBaseSelection() {
    try {
      // Fetch data from the backend
      const response = await fetch("/get-base-list");
      if (!response.ok) throw new Error("Failed to fetch base data");

      const basesList = await response.json();
      // Populate form fields with fetched data
      const baseSelect = document.getElementById("base_options");
      baseSelect.innerHTML = ""; // Clear previous options

      const defaultOption = document.createElement("option");
      defaultOption.value = "0";
      defaultOption.textContent = "Pasirinkite bazę";
      defaultOption.selected = true;
      defaultOption.classList = "text-secondary";
      baseSelect.appendChild(defaultOption);

      if (basesList) {
        basesList.forEach((base) => {
          const option = document.createElement("option");
          option.value = base.base_id;
          option.textContent = base.base_name;
          baseSelect.appendChild(option);
        });
      }
    } catch (error) {
      baseSelect.innerHTML = ""; // Clear previous options
      baseSelect.appendChild(defaultOption);
      console.error("Error fetching base data:", error);
    }
  }

  // Function to populate fields with fetched data
  function populateFields(base) {
    // Example: Update the processor dropdown
    const processorSelect = document.getElementById("processor_options");
    const motherboardSelect = document.getElementById("motherboard_options");
    const memorySelect = document.getElementById("memory_options");
    const storageSelect = document.getElementById("storage_options");
    const screenSelect = document.getElementById("screen_options");
    const graphicsCardSelect = document.getElementById("graphics_card_options");
    const coolingSelect = document.getElementById("cooling_options");
    const operatingSystemSelect = document.getElementById(
      "operating_system_options"
    );

    // Clear previous options
    processorSelect.innerHTML =
      "<option class='text-secondary'>Pasirinkite procesorių</option>";

    motherboardSelect.innerHTML =
      "<option class='text-secondary'>Pasirinkite motininę plokštę</option>";

    memorySelect.innerHTML =
      "<option class='text-secondary'>Pasirinkite atmintį</option>";

    storageSelect.innerHTML =
      "<option class='text-secondary'>Pasirinkite talpą</option>";

    screenSelect.innerHTML =
      "<option class='text-secondary'>Pasirinkite ekraną</option>";

    graphicsCardSelect.innerHTML =
      "<option class='text-secondary'>Pasirinkite vaizdo plokštę</option>";

    coolingSelect.innerHTML =
      "<option class='text-secondary'>Pasirinkite aušinimą</option>";

    operatingSystemSelect.innerHTML =
      "<option class='text-secondary'>Pasirinkite operacinę sistemą</option>";

    if (base) {
      const processorOption = document.createElement("option");
      processorOption.value = base.base_id;
      processorOption.textContent = base.processor;
      processorOption.selected = true;
      processorSelect.appendChild(processorOption);

      const motherboardOption = document.createElement("option");
      motherboardOption.value = base.base_id;
      motherboardOption.textContent = base.motherboard;
      motherboardOption.selected = true;
      motherboardSelect.appendChild(motherboardOption);

      const memoryOption = document.createElement("option");
      memoryOption.value = base.base_id;
      memoryOption.textContent = base.memory;
      memoryOption.selected = true;
      memorySelect.appendChild(memoryOption);

      const storageOption = document.createElement("option");
      storageOption.value = base.base_id;
      storageOption.textContent = base.storage;
      storageOption.selected = true;
      storageSelect.appendChild(storageOption);

      const screenOption = document.createElement("option");
      screenOption.value = base.base_id;
      screenOption.textContent = base.screen;
      screenOption.selected = true;
      screenSelect.appendChild(screenOption);

      const graphicsCardOption = document.createElement("option");
      graphicsCardOption.value = base.base_id;
      graphicsCardOption.textContent = base.graphics_card;
      graphicsCardOption.selected = true;
      graphicsCardSelect.appendChild(graphicsCardOption);

      const coolingOption = document.createElement("option");
      coolingOption.value = base.base_id;
      coolingOption.textContent = base.cooling_option;
      coolingOption.selected = true;
      coolingSelect.appendChild(coolingOption);

      const operatingSystemOption = document.createElement("option");
      operatingSystemOption.value = base.base_id;
      operatingSystemOption.textContent = base.operating_system;
      operatingSystemOption.selected = true;
      operatingSystemSelect.appendChild(operatingSystemOption);
    }
  }
});
