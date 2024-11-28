document.addEventListener("DOMContentLoaded", async () => {
  const loadingOverlay = document.getElementById("loadingOverlay");
  let totalPrice = deviceData["device_cost"];

  async function updatePriceBasedOnAssemblyData() {
    if (assemblyData) {
      const radios = document.querySelectorAll('input[type="radio"]');

      totalPrice = deviceData["device_cost"];

      // Use Promise.all to handle multiple async operations
      const pricePromises = Array.from(radios)
        .filter((radio) => radio.checked)
        .map(async (radio) => {
          const partId = radio.value;
          console.log(partId, "IS checked");
          const priceData = await getPartPrice(partId);
          return parseFloat(priceData.price);
        });

      // Wait for all price fetching to complete and sum the prices
      const prices = await Promise.all(pricePromises);
      totalPrice += prices.reduce((sum, price) => sum + price, 0);

      console.log("Total Price:", totalPrice);
      updatePrice(); // Update the price display
    }
  }

  const previousSelections = {};

  // Fetch device data
  async function getDeviceData() {
    try {
      const response = await fetch(
        `/get-device?device_id=${deviceData["device_id"]}`
      );
      if (!response.ok) throw new Error("Failed to fetch parts data");

      return await response.json();
    } catch (error) {
      console.error("Error fetching parts data:", error);
    }
  }

  // Fetch part price
  async function getPartPrice(partId) {
    try {
      const response = await fetch(`/get-part-price?part_id=${partId}`);
      if (!response.ok) throw new Error("Failed to fetch parts data");

      return await response.json();
    } catch (error) {
      console.error("Error fetching parts data:", error);
    }
  }

  // Load parts based on configuration
  async function loadParts(selectConfig) {
    const devicesData = await getDeviceData();

    if (devicesData && typeof devicesData === "object") {
      const partList = document.getElementById("partsList");
      const partLoadPromises = selectConfig.map(async (config) => {
        const listItem = document.createElement("li");
        listItem.className =
          "list-group-item text-white d-flex justify-content-between align-items-center";

        let innerHTMLContent = `<div class="ms-2 me-auto d-flex flex-column" id="${config.partType}"> 
            <div class="fw-bold">${config.nameLt}</div>`;

        const partIds = devicesData[`${config.partType}_ids`];
        const partNames = devicesData[`${config.partType}_names`];

        if (partIds && partNames) {
          const ids = partIds.split(",").map((id) => id.trim());
          const names = partNames.split(",").map((name) => name.trim());

          // Create an array to store all part price promises
          const partPricePromises = ids.map(async (id, index) => {
            let isChecked = false;

            if (
              assemblyData &&
              assemblyData[`${config.partType}_id`] === parseInt(id)
            ) {
              isChecked = true;
            }

            const priceData = await getPartPrice(id);
            return {
              id,
              name: names[index],
              price: priceData ? priceData.price : "N/A",
              isChecked,
            };
          });

          // Wait for all part prices to be fetched
          const partDetails = await Promise.all(partPricePromises);

          // Generate HTML for parts
          partDetails.forEach(({ id, name, price, isChecked }) => {
            innerHTMLContent += `<div class="form-check">
              <input ${isChecked ? "checked" : ""} value="${id}" name="${
              config.partType
            }_id" class="form-check-input bg-dark" type="radio" id="${id}">
              <label class="form-check-label" for="${id}">
                  ${name} (${price} €)
              </label>
            </div>`;
          });
        }

        innerHTMLContent += "</div>";
        listItem.innerHTML = innerHTMLContent;
        partList.appendChild(listItem);
      });

      // Wait for all part categories to be processed
      await Promise.all(partLoadPromises);
    } else {
      console.error("devicesData is not a valid object:", devicesData);
    }
  }

  // Update price input field
  function updatePrice() {
    const priceInput = document.getElementById("price");
    console.log("UPDATING PRICE to", totalPrice);

    if (assemblyData) {
      // if assemblyData is provided then check all of the radios and update the price
    }

    priceInput.value = totalPrice.toFixed(2); // Format to 2 decimal places
  }

  // Handle radio button selection/deselection
  function handleRadioChange(event, partPrice, isChecked, partId) {
    const groupName = event.target.name;

    if (isChecked) {
      totalPrice += partPrice;

      if (
        previousSelections[groupName] &&
        previousSelections[groupName] !== partId
      ) {
        const previousPrice = previousSelections[groupName].price;
        totalPrice -= previousPrice;
      }

      previousSelections[groupName] = { partId, price: partPrice };
    } else {
      totalPrice -= partPrice;
    }

    updatePrice();
  }

  // Add event listeners to radio buttons
  function addRadioEventListeners() {
    const radios = document.querySelectorAll('input[type="radio"]');
    radios.forEach((radio) => {
      radio.addEventListener("change", async (event) => {
        const partId = event.target.value;
        const priceData = await getPartPrice(partId);
        const isChecked = event.target.checked;

        handleRadioChange(
          event,
          parseFloat(priceData.price),
          isChecked,
          partId
        );
      });
    });
  }

  const selectConfig = [
    { partType: "processor", nameLt: "Procesorius", selectWhat: "procesorių" },
    {
      partType: "motherboard",
      nameLt: "Motininė plokštė",
      selectWhat: "motininę plokštę",
    },
    { partType: "memory", nameLt: "Atmintis", selectWhat: "atmintį" },
    { partType: "storage", nameLt: "Talpa", selectWhat: "talpą" },
    {
      partType: "graphics_card",
      nameLt: "Vaizdo plokštė",
      selectWhat: "vaizdo plokštę",
    },
    { partType: "screen", nameLt: "Ekranas", selectWhat: "ekraną" },
    { partType: "cooling", nameLt: "Aušinimas", selectWhat: "vaizdo plokštę" },
    {
      partType: "os",
      nameLt: "Operacinė sistema",
      selectWhat: "operacinę sistemą",
    },
  ];

  try {
    loadingOverlay.style.display = "flex";
    await loadParts(selectConfig);
    addRadioEventListeners();
    updatePriceBasedOnAssemblyData();
    updatePrice();
  } catch (error) {
    console.error("Error during initialization:", error);
  } finally {
    loadingOverlay.style.display = "none";
  }
});
