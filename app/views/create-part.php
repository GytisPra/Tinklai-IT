<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
    <title>Sukurti dalį</title>
</head>

<body data-bs-theme="dark" class="text-white">
    <?php include 'app/views/header.php'; ?>
    <div class="container">
        <h2 class="mt-2 mb-4">Pridėti dalį</h2>

        <form method="POST" id="partCreationForm">
            <div id="formContainer" class="row justify-content-center">
                <div class="row justify-content-center">
                    <div class="col-6 alert alert-danger mt-3" style="display: none;" id="errorContainer"></div>
                    <div class="col-6 alert alert-success mt-3" style="display: none;" id="successContainer"></div>
                </div>
                <div class="mb-3 col-2">
                    <label for="partType" class="form-label text-white text-nowrap">Pasirinkti detalės tipą</label>
                    <select class="form-select bg-dark text-white" name="part_type" id="partType" aria-label="Motherboard selection">
                        <option value="0" class="text-secondary" selected>Pasirinkite tipą</option>
                        <option value="1">Procesorius</option>
                        <option value="2">Motininė plokštė</option>
                        <option value="3">Ekranas</option>
                        <option value="4">Atmintis</option>
                        <option value="5">Vaizdo plokštė</option>
                        <option value="6">Talpa</option>
                        <option value="7">Aušinimas</option>
                        <option value="8">Operacinė sistema</option>
                    </select>
                </div>
                <div class="mb-3 col-2">
                    <label for="name" class="form-label">Pavadinimas</label>
                    <input
                        type="text"
                        class="form-control bg-dark text-white"
                        id="name"
                        name="name" />
                </div>
                <div class="mb-3 col-2">
                    <label for="amount" class="form-label">Kiekis</label>
                    <input
                        type="text"
                        class="form-control bg-dark text-white"
                        id="amount"
                        name="amount" />
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="mb-3 col-6">
                    <div class="input-group">
                        <span class="input-group-text">€</span>
                        <input type="text" name="price" id="price" class="form-control bg-dark text-white" aria-label="Cost">
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-6 d-flex justify-content-center">
                    <button type="submit" class="w-100 btn btn-primary">Pridėti</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/partFormValidation.js"></script>
</body>

</html>