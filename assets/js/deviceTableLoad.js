document.addEventListener("DOMContentLoaded", async () => {
  const sortSelect = document.getElementById("filter-options");
  const searchInput = document.getElementById("search");
  const loadingOverlay = document.getElementById("loadingOverlay");

  // Show and hide loading overlay functions
  function showLoading() {
    loadingOverlay.style.visibility = "visible";
  }

  function hideLoading() {
    loadingOverlay.style.visibility = "hidden";
  }

  // Flag to track initial data load
  let isInitialLoad = true;

  // Show the loading overlay initially
  showLoading();

  async function getDevices(sortNum) {
    try {
      const response = await fetch(`/get-all-devices?sort_order=${sortNum}`);
      if (!response.ok) throw new Error("Failed to fetch parts data");

      return await response.json();
    } catch (error) {
      console.error("Error fetching parts data:", error);
    }
  }

  let sortOrder = 1;
  let cachedDevices = []; // Store fetched devices to avoid repeated network calls

  sortSelect.addEventListener("change", (event) => {
    sortOrder = event.target.value;

    if (sortOrder > 0) {
      fillTable([], sortOrder);
    }
  });

  async function fillTable(devices, sortOrder, searchQuery = "") {
    const tableBody = document.getElementById("device_table_body");
    tableBody.innerHTML = "";

    // Only show loading overlay during initial data fetch
    if (isInitialLoad) {
      showLoading();
    }

    // If no devices passed, fetch them
    if (devices.length === 0) {
      devices = await getDevices(sortOrder);
      cachedDevices = devices; // Cache the fetched devices
      isInitialLoad = false; // Mark initial load as complete
      hideLoading(); // Hide loading overlay after initial fetch
    }

    if (!devices) {
      const tableRow = document.createElement("tr");
      const noDevicesCell = document.createElement("td");
      noDevicesCell.setAttribute("colspan", "3");
      noDevicesCell.textContent = "Įrenginių nerasta";

      tableRow.appendChild(noDevicesCell);
      tableBody.appendChild(tableRow);
      return;
    }

    // Filter devices by search query (case-insensitive match)
    const filteredDevices = devices.filter((device) =>
      device.device_name.toLowerCase().includes(searchQuery.toLowerCase())
    );

    if (filteredDevices.length === 0) {
      const tableRow = document.createElement("tr");
      const noDevicesCell = document.createElement("td");
      noDevicesCell.setAttribute("colspan", "3");
      noDevicesCell.textContent = "Nerasta įrenginių.";

      tableRow.appendChild(noDevicesCell);
      tableBody.appendChild(tableRow);
      return;
    }

    filteredDevices.forEach((device) => {
      // Create a new table row element
      const tableRow = document.createElement("tr");

      // Create a cell for the device name
      const deviceNameCell = document.createElement("td");
      deviceNameCell.textContent = device.device_name;
      deviceNameCell.className = "text-white";

      // Create a cell for the device cost
      const deviceCostCell = document.createElement("td");
      deviceCostCell.textContent = device.device_cost + "€";
      deviceCostCell.className = "text-white";

      tableRow.appendChild(deviceNameCell);
      tableRow.appendChild(deviceCostCell);
      // Create a cell for actions

      if (userRole != "Vadybininkas" && userRole != "Technikas") {
        const deviceActionsCell = document.createElement("td");
        const deviceActionsDiv = document.createElement("div");
        const deviceAction = document.createElement("a");
        deviceAction.textContent = "Komplektuoti";
        deviceAction.className = "btn btn-sm btn-primary edit-base-btn";
        deviceAction.href = `/assemble-device?device_id=${device.device_id}`;

        deviceActionsDiv.className = "btn-group";
        deviceActionsDiv.setAttribute("role", "group");
        deviceActionsDiv.appendChild(deviceAction);
        deviceActionsCell.appendChild(deviceActionsDiv);

        tableRow.appendChild(deviceActionsCell);
      }

      // Append the name, cost, and action cells to the table row

      // Append the complete table row to the table body
      tableBody.appendChild(tableRow);
    });
  }

  // Fill the table initially with the default sort order
  fillTable([], sortOrder);

  // Listen for changes in the search input
  searchInput.addEventListener("input", (event) => {
    const searchQuery = event.target.value;
    fillTable(cachedDevices, sortOrder, searchQuery);
  });
});
