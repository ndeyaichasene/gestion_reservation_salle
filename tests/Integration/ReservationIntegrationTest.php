<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Model\Reservation;
use App\Model\Salle;
use App\Repository\ReservationRepository;
use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase;

final class ReservationIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $bootDatabase = require dirname(__DIR__, 2) . '/config/database.php';
        $bootDatabase();

        Capsule::connection()->beginTransaction();
    }

    protected function tearDown(): void
    {
        Capsule::connection()->rollBack();

        parent::tearDown();
    }

    public function testEloquentCreeUneSalle(): void
    {
        $salle = Salle::create([
            'nom' => 'Salle Integration',
            'batiment' => 'Batiment A',
            'capacite' => 50,
            'type' => 'cours',
            'active' => true,
        ]);

        $this->assertNotNull($salle->id);
        $this->assertSame('Salle Integration', $salle->nom);
        $this->assertSame(50, $salle->capacite);
    }

    public function testRelationSalleReservationsFonctionne(): void
    {
        $salle = Salle::create([
            'nom' => 'Salle Relation',
            'batiment' => 'Batiment B',
            'capacite' => 40,
            'type' => 'informatique',
            'active' => true,
        ]);

        Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Awa Ndiaye',
            'email' => 'awa.ndiaye@universite.sn',
            'motif' => 'Cours de programmation',
            'date_debut' => '2026-09-15 10:00:00',
            'date_fin' => '2026-09-15 12:00:00',
            'statut' => 'confirmee',
        ]);

        $reservations = $salle->reservations()->get();

        $this->assertCount(1, $reservations);
        $this->assertSame(
            'Awa Ndiaye',
            $reservations->first()->responsable
        );
    }

    public function testRechercheReservationEnConflit(): void
    {
        $salle = Salle::create([
            'nom' => 'Salle Conflit',
            'batiment' => 'Batiment C',
            'capacite' => 30,
            'type' => 'reunion',
            'active' => true,
        ]);

        Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Awa Ndiaye',
            'email' => 'awa.ndiaye@universite.sn',
            'motif' => 'Réunion équipe',
            'date_debut' => '2026-09-15 10:00:00',
            'date_fin' => '2026-09-15 12:00:00',
            'statut' => 'confirmee',
        ]);

        $repository = new ReservationRepository();

        $conflit = $repository->getConflitReservation(
            $salle->id,
            new \DateTimeImmutable('2026-09-15 11:00:00'),
            new \DateTimeImmutable('2026-09-15 13:00:00')
        );

        $this->assertInstanceOf(Reservation::class, $conflit);
        $this->assertSame('Awa Ndiaye', $conflit->responsable);
    }

    public function testAnnulationReservation(): void
    {
        $salle = Salle::create([
            'nom' => 'Salle Annulation',
            'batiment' => 'Batiment D',
            'capacite' => 25,
            'type' => 'reunion',
            'active' => true,
        ]);

        $reservation = Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Awa Ndiaye',
            'email' => 'awa.ndiaye@universite.sn',
            'motif' => 'Réunion projet',
            'date_debut' => '2026-09-16 10:00:00',
            'date_fin' => '2026-09-16 12:00:00',
            'statut' => 'confirmee',
        ]);

        $repository = new ReservationRepository();

        $reservationAnnulee = $repository->annulerReservation($reservation);

        $this->assertSame('annulee', $reservationAnnulee->statut);

        $reservationEnBase = Reservation::find($reservation->id);

        $this->assertNotNull($reservationEnBase);
        $this->assertSame('annulee', $reservationEnBase->statut);
    }
}