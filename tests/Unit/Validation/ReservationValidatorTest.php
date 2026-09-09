<?php

declare(strict_types=1);

namespace Tests\Unit\Validation;

use App\Validation\ReservationValidator;
use PHPUnit\Framework\TestCase;

final class ReservationValidatorTest extends TestCase
{
    private ReservationValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new ReservationValidator();
    }

    private function donneesValides(): array
    {
        return [
            'salle_id' => 1,
            'responsable' => 'Awa Ndiaye',
            'email' => 'awa.ndiaye@universite.sn',
            'motif' => 'Cours de programmation',
            'date_debut' => '2026-09-15 10:00:00',
            'date_fin' => '2026-09-15 12:00:00',
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

    public function testEmailInvalide(): void
    {
        $data = $this->donneesValides();
        $data['email'] = 'email-invalide';

        $resultat = $this->validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('email', $resultat->errors());
    }

    public function testResponsableVide(): void
    {
        $data = $this->donneesValides();
        $data['responsable'] = '';

        $resultat = $this->validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('responsable', $resultat->errors());
    }

    public function testDateInvalide(): void
    {
        $data = $this->donneesValides();
        $data['date_debut'] = 'date-invalide';

        $resultat = $this->validator->validate($data);

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('date_debut', $resultat->errors());
    }
}
