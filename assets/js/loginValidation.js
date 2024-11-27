document.addEventListener("DOMContentLoaded", async () => {
  const form = document.getElementById("loginForm");

  form.addEventListener("submit", function (event) {
    event.preventDefault();
    clearError();
    submitForm();
  });

  function submitForm() {
    fetch("/login/submit", {
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
        if (data.success && data.authenticated) {
          clearError();
          window.location.href = "/dashboard";
        } else if (!data.authenticated) {
          displayError(data.message || "Neteisingi prisijungimo duomenys");
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
      errorContainer.className = "alert alert-danger";
      form.insertBefore(errorContainer, form.firstChild);
    }

    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });

    const errorItem = document.createElement("span");
    errorItem.textContent = message;
    errorContainer.appendChild(errorItem);

    // Set error message
    errorContainer.appendChild(errorItem);
    errorContainer.style.display = "block";
  }
  function clearError() {
    const errorContainer = document.getElementById("errorContainer");
    if (errorContainer) {
      errorContainer.textContent = "";
      errorContainer.style.display = "none";
    }
  }
});
