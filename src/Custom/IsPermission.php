<?php

namespace App\Custom;

use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait IsPermission
{
    public function validateAdminRequest($user, $role)
    {
        if ($user->getRole() != $role) {
            return false;
        }
        return true;
    }
}
