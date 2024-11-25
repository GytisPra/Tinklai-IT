<h2 class="mb-4">Įrenginiai</h2>

<div class="d-flex gap-5 align-items-center">
    <div class="row">
        <div class="col">
            <label for="filter-options" class="form-label text-white">Rikiuoti pagal</label>
            <select class="form-select bg-dark text-white" id="filter-options" aria-label="Filter Selection">
                <option value="1">Kaina mažėjimo tvarka</option>
                <option value="2">Kaina didėjimo tvarka</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label for="search" class="form-label text-white">
                Ieškoti
                <?php include 'assets/icons/search-icon.svg'; ?>
            </label>
            <input id="search" type="text" class="form-control rounded bg-dark text-white" placeholder="Pavadinimas" />
        </div>
    </div>
    <div class="row">
        <div class="col">
            <a class="border p-2 rounded text-nowrap" href="/create-base">+Pridėti bazę</a>
        </div>
        <div class="col">
            <a class="border p-2 rounded text-nowrap" href="/create-device">+Pridėti įrengnį</a>
        </div>
        <div class="col">
            <a class="border p-2 rounded text-nowrap" href="/create-part">+Pridėti dalį</a>
        </div>
    </div>
</div>

<table class="table pb-2 mt-2 text-white">
    <thead>
        <tr>
            <th scope="col">Nuotrauka</th>
            <th class="text-center" scope="col">Pavadinimas</th>
            <th class="text-center" scope="col">Kaina</th>
            <th class="text-center" scope="col">Aprašymas</th>
            <th class="text-center" scope="col">Veiksmas</th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 0; $i < 10; $i++) { ?>
            <tr key=<?php echo htmlspecialchars("device-$i", ENT_QUOTES, 'UTF-8'); ?>>
                <td scope="row">
                    <?php include 'assets/icons/placeholder-image.svg'; ?>
                </td>
                <td class="text-center">Pavadinimas</td>
                <td class="text-center">0.00$</td>
                <td class="text-center">TRUMPAS APRAŠYMAS</td>
                <td class="text-center">
                    <a href="<?php echo htmlspecialchars("device-$i", ENT_QUOTES, 'UTF-8'); ?>">Peržiūrėti</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>