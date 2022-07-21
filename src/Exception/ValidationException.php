<?php

namespace App\Exception;

use Throwable;

class ValidationException extends \Exception
{
    private $violations;

    public function __construct($message = "", $code = 0, array $violations = [])
    {
        parent::__construct($message, $code, null);
        $this->violations = $violations;
    }

    public function getViolations(): array
    {
        return $this->violations;
    }
}
