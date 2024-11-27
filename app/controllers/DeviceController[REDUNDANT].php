<?php
class DeviceController
{
    public function __construct()
    {
        global $mysqli;
        $this->baseModel = new Device($mysqli);
    }

    private $baseModel;

    // Display the registration form
    public function showDeviceCreationForm()
    {
        include 'app/views/create-device.php';
    }

    public function showTechniciansDevices()
    {
        include 'app/views/my-devices.php';
    }
}
