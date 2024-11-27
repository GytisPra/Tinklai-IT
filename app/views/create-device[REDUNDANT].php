<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Įrenginio aprašymas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>

<body data-bs-theme="dark" class="text-white">
    <?php include 'app/views/header.php'; ?>

    <div class="container">
        <h2 class="mt-2 mb-4">Aprašyti įrenginį</h2>

        <form method="POST" action="/create-device/submit">
            <div class="row">
                <div class="row">
                    <div class="mb-3 col">
                        <label for="name" class="form-label">Pavadinimas</label>
                        <input
                            type="text"
                            class="form-control bg-dark text-white"
                            id="name"
                            name="name"
                            required />
                    </div>
                    <div class="mb-3 col">
                        <label for="baseOptions" class="form-label text-white text-nowrap">Pasirinkti bazę</label>
                        <select class="form-select bg-dark text-white" id="baseOptions" aria-label="Motherboard selection">
                            <option value="0" class="text-secondary" selected>Pasirinkite bazę</option>
                        </select>
                    </div>
                </div>
                <h4 class="mt-2 mb-4">Bazės specifikacija</h4>
                <div class="row" id="base_specification">
                    <div class="mb-3 col">
                        <div id="loadingOverlay" class="placeholder bg-dark" style="width: 100%; height: 38.563rem; color: white; display: flex; align-items: center; justify-content: center; z-index: 1000;">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <ol class="list-group" id="partsList">
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col">something more</div>
                </div>
        </form>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Pridėti</button>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/deviceLoadBaseData.js"></script>
</body>

</html>