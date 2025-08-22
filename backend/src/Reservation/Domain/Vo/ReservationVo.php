<?php
declare(strict_types=1);
namespace Fynkus\Reservation\Domain\Vo;

use Error;

final class ReservationVo //extends AggregateRoot
{
    public function __construct(
        private readonly ?SpaceUuid        $spaceUuid,
        private readonly ?DateVo     $date,
        private readonly ?SlotsVo     $slots

    ) {}

    public static function create(
                                    ?SpaceUuid         $spaceUuid, 
                                    ?DateVo $date,
                                    ?SlotsVo     $slots
                                    )
    {
        try {
            $reservation = new self($spaceUuid, $date, $slots  );
            //$node->record(new NodeCreatedDomainEvent(UuidValueObject::random()->value(), $uuid->value(), $name->value(), $hostName->value(), $ip->value(), $sshPort->value(), $timeZone->value(), $keyboard->value(), $display->value(), $storage->value(), $storageIso->value(), $storageImage->value(), $storageBackup->value(), $networkInterface->value()));
            return $reservation;
        } catch (Error $e) {
            // throw exception
        }
    }
    public function spaceUuid(): ?SpaceUuid
    {
        return $this->spaceUuid;
    }
    public function date(): ?DateVo
    {
        return $this->date;
    }
    public function slots(): ?SlotsVo
    {
        return $this->slots;
    }
}