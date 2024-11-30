document.addEventListener("DOMContentLoaded", async () => {
  // Attach event listeners to buttons
  const editButtons = document.querySelectorAll(".edit-assembly-btn");
  const orderButtons = document.querySelectorAll(".order-assembly-btn");
  const deleteButtons = document.querySelectorAll(".delete-assembly-btn");
  let assemblyToDelete = null;

  function deleteBase(assemblyId) {
    assemblyToDelete = assemblyId;
    const modal = new bootstrap.Modal(document.getElementById("confirmModal"));
    modal.show();
  }

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const assemblyId = this.getAttribute("data-assembly-id");
      deleteBase(assemblyId);
    });
  });

  document
    .getElementById("confirmDelete")
    .addEventListener("click", function () {
      if (assemblyToDelete) {
        const formData = new FormData();
        formData.append("assembly_id", assemblyToDelete);

        console.log(assemblyToDelete);

        fetch("/assembly-delete", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              location.reload();
            } else {
              console.error(data.error);
            }
          })
          .catch((error) => {
            console.error("Error:", error);
          });

        // Close the modal after deletion attempt
        const modal = bootstrap.Modal.getInstance(
          document.getElementById("confirmModal")
        );
        modal.hide();
      }
    });

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const assemblyId = this.getAttribute("data-assembly-id");
      window.location.href = `/assembly-edit?assembly_id=${assemblyId}`;
    });
  });

  orderButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const assemblyId = this.getAttribute("data-assembly-id");
      const assemblyName = this.getAttribute("data-assembly-name");

      console.log(assemblyId);
      // Create form data
      const formData = new FormData();
      formData.append("assembly_id", assemblyId);

      // Make the POST request using fetch
      fetch("/assembly-order", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json()) // Assuming the server responds with JSON
        .then((data) => {
          if (data.success) {
            createAndShowToast(assemblyName);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
        });
    });
  });

  function createAndShowToast(assemblyName) {
    const toastElement = document.createElement("div");
    toastElement.id = "liveToast";
    toastElement.classList.add("toast");
    toastElement.classList.add("d-flex");
    toastElement.setAttribute("role", "alert");
    toastElement.setAttribute("aria-live", "assertive");
    toastElement.setAttribute("aria-atomic", "true");

    // Set the inner HTML of the toast
    toastElement.innerHTML = `
            <div class="toast-header">
                <strong class="me-auto">Komplektai</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${assemblyName} užsakytas.
            </div>
      `;

    // Append the toast to the container
    const toastContainer = document.getElementById("toastContainer");
    toastContainer.appendChild(toastElement);

    // Create a new Bootstrap toast instance and show it
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
  }
});
