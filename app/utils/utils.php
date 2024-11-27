<?php
function isUserLoggedIn()
{
    return isset($_SESSION['user']);
}

function isUserInRole($roles)
{
    if (isUserLoggedIn()) {
        $userRole = $_SESSION['user']['role'];
        if (is_array($roles)) {
            return in_array($userRole, $roles);
        }
        return $userRole == $roles; // For a single number
    }
    return false;
}

function getUserRole()
{
    $role = $_SESSION['user']['role'] ?? '';

    return match ($role) {
        1 => "Vadybininkas",
        2 => "Technikas",
        default => ""
    };
}

function getUserId()
{
    if (isUserLoggedIn()) {
        return $_SESSION['user']['id'] ?? '';
    }
}
