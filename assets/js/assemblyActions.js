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
              throw new Error("Klaida pašalinant įrenginį prieinamumą");
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
      const assemblyId = this.getAttribute("data-assembly-id");
      window.location.href = `/assembly-edit?assembly_id=${assemblyId}`;
    });
  });

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const deviceId = this.getAttribute("data-device-id");
      deleteBase(deviceId);
    });
  });
});
