<?php
declare(strict_types=1);
namespace Fynkus\Reservation\Domain\Service;


use Fynkus\Reservation\Domain\Vo\ReservationVo;
use Fynkus\Reservation\Domain\Vo\SlotsVo;
use Fynkus\Reservation\Domain\Vo\SlotVo;

interface IReservationService
{
    public function createReservation(ReservationVo $reservationVo): array;

    public function getSpace(string $uuid):mixed;
    public function exitsReservationByDate( string $date ):void;

    public function saveAllReservation(SlotsVo $slotsVo, int $spaceId, string $date ):array;

    public function saveReservation(SlotVo $slotVo, int $spaceId, string $date):string;
}