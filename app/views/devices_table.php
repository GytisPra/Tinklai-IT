<body data-bs-theme="dark" class="text-white"></body>
<h2 class="mb-4">Įrenginiai</h2>

<div class="d-flex gap-5 align-items-center">
    <div class="row">
        <div class="col">
            <label for="filter-options" class="form-label text-white">Rikiuoti pagal</label>
            <select class="form-select bg-dark text-white" id="filter-options" aria-label="Filter Selection">
                <option selected>Pasirinkite filtravimą</option>
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
</div>

<div class="table-responsive" style="position: relative;"> <!-- Make this relative to position the overlay within it -->
    <div id="loadingOverlay" class="bg-dark" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; color: white; display: flex; align-items: center; justify-content: center; z-index: 1000">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <table id="device_table" class="table mt-5 table-striped table-hover table-bordered" style="position: relative;">
        <thead class="table-dark">
            <tr>
                <th>Pavadinimas</th>
                <th>Konstravimo kaina</th>
                <th>Veiksmai</th>
            </tr>
        </thead>
        <tbody id="device_table_body">
        </tbody>
    </table>
</div>

<script src="/assets/js/deviceTableLoad.js"></script>
</body>