<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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

    <!-- Container -->
    <div class="custom-container">
        <?php include 'app/views/devices_table.php' ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>