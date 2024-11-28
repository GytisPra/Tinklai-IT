<body data-bs-theme="dark" class="text-white"></body>
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
        <div class="col">
            <label for="filter-options" class="form-label text-white">Filtruoti tipą</label>
            <select class="form-select bg-dark text-white" id="filter-options" aria-label="Filter Selection">
                <option value="0" class="text-secondary">Pasirinkite tipą</option>
                <option value="1">Darbinis kompiuteris</option>
                <option value="2">Žaidimo kompiuteris</option>
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
</div>

<?php global $deviceController;
$allDevices = $deviceController->getAllDevices(); ?>

<div class="table-responsive">
    <table class="table mt-5 table-striped table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Pavadinimas</th>
                <th>Konstravimo kaina</th>
                <th>Veiksmai</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($allDevices)) : ?>
                <?php foreach ($allDevices as $device) : ?>
                    <tr>
                        <td><?= htmlspecialchars($device['device_name']); ?></td>
                        <td><?= htmlspecialchars($device['device_cost']); ?> €</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="/assemble-device?device_id=<?= htmlspecialchars($device['device_id']); ?>" class="btn btn-sm btn-primary edit-base-btn">Komplektuoti</a>
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

</table>
</body>

</html>