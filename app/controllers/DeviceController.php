<?php
class DeviceController
{
    public function __construct()
    {
        global $mysqli;
        $this->baseModel = new Base($mysqli);
    }

    private $baseModel;

    // Display the registration form
    public function showDeviceCreationForm()
    {
        include 'app/views/create-device.php';
    }
}
