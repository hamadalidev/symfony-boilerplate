<?php

namespace App\Custom;

use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait RequestValidation
{
    public function validate($input, $constraints, ValidatorInterface $validator)
    {
        $violations = $validator->validate($input, $constraints);

        if (0 !== count($violations)) {
            $violationList = [];
            foreach ($violations as $violation) {
                $violationList[] = [
                    'filed'         =>  str_replace(']', '', str_replace('[', '', $violation->getPropertyPath())),
                    'description'   =>  $violation->getMessage()
                ];
            }
            throw new ValidationException("Validation Failed", 400, $violationList);
        }
    }
}
