<?php
declare(strict_types=1);
namespace Fynkus\Reservation\Domain\Service;

use Fynkus\Reservation\Domain\Vo\DateVo;
use Fynkus\Reservation\Domain\Vo\SpaceUuid;

interface IReservationOfOneSpaceAndDateService
{
    public function getReservation(SpaceUuid $spaceUuid, DateVo $date ): array;
    public function getSpace(string $uuid):mixed;
    public function getReservationSpaceDate(SpaceUuid $spaceUuid, DateVo $date):array;
}