<?php
class BaseController
{
    public function __construct() {}

    // Display the registration form
    public function showBaseCreationForm()
    {
        include 'app/views/create-base.php';
    }
}
