<?php

namespace App\Validation;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;


final class ReservationValidator implements ValidatorInterface
{


    public function validate(array $data):ValidationResult
    {
        $regles = [
            'salle_id' => v::intVal()->notEmpty()->positive(),
            'responsable' => v::stringType()->notEmpty()->length(2,120),
            'email' => v::stringType()->notEmpty()->email(),
            'motif' => v::stringType()->notEmpty()->length(5,255),
            'date_debut' => v::dateTime(),
            'date_fin' => v::dateTime(),
        ];

        $errors = [];
        foreach ($regles as $key => $valideur) {
            try {
                $valideur->assert($data[$key] ?? null);
            } catch (NestedValidationException $e) {
                $errors[$key][] = "Le champ {$key} est invalide.";
            }
        }

        if (!empty($errors)) {
           return ValidationResult::failure($errors,$data);
        }

        return ValidationResult::success($data);
    }
    
}