<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Įrenginio aprašymas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
        }

        a {
            text-decoration: none;
            cursor: pointer;
            color: white;
        }

        a:hover,
        a:hover span,
        a:hover svg {
            color: lightblue;
            stroke: lightblue;
            transition: all .1s ease-out;
        }

        select.no-border {
            border: none;
            box-shadow: none;
            background-color: transparent;
            padding-left: 0.5rem;
        }
    </style>
</head>

<body data-bs-theme="dark" class="text-white">
    <?php include 'app/views/header.php'; ?>

    <div class="container">
        <h2 class="mt-2 mb-4">Aprašyti įrenginį</h2>

        <form method="POST" action="/create-device/submit">
            <div class="row">
                <div class="row">
                    <div class="mb-3 col">
                        <label for="base_options" class="form-label text-white text-nowrap">Pasirinkti bazę</label>
                        <select class="form-select bg-dark text-white" id="base_options" aria-label="Motherboard selection">
                            <option value="0" class="text-secondary" selected>Pasirinkite bazę</option>
                        </select>
                    </div>
                </div>
                <h4 class="mt-2 mb-4">Bazės specifikacija</h4>
                <div class="row" id="base_specification">
                    <div class="mb-3 col">
                        <ol class="list-group">
                            <li class="list-group-item text-white d-flex justify-content-between align-items-center">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Procesorius</div>
                                    <select class="form-select bg-dark text-white no-border" id="processor_options" aria-label="Processor selection">
                                        <option selected value="3">...</option>
                                    </select>
                                </div>
                                <div class="ms-2 d-flex flex-column">
                                    Likutis
                                    <span class="badge text-bg-primary rounded-pill">14</span>
                                </div>
                            </li>
                            <li class="list-group-item text-white d-flex justify-content-between align-items-center">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Motininė plokštė</div>
                                    <select class="form-select ml-0 bg-dark text-white no-border" id="motherboard_options" aria-label="Motherboard selection">
                                        <option value="2" selected>...</option>
                                    </select>
                                </div>
                                <div class="ms-2 d-flex flex-column">
                                    Likutis
                                    <span class="badge text-bg-primary rounded-pill">14</span>
                                </div>
                            </li>
                            <li class="list-group-item text-white d-flex justify-content-between align-items-center">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Atmintis</div>
                                    <select class="form-select bg-dark text-white no-border" id="memory_options" aria-label="Memory selection">
                                        <option selected value="2">...</option>
                                    </select>
                                </div>
                                <div class="ms-2 d-flex flex-column">
                                    Likutis
                                    <span class="badge text-bg-primary rounded-pill">14</span>
                                </div>
                            </li>
                            <li class="list-group-item text-white d-flex justify-content-between align-items-center">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Talpa</div>
                                    <select class="form-select bg-dark text-white no-border" id="storage_options" aria-label="storage selection">
                                        <option value="2" selected>...</option>
                                    </select>
                                </div>
                                <div class="ms-2 d-flex flex-column d-none">
                                    Likutis
                                    <span class="badge text-bg-primary rounded-pill">14</span>
                                </div>
                            </li>
                            <li class="list-group-item text-white d-flex justify-content-between align-items-center">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Ekranas</div>
                                    <select class="form-select ml-0 bg-dark text-white no-border" id="screen_options" aria-label="Screen selection">
                                        <option value="2" selected>...</option>
                                    </select>
                                </div>
                                <div class="ms-2 d-flex flex-column">
                                    Likutis
                                    <span class="badge text-bg-primary rounded-pill">14</span>
                                </div>
                            </li>
                            <li class="list-group-item text-white d-flex justify-content-between align-items-center">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Vaizdo plokštė</div>
                                    <select class="form-select ml-0 bg-dark text-white no-border" id="graphics_card_options" aria-label="Graphics card selection">
                                        <option value="2" selected>...</option>
                                    </select>
                                </div>
                                <div class="ms-2 d-flex flex-column">
                                    Likutis
                                    <span class="badge text-bg-primary rounded-pill">14</span>
                                </div>
                            </li>
                            <li class="list-group-item text-white d-flex justify-content-between align-items-center">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Aušinimas</div>
                                    <select class="form-select ml-0 bg-dark text-white no-border" id="cooling_options" aria-label="Cooling options selection">
                                        <option value="3" selected>...</option>
                                    </select>
                                </div>
                            </li>
                            <li class="list-group-item text-white d-flex justify-content-between align-items-center">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Operacinė sistema</div>
                                    <select class="form-select ml-0 bg-dark text-white no-border" id="operating_system_options" aria-label="Operating system selection">
                                        <option value="2" selected>...</option>
                                    </select>
                                </div>
                            </li>
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
    <script src="/assets/js/device-create.js"></script>
</body>

</html>