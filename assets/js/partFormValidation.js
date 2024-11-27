document.addEventListener("DOMContentLoaded", async () => {
  const form = document.getElementById("partCreationForm");
  const formContainer = document.getElementById("formContainer");

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    // Reset previous error messages
    clearErrors();

    // Validate inputs
    const requiredInputs = [
      { id: "partType", message: "Detalės tipas turi būti pasirinktas." },
      { id: "amount", message: "Įveskite kiekį." },
      { id: "price", message: "Talpa turi būti pasirinkta." },
      { id: "name", message: "Įveskite pavadinimą." },
    ];

    errorMesseges = [];
    i = 0;

    // Validate each required part
    for (let part of requiredInputs) {
      const input = document.getElementById(part.id);

      if (input.id == "price") {
        const value = parseInt(input.value);

        // Check if the value is not a number
        if (isNaN(value)) {
          errorMesseges[i] = "Kaina turi būti skaičius";
          i++;
        } else if (value <= 0) {
          errorMesseges[i] = "Kaina turi būti teigiama";
          i++;
        }
      } else if (input.id == "amount") {
        const value = parseInt(input.value);

        // Check if the value is not a number
        if (isNaN(value)) {
          errorMesseges[i] = "Kiekis turi būti skaičius";
          i++;
        } else if (value <= 0) {
          errorMesseges[i] = "Kiekis turi būti teigiamas";
          i++;
        }
      }

      if (!input || !input.value || input.value === "0") {
        errorMesseges[i] = part.message;
        i++;
      }
    }

    if (errorMesseges.length != 0) {
      displayErrors(errorMesseges);
      return;
    }

    submitForm();
  });

  function submitForm() {
    fetch("/create-part/submit", {
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
          throw new Error(errorData.message || "Klaida kuriant detalę");
        }

        return response.json();
      })
      .then((data) => {
        if (data.success) {
          clearErrors();
          showSuccess(data.message || "Detalė sėkmingai sukurta");
        } else {
          displayError(data.message || "Klaida kuriant detalę");
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
      formContainer.insertBefore(errorContainer);
    }

    clearSuccess();

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
      successContainer.innerHTML = "";
    }
  }

  function showSuccess(message) {
    // Get or create success container
    let successContainer = document.getElementById("successContainer");
    let linkToDashBoard = document.getElementById("LinkToDashBoard");
    successContainer.style.display = "block";

    // Create success container if it doesn't exist
    if (!successContainer) {
      successContainer = document.createElement("div");
      successContainer.id = "successContainer";
      successContainer.className = "alert alert-success mt-3";
      form.insertBefore(successContainer, form.firstChild); // Assuming 'form' is the reference to your form element
    }

    // Set the success message
    successContainer.textContent = message;

    // Create link to dashboard only if it doesn't exist
    if (!linkToDashBoard) {
      linkToDashBoard = document.createElement("a");
      linkToDashBoard.id = "LinkToDashBoard";
      linkToDashBoard.textContent = "Grįžti į pagrindinį langą";
      linkToDashBoard.className = "ms-5";
      linkToDashBoard.href = "/dashboard"; // Set the URL for the link
      successContainer.appendChild(linkToDashBoard);
    }

    // Show the success container
    successContainer.style.display = "block";

    // Scroll to the top of the page
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  }
});
