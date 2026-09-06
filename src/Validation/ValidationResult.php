<?php

declare(strict_types=1);

namespace App\Validation;

final class ValidationResult{

    public function __construct(
        private readonly bool $valid,
        private readonly array $errors,
    ){}
    
    public static function success():self{
        return new self(true,[]);
    }

    public static function failure(array $errors):self{
        return new self(false,$errors);
    }

    public function isValid():bool{
        return $this->valid;
    }

    public function errors():array{
        return $this->errors;
    }


}