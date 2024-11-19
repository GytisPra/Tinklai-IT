<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-dark">
    <style>
        a {
            color: white;
            text-decoration: none;
        }

        a:hover span,
        a:hover svg {
            color: lightblue;
            stroke: lightblue;
            transition: all .1s ease-out;
        }

        .button-group {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 1.4rem;
            line-height: 1;
            padding: 10px;
            align-self: stretch;
        }

        .navbar-brand {
            font-weight: bold;
        }
    </style>
    <div class="container-fluid">
        <!-- Left Side: Brand -->
        <a class="navbar-brand text-white" href="#">Konstruktorius</a>

        <!-- Burger Menu for Small Screens -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Right Side: Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <?php if (function_exists('isUserLoggedIn') && isUserLoggedIn()) { ?>
                        <!-- Logout Link -->
                        <a href="/logout">
                            <?php include 'assets/icons/logout-icon.svg' ?>
                            <span>Atsijungti</span>
                        </a>
                    <?php } else { ?>
                        <a href="/login">
                            <?php include 'assets/icons/profile-icon.svg' ?>
                            <span>Prisijungti</span>
                        </a>
                        <span class="mx-2 text-white">|</span>
                        <a href="/register" class="text-decoration-none">
                            <span>Registruotis</span>
                        </a>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</nav>