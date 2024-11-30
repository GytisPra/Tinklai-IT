document.addEventListener("DOMContentLoaded", async () => {
  let orderToChange = null;
  let confirmButtonId = "confirmDelete";

  console.log(userRole);

  if (userRole === "Vadybininkas") {
    confirmButtonId = "confirmChangeStatus";
  }

  function cancelOrder(orderId) {
    orderToChange = orderId;
    const modal = new bootstrap.Modal(document.getElementById("confirmModal"));
    modal.show();
  }

  function changeStatus(orderId) {
    orderToChange = orderId;

    const modal = new bootstrap.Modal(document.getElementById("confirmModal"));
    modal.show();
  }

  document.getElementById("refuse").addEventListener("click", function () {
    location.reload();
  });

  document
    .getElementById(confirmButtonId)
    .addEventListener("click", function () {
      if (orderToChange) {
        const formData = new FormData();
        formData.append("order_id", orderToChange);

        if (userRole === "Vadybininkas") {
          const selectedStatus = document.getElementById(
            `statusSelection ${orderToChange}`
          );
          formData.append("new_status", selectedStatus.value);
        } else {
          formData.append("new_status", "4");
        }

        console.log("HELLO WORLD!");

        fetch("/update-order-status", {
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

  const CancelOrderButton = document.querySelectorAll(".cancel-order-btn");
  const changeStatusSelect = document.querySelectorAll(".order-status-select");

  CancelOrderButton.forEach((button) => {
    button.addEventListener("click", function () {
      const orderId = this.getAttribute("data-order-id");

      cancelOrder(orderId);
    });
  });

  changeStatusSelect.forEach((select) => {
    select.addEventListener("change", function () {
      const orderId = select.getAttribute("data-order-id");

      changeStatus(orderId);
    });
  });
});
