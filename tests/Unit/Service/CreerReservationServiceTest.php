<?php

declare(strict_types=1);

namespace Tests\Unit\Service;

use App\DTO\CreerReservationDTO;
use App\Model\Salle;
use App\Service\CreerReservationService;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Service\Stub\InMemoryReservationRepository;
use Tests\Unit\Service\Stub\InMemorySalleRepository;

final class CreerReservationServiceTest extends TestCase
{
    public function testCreerReservationValide(): void
    {
        // Arrange : préparer les dépendances
        $salles = new InMemorySalleRepository();
        $reservations = new InMemoryReservationRepository();

        $salle = new Salle([
            'nom' => 'Salle B12',
            'batiment' => 'Bâtiment B',
            'capacite' => 40,
            'type' => 'cours',
            'active' => true,
        ]);

        $salles->save($salle);

        $service = new CreerReservationService(
            $salles,
            $reservations
        );

        // Date future
        $dateDebut = new \DateTimeImmutable('+1 day 10:00');
        $dateFin = new \DateTimeImmutable('+1 day 12:00');

        $dto = new CreerReservationDTO(
            salleId: $salle->id,
            responsable: 'Awa Ndiaye',
            email: 'awa.ndiaye@universite.sn',
            motif: 'Cours d’architecture logicielle',
            dateDebut: $dateDebut,
            dateFin: $dateFin
        );

        // Act : exécuter le service
        $reservation = $service->creer($dto);

        // Assert : vérifier le résultat
        $this->assertInstanceOf(
            \App\Model\Reservation::class,
            $reservation
        );

        $this->assertSame($salle->id, $reservation->salle_id);
        $this->assertSame('Awa Ndiaye', $reservation->responsable);
        $this->assertSame('awa.ndiaye@universite.sn', $reservation->email);
        $this->assertSame('Cours d’architecture logicielle', $reservation->motif);
        $this->assertSame('confirmee', $reservation->statut);

        $this->assertSame(
            $reservation,
            $reservations->getReservationById($reservation->id)
        );
    }

    public function testCreerReservationSalleInexistante(): void
{
    // Arrange
    $salles = new InMemorySalleRepository();
    $reservations = new InMemoryReservationRepository();

    $service = new CreerReservationService(
        $salles,
        $reservations
    );

    $dateDebut = new \DateTimeImmutable('+1 day 10:00');
    $dateFin = new \DateTimeImmutable('+1 day 12:00');

    $dto = new CreerReservationDTO(
        salleId: 999,
        responsable: 'Awa Ndiaye',
        email: 'awa.ndiaye@universite.sn',
        motif: 'Cours',
        dateDebut: $dateDebut,
        dateFin: $dateFin
    );

    // Assert : une exception est attendue
    $this->expectException(\App\Exception\SalleIndisponibleException::class);

    // Act
    $service->creer($dto);
}

public function testCreerReservationSalleInactive(): void
{
    // Arrange
    $salles = new InMemorySalleRepository();
    $reservations = new InMemoryReservationRepository();

    $salle = new Salle([
        'nom' => 'Salle C10',
        'batiment' => 'Bâtiment C',
        'capacite' => 30,
        'type' => 'cours',
        'active' => false,
    ]);

    $salles->save($salle);

    $service = new CreerReservationService(
        $salles,
        $reservations
    );

    $dateDebut = new \DateTimeImmutable('+1 day 10:00');
    $dateFin = new \DateTimeImmutable('+1 day 12:00');

    $dto = new CreerReservationDTO(
        salleId: $salle->id,
        responsable: 'Awa Ndiaye',
        email: 'awa.ndiaye@universite.sn',
        motif: 'Cours',
        dateDebut: $dateDebut,
        dateFin: $dateFin
    );

    // Assert
    $this->expectException(
        \App\Exception\SalleIndisponibleException::class
    );

    // Act
    $service->creer($dto);
}

public function testCreerReservationDateFinAvantDebut(): void
{
    // Arrange
    $salles = new InMemorySalleRepository();
    $reservations = new InMemoryReservationRepository();

    $salle = new Salle([
        'nom' => 'Salle D10',
        'batiment' => 'Bâtiment D',
        'capacite' => 30,
        'type' => 'cours',
        'active' => true,
    ]);

    $salles->save($salle);

    $service = new CreerReservationService(
        $salles,
        $reservations
    );

    // La fin est volontairement avant le début
    $dateDebut = new \DateTimeImmutable('+1 day 14:00');
    $dateFin = new \DateTimeImmutable('+1 day 12:00');

    $dto = new CreerReservationDTO(
        salleId: $salle->id,
        responsable: 'Awa Ndiaye',
        email: 'awa.ndiaye@universite.sn',
        motif: 'Cours',
        dateDebut: $dateDebut,
        dateFin: $dateFin
    );

    // Assert
    $this->expectException(
        \App\Exception\ReservationInvalideException::class
    );

    // Act
    $service->creer($dto);
}

public function testCreerReservationDureeSuperieureA4Heures(): void
{
    // Arrange
    $salles = new InMemorySalleRepository();
    $reservations = new InMemoryReservationRepository();

    $salle = new Salle([
        'nom' => 'Salle E10',
        'batiment' => 'Bâtiment E',
        'capacite' => 30,
        'type' => 'cours',
        'active' => true,
    ]);

    $salles->save($salle);

    $service = new CreerReservationService(
        $salles,
        $reservations
    );

    // 5 heures : réservation volontairement trop longue
    $dateDebut = new \DateTimeImmutable('+1 day 10:00');
    $dateFin = new \DateTimeImmutable('+1 day 15:00');

    $dto = new CreerReservationDTO(
        salleId: $salle->id,
        responsable: 'Awa Ndiaye',
        email: 'awa.ndiaye@universite.sn',
        motif: 'Cours',
        dateDebut: $dateDebut,
        dateFin: $dateFin
    );

    // Assert
    $this->expectException(
        \App\Exception\ReservationInvalideException::class
    );

    // Act
    $service->creer($dto);
}

public function testCreerReservationDateDansLePasse(): void
{
    // Arrange
    $salles = new InMemorySalleRepository();
    $reservations = new InMemoryReservationRepository();

    $salle = new Salle([
        'nom' => 'Salle F10',
        'batiment' => 'Bâtiment F',
        'capacite' => 30,
        'type' => 'cours',
        'active' => true,
    ]);

    $salles->save($salle);

    $service = new CreerReservationService(
        $salles,
        $reservations
    );

    $dateDebut = new \DateTimeImmutable('-1 day 10:00');
    $dateFin = new \DateTimeImmutable('-1 day 12:00');

    $dto = new CreerReservationDTO(
        salleId: $salle->id,
        responsable: 'Awa Ndiaye',
        email: 'awa.ndiaye@universite.sn',
        motif: 'Cours',
        dateDebut: $dateDebut,
        dateFin: $dateFin
    );

    // Assert
    $this->expectException(
        \App\Exception\ReservationInvalideException::class
    );

    // Act
    $service->creer($dto);
}
public function testCreerReservationEnConflit(): void
{
    // Arrange
    $salles = new InMemorySalleRepository();
    $reservations = new InMemoryReservationRepository();

    $salle = new Salle([
        'nom' => 'Salle G10',
        'batiment' => 'Bâtiment G',
        'capacite' => 30,
        'type' => 'cours',
        'active' => true,
    ]);

    $salles->save($salle);

    // Une réservation existe déjà : 10h → 12h
    $reservationExistante = new \App\Model\Reservation([
        'salle_id' => $salle->id,
        'responsable' => 'Moussa Diop',
        'email' => 'moussa@example.com',
        'motif' => 'Cours',
        'date_debut' => new \DateTimeImmutable('+1 day 10:00'),
        'date_fin' => new \DateTimeImmutable('+1 day 12:00'),
        'statut' => 'confirmee',
    ]);

    $reservations->save($reservationExistante);

    $service = new CreerReservationService(
        $salles,
        $reservations
    );

    // Nouvelle réservation : 11h → 13h
    // Elle chevauche donc la réservation existante.
    $dto = new CreerReservationDTO(
        salleId: $salle->id,
        responsable: 'Awa Ndiaye',
        email: 'awa.ndiaye@universite.sn',
        motif: 'Réunion',
        dateDebut: new \DateTimeImmutable('+1 day 11:00'),
        dateFin: new \DateTimeImmutable('+1 day 13:00')
    );

    // Assert
    $this->expectException(
        \App\Exception\SalleIndisponibleException::class
    );

    // Act
    $service->creer($dto);
}

public function testCreerReservationAdjacenteSansChevauchement(): void
{
    // Arrange
    $salles = new InMemorySalleRepository();
    $reservations = new InMemoryReservationRepository();

    $salle = new Salle([
        'nom' => 'Salle H10',
        'batiment' => 'Bâtiment H',
        'capacite' => 30,
        'type' => 'cours',
        'active' => true,
    ]);

    $salles->save($salle);

    $reservationExistante = new \App\Model\Reservation([
        'salle_id' => $salle->id,
        'responsable' => 'Moussa Diop',
        'email' => 'moussa@example.com',
        'motif' => 'Cours',
        'date_debut' => new \DateTimeImmutable('+1 day 10:00'),
        'date_fin' => new \DateTimeImmutable('+1 day 12:00'),
        'statut' => 'confirmee',
    ]);

    $reservations->save($reservationExistante);

    $service = new CreerReservationService(
        $salles,
        $reservations
    );

    // Nouvelle réservation : 12h → 14h
    $dto = new CreerReservationDTO(
        salleId: $salle->id,
        responsable: 'Awa Ndiaye',
        email: 'awa.ndiaye@universite.sn',
        motif: 'Réunion',
        dateDebut: new \DateTimeImmutable('+1 day 12:00'),
        dateFin: new \DateTimeImmutable('+1 day 14:00')
    );

    // Act
    $reservation = $service->creer($dto);

    // Assert
    $this->assertInstanceOf(
        \App\Model\Reservation::class,
        $reservation
    );

    $this->assertSame('confirmee', $reservation->statut);
    $this->assertSame(2, count($reservations->getAllReservations()));
}
}