document.addEventListener("DOMContentLoaded", async () => {
  const form = document.getElementById("registrationForm");

  const requiredInputs = [
    { id: "name", message: "Įveskite vardą." },
    { id: "lastname", message: "Įveskite pavardę." },
    { id: "username", message: "Įveskite prisijungimo vardą." },
    { id: "phone_number", message: "Įveskite telefono numerį." },
    { id: "email", message: "Įveskite elektroninį paštą." },
    { id: "password", message: "Įveskite slaptažodį." },
  ];

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    // Reset previous error messages
    clearErrors();

    // Validate inputs

    errorMesseges = [];
    errorContainerIds = [];
    i = 0;

    // Validate each required part
    for (let inputObj of requiredInputs) {
      const input = document.getElementById(inputObj.id);

      const trimmedInput = input.value.trim();

      if (!input || !input.value || trimmedInput.length === 0) {
        errorMesseges[i] = inputObj.message;
        errorContainerIds[i] = `${inputObj.id}_error`;
        i++;
      }
    }

    if (errorMesseges.length != 0) {
      displayErrors(errorMesseges, errorContainerIds);
      return;
    }

    submitForm();
  });

  function submitForm() {
    fetch("/register/submit", {
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
          throw new Error(errorData.message || "Klaida kuriant vartotoją");
        }

        return response.json();
      })
      .then((data) => {
        if (data.success) {
          window.location.href = "/login";
          clearErrors();
        } else {
          displayError(data.message || "Klaida kuriant vartotoją");
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

  function displayErrors(messages, errorContainerIds) {
    for (let index = 0; index < messages.length; index++) {
      const message = messages[index];
      const errorContainer = document.getElementById(errorContainerIds[index]);
      const errorItem = document.createElement("span");
      errorItem.className = "text-danger ps-2 text-nowrap";

      errorItem.textContent = message;
      errorContainer.appendChild(errorItem);
      errorContainer.style.display = "block";
    }

    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  }

  function clearErrors() {
    for (let inputObj of requiredInputs) {
      const errorContainer = document.getElementById(`${inputObj.id}_error`);
      if (errorContainer) {
        errorContainer.textContent = "";
        errorContainer.style.display = "none";
      }
    }

    const errorContainer = document.getElementById("errorContainer");
    if (errorContainer) {
      errorContainer.textContent = "";
      errorContainer.style.display = "none";
    }
  }
});
