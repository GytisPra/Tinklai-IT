<?php
class DashboardController
{
    public function __construct() {}

    // Display the registration form
    public function showDashboard()
    {
        include 'app/views/dashboard.php';
    }
}
