document.addEventListener("DOMContentLoaded", async () => {
  const form = document.getElementById("deviceForm");

  const requiredParts = [
    {
      id: "motherboard",
      message: "Motininė plokšte turi būti pasirinkta.",
    },
    { id: "memory", message: "Atmintis turi būti pasirinkta." },
    { id: "storage", message: "Talpa turi būti pasirinkta." },
    {
      id: "processor",
      message: "Procesorius turi būti pasirinktas.",
    },
    {
      id: "graphics_card",
      message: "Vaizdo plokštė turi būti pasirinkta.",
    },
    { id: "cooling", message: "Aušinimas turi būti pasirinktas." },
    { id: "screen", message: "Ekranas turi būti pasirinktas." },
    { id: "os", message: "Operacinė sistema turi būti pasirinkta." },
  ];

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

    const costInput = document.getElementById("price");
    if (!costInput.value.trim()) {
      displayError("Įveskite kainą");
      return;
    } else {
      const value = parseInt(costInput.value);

      if (isNaN(value)) {
        displayError("Kaina turi būti skaičius");
        return;
      } else if (value <= 0) {
        displayError("Kaina turi teigiamas skaičius");
        return;
      }
    }

    // Validate parts

    let errorMessages = [];

    // Validate each required part
    for (let part of requiredParts) {
      // Select all checkboxes within the specified div by its id (e.g., #motherboard)
      let checkboxes = document.querySelectorAll(
        `#${part.id} .form-check input[type="checkbox"]`
      );

      // Check if any checkbox is checked
      const isChecked = Array.from(checkboxes).some(
        (checkbox) => checkbox.checked
      );

      // If none of the checkboxes are selected, add an error message
      if (!isChecked) {
        errorMessages.push(part.message);
      }
    }

    if (errorMessages.length > 0) {
      displayErrors(errorMessages);
      return;
    }

    submitForm();
  });

  function submitForm() {
    const formData = new FormData(form);
    const selectedParts = [];

    for (let part of requiredParts) {
      let checkboxes = document.querySelectorAll(
        `#${part.id} .form-check input[type="checkbox"]`
      );
      checkboxes.forEach((checkbox) => {
        if (checkbox.checked) {
          selectedParts.push(checkbox.value);
        }
      });
    }

    selectedParts.forEach((partId) => {
      formData.append("selectedParts[]", partId);
    });

    // Determine the endpoint based on whether we are editing or creating
    const endpoint =
      deviceData && deviceData.device_id
        ? "/edit-device/update"
        : "/create-device/submit";

    if (deviceData && deviceData.device_id) {
      formData.append("device_id", deviceData.device_id);
    }

    fetch(endpoint, {
      method: "POST",
      body: formData,
      headers: {
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then(async (response) => {
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
          throw new TypeError("Received non-JSON response");
        }
        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || "Klaida tvarkant įrenginį");
        }
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          clearErrors();
          showSuccess(
            data.message ||
              (deviceData && deviceData.device_id
                ? "Įrenginys sėkmingai atnaujintas"
                : "Įrenginys sėkmingai sukurtas")
          );
          // form.reset();
        } else {
          displayError(data.message || "Klaida tvarkant įrenginį");
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
    let linkToDevices = document.getElementById("linkToDevices");

    // Create container if it doesn't exist
    if (!successContainer) {
      successContainer = document.createElement("div");
      successContainer.id = "successContainer";
      successContainer.className =
        "alert alert-success mt-3 justify-content-between";
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

    if (!linkToDevices) {
      linkToDevices = document.createElement("a");
      linkToDevices.id = "linkToDevices";
      linkToDevices.textContent = "Eiti į mano įrenginius";
      linkToDevices.className = "ms-5";
      linkToDevices.href = "/my-devices";
    }

    // Append the link to the container
    successContainer.appendChild(linkToDashBoard);
    successContainer.appendChild(linkToDevices);

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
