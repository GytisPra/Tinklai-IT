<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($device) ? 'Redaguoti įrenginį' : 'Įrenginio aprašymas' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>

<body data-bs-theme="dark" class="text-white">
    <?php include 'app/views/header.php'; ?>
    <div class="container">
        <h2 class="mt-2 mb-4"><?= isset($device) ? 'Redaguoti įrenginį' : 'Aprašyti įrenginį' ?></h2>
        <form method="POST" id="deviceForm" action="<?= isset($device) ? '/edit-device' : '/create-device' ?>">
            <input type="hidden" name="device_id" value="<?= isset($device) ? $device['device_id'] : '' ?>" />
            <div class="row">
                <div class="mb-3 col">
                    <div class="input-group">
                        <span class="input-group-text col-2">Pavadinimas</span>
                        <input type="text" class="form-control bg-dark text-white" id="name" name="name" required
                            value="<?= isset($device) ? htmlspecialchars($device['device_name']) : '' ?>" />
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="mb-3 col">
                    <div class="input-group">
                        <span class="input-group-text col-2">Konstrukcijos kaina</span>
                        <input required name="price" id="price" class="form-control bg-dark text-white" aria-label="Price" type="text"
                            value="<?= isset($device) ? htmlspecialchars($device['device_cost']) : '' ?>">
                    </div>
                </div>
            </div>
            <h4 class="mt-2 mb-4">Kompiuterio dalys</h4>
            <div class="row">
                <div class="mb-3 col">
                    <ol class="list-group" id="partsList">
                        <div id="loadingOverlay" class="placeholder bg-dark" style="width: 100%; height: 38.563rem; color: white; display: flex; align-items: center; justify-content: center; z-index: 1000;">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </ol>
                </div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary"><?= isset($device) ? 'Atnaujinti' : 'Pridėti' ?></button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/deviceLoadData.js"></script>
    <script src="/assets/js/deviceFormValidation.js"></script>
    <script>
        const deviceData = <?= isset($device) ? json_encode($device, JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_APOS) : 'null' ?>;
    </script>
</body>

</html>