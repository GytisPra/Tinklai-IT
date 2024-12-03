<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
    <title><?= isUserInRole([1]) ? "Peržiūrėti visus užsakymus" : "Peržiūrėti mano užsakymus" ?></title>
</head>



<body data-bs-theme="dark" class="text-white">

    <?php if (isUserInRole([1])): ?>
        <div class="modal" tabindex="-1" id="confirmModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Patvirtinimas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Ar tikrai norite pakeisti šio užsakymo statusą?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="refuse" data-bs-dismiss="modal">Ne</button>
                        <button type="button" class="btn btn-danger" id="confirmChangeStatus">Taip</button>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="modal" tabindex="-1" id="confirmModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Patvirtinimas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Ar tikrai norite atšaukti šį užsakymą?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="refuse" data-bs-dismiss="modal">Ne</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">Taip</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php
    include 'app/views/header.php';

    // Define status styles
    $status_styles = [
        "Vykdomas" => "bg-warning text-dark", // Yellow for "In Progress"
        "Gautas" => "bg-info text-dark", // Blue for "Received"
        "Priimtas" => "bg-success text-white", // Green for "Accepted"
        "Atšauktas" => "bg-danger text-white" // Red for "Canceled"
    ];
    ?>

    <div class="container">
        <h2 class="mt-2 mb-4"><?= isUserInRole([1]) ? "Peržiūrėti visus užsakymus" : "Peržiūrėti mano užsakymus" ?></h2>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <?php if (isUserInRole([1])): ?>
                            <th>Užsakė</th>
                        <?php endif; ?>
                        <th>Pavadinimas</th>
                        <th>Detalių kaina</th>
                        <th>Statusas</th>
                        <th>Veiksmai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($ordersData)) : ?>
                        <?php foreach ($ordersData as $order) : ?>
                            <tr>
                                <?php if (isUserInRole([1])): ?>
                                    <td class="text-white"><?= htmlspecialchars($order['user_name']); ?></td>
                                <?php endif; ?>
                                <td class="text-white"><?= htmlspecialchars($order['name']); ?></td>
                                <td class="text-white"><?= htmlspecialchars($order['price']); ?> €</td>
                                <td class="text-white">
                                    <?php
                                    // Fetch the status text
                                    $order_status = htmlspecialchars($order['order_status']);

                                    // Apply the appropriate class based on status
                                    if (isset($status_styles[$order_status])) {
                                        $status_class = $status_styles[$order_status];
                                    } else {
                                        $status_class = "bg-secondary text-white"; // Default to grey if status is unknown
                                    }
                                    ?>

                                    <?php if (isUserInRole([1])): ?>
                                        <select class="form-select bg-dark text-white order-status-select" data-order-id="<?= $order['order_id'] ?>" id="statusSelection <?= $order['order_id'] ?>" aria-label="Status select">
                                            <option value="1"
                                                <?php echo ($order_status == "Vykdomas") ? 'class="text-secondary" og-selection="true" selected' : ''; ?>>
                                                Vykdomas
                                            </option>
                                            <option value="2"
                                                <?php echo ($order_status == "Gautas") ? 'class="text-secondary" og-selection="true" selected' : ''; ?>>
                                                Gautas
                                            </option>
                                            <option value="3"
                                                <?php echo ($order_status == "Priimtas") ? 'class="text-secondary" og-selection="true" selected' : ''; ?>>
                                                Priimtas
                                            </option>
                                            <option value="4"
                                                <?php echo ($order_status == "Atšauktas") ? 'class="text-secondary" og-selection="true" selected' : ''; ?>>
                                                Atšauktas
                                            </option>
                                        </select>
                                    <?php else: ?>
                                        <span class="badge <?= $status_class; ?> "><?= $order_status; ?></span>
                                    <?php endif; ?>
                                </td>
                                <?php if (isUserInRole([3])): ?>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-danger cancel-order-btn" data-order-id="<?= htmlspecialchars($order['order_id']); ?>">Atšaukti</button>
                                        </div>
                                    </td>
                                <?php elseif (isUserInRole([1])): ?>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/assembly-view?assembly_id=<?= htmlspecialchars($order['assembly_id']); ?>" class="btn btn-sm btn-primary" data-order-id="<?= htmlspecialchars($order['order_id']); ?>">Peržiūrėti</a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="11" class="text-center">Nerasta jokių duomenų</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="/assets/js/orderActions.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const userRole = <?= json_encode(getUserRole()) ?>;
    </script>
</body>

</html>