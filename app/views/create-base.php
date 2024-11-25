<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bazės sukūrimas</title>
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

        input.no-shadow {
            box-shadow: none;
            background-color: transparent;
        }
    </style>
</head>

<body data-bs-theme="dark" class="text-white">
    <?php include 'app/views/header.php'; ?>

    <div class="container">
        <h2 class="mt-2 mb-4">Sukurti bazę</h2>

        <form method="POST" action="/create-base/submit">
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
                        <label for="computer_type_select" class="form-label">Kompiuterio tipas</label>
                        <select class="form-select bg-dark text-white" id="computer_type_select" aria-label="Processor selection">
                            <option value="0" selected>Pasirinkite kompiuterio tipą</option>
                        </select>
                    </div>
                </div>
                <h4 class="mt-2 mb-4">Bazės specifikacija</h4>
                <div class="row" id="base_specification">
                    <div class="mb-3 col">
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
    <script src="/assets/js/base-create.js"></script>
</body>

</html>