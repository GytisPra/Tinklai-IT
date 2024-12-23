document.addEventListener("DOMContentLoaded", async () => {
  let deviceToDelete = null;

  function deleteBase(deviceId) {
    deviceToDelete = deviceId;
    const modal = new bootstrap.Modal(document.getElementById("confirmModal"));
    modal.show();
  }

  document
    .getElementById("confirmDelete")
    .addEventListener("click", function () {
      if (deviceToDelete) {
        const requestData = { deviceId: deviceToDelete };

        fetch("/delete-device", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          body: JSON.stringify(requestData),
        })
          .then((response) => {
            if (!response.ok) {
              showError(
                "Įrenginio pašalinti negalima, kadangi jis yra naudojamas komplekte"
              );
              throw new Error("Klaida pašalinant įrenginį");
            } else {
              // Reload page to update table
              location.reload();
            }
            return response.json();
          })
          .catch((error) => {
            console.error(error.message);
          });

        // Close the modal after deletion attempt
        const modal = bootstrap.Modal.getInstance(
          document.getElementById("confirmModal")
        );
        modal.hide();
      }
    });

  // Attach event listeners to buttons
  const editButtons = document.querySelectorAll(".edit-base-btn");
  const deleteButtons = document.querySelectorAll(".delete-base-btn");

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const deviceId = this.getAttribute("data-device-id");
      window.location.href = `/edit-device?device_id=${deviceId}`;
    });
  });

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const deviceId = this.getAttribute("data-device-id");
      deleteBase(deviceId);
    });
  });

  function showError(msg) {
    const errorContainer = document.getElementById("errorContainer");
    errorContainer.textContent = msg;
    errorContainer.style.display = "flex";
  }
  function clearError() {
    const errorContainer = document.getElementById("errorContainer");
    errorContainer.textContent = "";
    errorContainer.style.display = "none";
  }
});
