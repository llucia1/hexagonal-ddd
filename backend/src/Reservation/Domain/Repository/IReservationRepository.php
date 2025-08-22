<?php
declare(strict_types=1);
namespace Fynkus\Reservation\Domain\Repository;

use Fynkus\Reservation\Infrastructure\DB\MySQL\Entity\ReservationEntity;

interface IReservationRepository
{
    public function findBySpaceAndDate(string $spaceUuid, string $date): array;
    public function findByDate(\DateTimeInterface $date): array;
    public function save(ReservationEntity $reservation): void;
}