<?php

declare(strict_types=1);

namespace App\Validation;

final class ValidationResult{

    public function __construct(
        private readonly bool $valid,
        private readonly array $errors,
        private readonly array $data,
    ){}
    
    public static function success(array $data):self{
        return new self(true,[],$data);
    }

    public static function failure(array $errors,array $data=[]):self{
        return new self(false,$errors, $data);
    }

    public function isValid():bool{
        return $this->valid;
    }

    public function errors():array{
        return $this->errors;
    }

    public function data():array{
        return $this->data;
    }


}