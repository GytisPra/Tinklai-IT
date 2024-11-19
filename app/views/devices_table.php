<h2 class="mb-4 text-white">Įrenginiai</h2>
<table class="table text-white">
    <thead>
        <tr>
            <th scope="col">Nuotrauka</th>
            <th scope="col">Pavadinimas</th>
            <th scope="col">Kaina</th>
            <th scope="col">Aprašymas</th>
            <th scope="col">Veiksmas</th>
        </tr>
    </thead>
    <tbody>
        <style>
            a {
                color: white;
                text-decoration: none;
                cursor: pointer;
            }

            a:hover {
                color: lightblue;
                transition: all .1s ease-out;
            }
        </style>
        <?php for ($i = 0; $i < 10; $i++) { ?>
            <tr key=<?php echo (string)$i ?>>
                <th scope="row"><?php include 'assets/icons/placeholder-image.svg' ?></th>
                <td>Pavadinimas</td>
                <td>0.00$</td>
                <td>TRUMPAS APRAŠYMAS</td>
                <td><a href=<?php echo "device-$i"; ?>>Peržiūrėti</a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>