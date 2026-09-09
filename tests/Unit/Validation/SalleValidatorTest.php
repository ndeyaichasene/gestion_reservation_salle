<?php

declare(strict_types=1);

namespace Tests\Unit\Validation;

use App\Validation\SalleValidator;
use PHPUnit\Framework\TestCase;

final class SalleValidatorTest extends TestCase
{
    private SalleValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new SalleValidator();
    }

    private function donneesValides(): array
    {
        return [
            'nom' => 'Salle A',
            'batiment' => 'Batiment A',
            'capacite' => 50,
            'type' => 'cours',
            'active' => true,
        ];
    }

    public function testDonneesValides(): void
    {
        $resultat = $this->validator->validate(
            $this->donneesValides()
        );

        $this->assertTrue($resultat->isValid());
        $this->assertEmpty($resultat->errors());
    }

    public function testCapaciteNegative(): void
    {
        $data = $this->donneesValides();
        $data['capacite'] = -10;

        $resultat = $this->validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('capacite', $resultat->errors());
    }

    public function testTypeInconnu(): void
    {
        $data = $this->donneesValides();
        $data['type'] = 'inconnu';

        $resultat = $this->validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('type', $resultat->errors());
    }
}

