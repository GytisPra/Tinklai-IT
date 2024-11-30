<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
    <title>Mano įrenginiai</title>
</head>

<body data-bs-theme="dark" class="text-white">
    <?php
    include 'app/views/header.php';
    ?>

    <div class="container">
        <div class="modal" tabindex="-1" id="confirmModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Patvirtinimas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Ar tikrai norite pašalinti šį komplektą?</p>
                        <p class="text-danger fw-bold">
                            Įspėjimas: Jei pašalinsite šį komplektą, visi su juo susiję užsakymai taip pat bus pašalinti.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ne</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">Taip</button>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="mt-2 mb-4">Peržiūrėti mano komplektus</h2>

        <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer">

        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Pavadinimas</th>
                        <th>Komplekto kaina</th>
                        <th>Pilna kaina</th>
                        <th>Veiksmai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($assemblyData)) : ?>
                        <?php foreach ($assemblyData as $assembly) : ?>
                            <tr>
                                <td class="text-white"><?= htmlspecialchars($assembly['name']); ?></td>
                                <td class="text-white"><?= htmlspecialchars($assembly['price']); ?> €</td>
                                <td class="text-white"><?= htmlspecialchars(intval($assembly['total_price']) + intval($assembly['price'])); ?> €</td>
                                <td class="text-white">
                                    <div class="btn-group" role="group">
                                        <button class="btn bg-success btn-sm btn-success order-assembly-btn" data-assembly-name="<?= htmlspecialchars($assembly['name']); ?>" data-assembly-id="<?= htmlspecialchars($assembly['id']); ?>">Užsakyti</button>
                                        <button class="btn btn-sm btn-primary edit-assembly-btn" data-assembly-id="<?= htmlspecialchars($assembly['id']); ?>">Redaguoti</button>
                                        <button class="btn btn-sm btn-danger delete-assembly-btn" data-assembly-id="<?= htmlspecialchars($assembly['id']); ?>">Pašalinti</button>
                                    </div>
                                </td>
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

    <script src="/assets/js/assemblyActions.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>