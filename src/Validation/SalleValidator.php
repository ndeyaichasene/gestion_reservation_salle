<?php

declare(strict_types=1);

namespace App\Validation;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class SalleValidator implements ValidatorInterface
{

    private const TYPES_AUTORISES = ['cours','informatique','laboratoire','amphitheatre','reunion'];

    public function validate(array $data):ValidationResult
    {

        $regles = [
            'nom' => v::stringType()->notEmpty()->length(2,100),
            'batiment' => v::stringType()->notEmpty()->length(2,100),
            'capacite' => v::intVal()->between(1,1000),
            'type' => v::in(self::TYPES_AUTORISES),
            'active' => v::boolVal()
        ];

        $errors = [];

        foreach ($regles as $key => $validateur) {
            try {
                $validateur->assert($data[$key] ?? null);
            } catch (NestedValidationException $e) {
                $errors[$key][] = "Le champ {$key} est invalide.";
            }
           
        }

        if (!empty($errors)) {
           return ValidationResult::failure($errors);
        }

        return ValidationResult::success();


    }

}