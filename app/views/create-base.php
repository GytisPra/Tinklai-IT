<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bazės sukurimas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
        }

        .custom-container {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            width: 100%;
            padding-right: calc(var(--bs-gutter-x)* .5);
            padding-left: calc(var(--bs-gutter-x)* .5);
            margin-right: auto;
            margin-left: auto;
            margin-top: 1.5rem;
            color: white
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
    </style>
</head>

<body data-bs-theme="dark" class="text-white">
    <?php include 'app/views/header.php'; ?>

    <div class="container">
        <h2 class="mt-2 mb-4">Sukurti bazę</h2>

        <form method="POST" action="/create-base/submit">
            <div class="row">
                <div class="mb-3 col form-group">
                    <label for="description" class="form-label">Aprašymas</label>
                    <textarea class="form-control bg-dark text-white" id="description" rows="3"></textarea>
                </div>
                <div class="mb-3 col">
                    <label for="cost" class="form-label">Kaina</label>
                    <input
                        type="text"
                        class="form-control bg-dark text-white"
                        id="cost"
                        name="cost"
                        required />
                </div>
                <div class="row">
                    <div class="mb-3 col">
                        <label for="motherboard_options" class="form-label text-white text-nowrap">Pasirinkti motininę plokštę</label>
                        <select class="form-select bg-dark text-white" id="motherboard_options" aria-label="Motherboard selection">
                            <option value="1">TEST</option>
                            <option value="2">TEST</option>
                            <option value="3">TEST</option>
                            <option value="4">TEST</option>
                            <option value="5">TEST</option>
                            <option value="6">TEST</option>
                        </select>
                    </div>
                    <div class="mb-3 col">
                        <label for="graphic_options" class="form-label text-white text-nowrap">Pasirinkti vaizdo plokštę</label>
                        <select class="form-select bg-dark text-white" id="graphic_options" aria-label="Graphics card selection">
                            <option value="1">TEST</option>
                            <option value="2">TEST</option>
                            <option value="3">TEST</option>
                            <option value="4">TEST</option>
                            <option value="5">TEST</option>
                            <option value="6">TEST</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col">
                        <label for="graphic_options" class="form-label text-white">Pasirinkti procesorių</label>
                        <select class="form-select bg-dark text-white" id="graphic_options" aria-label="Graphics card selection">
                            <option value="1">TEST</option>
                            <option value="2">TEST</option>
                            <option value="3">TEST</option>
                            <option value="4">TEST</option>
                            <option value="5">TEST</option>
                            <option value="6">TEST</option>
                        </select>
                    </div>
                    <div class="mb-3 col">
                        <label for="graphic_options" class="form-label text-white">Pasirinkti talpą</label>
                        <select class="form-select bg-dark text-white" id="graphic_options" aria-label="Graphics card selection">
                            <option value="1">TEST</option>
                            <option value="2">TEST</option>
                            <option value="3">TEST</option>
                            <option value="4">TEST</option>
                            <option value="5">TEST</option>
                            <option value="6">TEST</option>
                        </select>
                    </div>
                </div>





        </form>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Pridėti</button>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>