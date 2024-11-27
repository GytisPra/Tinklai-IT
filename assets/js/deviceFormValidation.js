document.addEventListener("DOMContentLoaded", async () => {
  const form = document.getElementById("deviceCreationForm");

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    // Reset previous error messages
    clearErrors();

    // Validate name
    const nameInput = document.getElementById("name");
    if (!nameInput.value.trim()) {
      displayError("Įveskite pavadinimą");
      return;
    }

    // Validate parts
    const requiredParts = [
      {
        id: "motherboard_options",
        message: "Motininė plokšte turi būti pasirinkta.",
      },
      { id: "memory_options", message: "Atmintis turi būti pasirinkta." },
      { id: "storage_options", message: "Talpa turi būti pasirinkta." },
      {
        id: "processor_options",
        message: "Procesorius turi būti pasirinktas.",
      },
      {
        id: "graphics_card_options",
        message: "Vaizdo plokštė turi būti pasirinkta.",
      },
      { id: "cooling_options", message: "Aušinimas turi būti pasirinktas." },
      { id: "screen_options", message: "Ekranas turi būti pasirinktas." },
      { id: "os_options", message: "Operacinė sistema turi būti pasirinkta." },
      { id: "computer_type_select", message: "Pasirinkite kompiuterio tipą" },
    ];

    errorMesseges = [];
    i = 0;

    // Validate each required part
    for (let part of requiredParts) {
      const partSelect = document.getElementById(part.id);

      if (!partSelect || !partSelect.value || partSelect.value === "0") {
        errorMesseges[i] = part.message;
        i++;
      }
    }

    if (errorMesseges.length != 0) {
      displayErrors(errorMesseges);
      return;
    }

    // If we've made it this far, do an AJAX check for part availability
    checkPartAvailability();
  });

  function checkPartAvailability() {
    // Collect part IDs
    const partIds = [
      "motherboard_id",
      "memory_id",
      "storage_id",
      "processor_id",
      "graphics_card_id",
      "cooling_id",
      "screen_id",
      "os_id",
    ];

    const unavailabilityMessages = {
      motherboard_id: "Motininės plokšte nėra sandelyje.",
      memory_id: "Atminties nėra sandelyje.",
      storage_id: "Talpos nėra sandelyje.",
      processor_id: "Procesoriaus nėra sandelyje.",
      graphics_card_id: "Vaizdo plokštės nėra sandelyje.",
      cooling_id: "Pasirinkto aušinimo nėra sandelyje.",
      screen_id: "Ekrano nėra sandelyjes.",
      os_id: "Operacinė sistemos nebeturime.",
    };

    // Prepare data to send
    const formData = new FormData(form);
    const partChecks = partIds.map((partId) => {
      const partValue = formData.get(partId);
      return { partId, partValue };
    });

    // Send AJAX request to check part availability
    fetch("/check-parts-availability", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest", // Add this line
      },
      body: JSON.stringify(partChecks),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Klaida tikrinant dalių prieinamumą");
        }
        return response.json();
      })
      .then((availabilityData) => {
        // Check if any parts are unavailable
        const unavailableParts = availabilityData.unavailableParts;

        if (unavailableParts && unavailableParts.length > 0) {
          // Display first unavailable part error
          const firstUnavailablePart = unavailableParts[0];
          displayError(unavailabilityMessages[firstUnavailablePart]);
          return;
        }

        submitForm();
      })
      .catch((error) => {
        console.log(error.message);
        displayError(error.message);
      });
  }

  function submitForm() {
    fetch("/create-device/submit", {
      method: "POST",
      body: new FormData(form),
      headers: {
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then(async (response) => {
        // Check if response is JSON
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
          throw new TypeError("Received non-JSON response");
        }

        // Check HTTP status
        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || "Klaida kuriant įrenginį");
        }

        return response.json();
      })
      .then((data) => {
        if (data.success) {
          clearErrors();
          showSuccess(data.message || "Įrenginys sėkmingai sukurtas");
        } else {
          displayError(data.message || "Klaida kuriant įrenginį");
        }
      })
      .catch((error) => {
        console.error("Submission error:", error);
        displayError(error.message || "Netikėta klaida");
      });
  }

  function displayError(message) {
    // Create error container if it doesn't exist
    let errorContainer = document.getElementById("errorContainer");
    if (!errorContainer) {
      errorContainer = document.createElement("div");
      errorContainer.id = "errorContainer";
      errorContainer.className = "alert alert-danger mt-3";
      form.insertBefore(errorContainer, form.firstChild);
    }

    clearSuccess();

    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });

    const errorItem = document.createElement("li");
    errorItem.textContent = message;
    errorContainer.appendChild(errorItem);

    // Set error message
    errorContainer.appendChild(errorItem);
    errorContainer.style.display = "block";
  }

  function displayErrors(messages) {
    let errorContainer = document.getElementById("errorContainer");

    // Create error container if it doesn't exist
    if (!errorContainer) {
      errorContainer = document.createElement("div");
      errorContainer.id = "errorContainer";
      errorContainer.className = "alert alert-danger mt-3";
      form.insertBefore(errorContainer, form.firstChild);
    }

    messages.forEach((message) => {
      const errorItem = document.createElement("li");
      errorItem.textContent = message;
      errorContainer.appendChild(errorItem);
    });

    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
    errorContainer.style.display = "block";
  }

  function clearErrors() {
    const errorContainer = document.getElementById("errorContainer");
    if (errorContainer) {
      errorContainer.textContent = "";
      errorContainer.style.display = "none";
    }
  }

  function clearSuccess() {
    const successContainer = document.getElementById("successContainer");
    if (successContainer) {
      successContainer.style.display = "none";
    }
  }

  function showSuccess(message) {
    let successContainer = document.getElementById("successContainer");
    let linkToDashBoard = document.getElementById("LinkToDashBoard");

    // Create container if it doesn't exist
    if (!successContainer) {
      successContainer = document.createElement("div");
      successContainer.id = "successContainer";
      successContainer.className =
        "alert alert-success mt-3 me-4 d-flex justify-content-between";
    }

    // Clear previous content and set message
    successContainer.innerHTML = "";

    // Create a span for the message
    let messageSpan = document.createElement("span");
    messageSpan.textContent = message;
    successContainer.appendChild(messageSpan);

    // Ensure dashboard link exists
    if (!linkToDashBoard) {
      linkToDashBoard = document.createElement("a");
      linkToDashBoard.id = "LinkToDashBoard";
      linkToDashBoard.textContent = "Grįžti į pagrindinį langą";
      linkToDashBoard.className = "ms-5";
      linkToDashBoard.href = "/dashboard";
    }

    // Append the link to the container
    successContainer.appendChild(linkToDashBoard);

    // Insert container if not already in document
    if (!successContainer.parentNode) {
      form.insertBefore(successContainer, form.firstChild);
    }

    // Show the success container
    successContainer.style.display = "flex";

    // Scroll to top
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
});
