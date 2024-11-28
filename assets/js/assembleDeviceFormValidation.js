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

    // Validate parts

    let errorMessages = [];

    // Validate each required part
    for (let part of requiredParts) {
      // Select all checkboxes within the specified div by its id (e.g., #motherboard)
      let radios = document.querySelectorAll(
        `#${part.id} .form-check input[type="radio"]`
      );

      const isChecked =
        Array.from(radios).filter((radio) => radio.checked).length === 1;

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

    // Determine the endpoint based on whether we are editing or creating
    const endpoint = assemblyData
      ? "/assembly-edit/update"
      : "/assemble-device/assemble";

    if (
      deviceData &&
      deviceData.device_id &&
      deviceData.device_name &&
      deviceData.device_cost
    ) {
      formData.append("device_id", deviceData.device_id);
      formData.append("device_name", deviceData.device_name);
      formData.append("price", deviceData.device_cost);
    }

    if (assemblyData && assemblyData.id) {
      formData.append("assembly_id", assemblyData.id);
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

        // Check if the response is JSON or not
        if (contentType && contentType.includes("application/json")) {
          // Handle JSON response
          const data = await response.json();
          if (!response.ok) {
            throw new Error(data.message || "Klaida tvarkant įrenginį");
          }
          return data; // Return JSON data for further handling
        } else {
          // Handle non-JSON response (text, HTML, etc.)
          const text = await response.text(); // Read the response as text
          if (!response.ok) {
            throw new Error(text || "Klaida tvarkant įrenginį");
          }
          return { success: true, message: text }; // Return the response as text with success flag
        }
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
      linkToDevices.textContent = "Eiti į mano komplektus";
      linkToDevices.className = "ms-5";
      linkToDevices.href = "/my-assemblies";
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
