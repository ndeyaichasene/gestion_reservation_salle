<?php

declare(strict_types=1);

namespace App\Validation;

interface SalleValidatorInterface extends ValidatorInterface
{
    public function validate(array $data): ValidationResult;
}
