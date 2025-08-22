<?php
declare(strict_types=1);

namespace Fynkus\Reservation\Application\Service;

use Exception;
use Fynkus\Common\Domain\Bus\Query\QueryBus;
use Fynkus\Common\Domain\ValueObjects\UuidValueObject;
use Fynkus\Reservation\Domain\Exception\ReservationDuplicateException;
use Fynkus\Reservation\Domain\Exception\SpacesNotFoundException;
use Fynkus\Reservation\Domain\Repository\IReservationRepository;
use Fynkus\Reservation\Domain\Service\IReservationService;
use Fynkus\Reservation\Domain\Vo\ReservationVo;
use Fynkus\Reservation\Domain\Vo\SlotsVo;
use Fynkus\Reservation\Domain\Vo\SlotVo;
use Fynkus\Reservation\Infrastructure\DB\MySQL\Entity\ReservationEntity;
use Fynkus\Space\Application\Cqrs\Queries\GetSpaceByUuidQueried;
use Psr\Log\LoggerInterface;


class PostReservationService implements IReservationService
{
    public function __construct(
        private readonly IReservationRepository $reservationRepository,
        private QueryBus             $queryBus,
        public LoggerInterface       $logger

    ) {}

    public function __invoke( ReservationVo $reservationVo ): array
    {
        return $this->createReservation($reservationVo );
    }

    public function createReservation(ReservationVo $reservationVo): array
    {
        $spaceEntity = $this->getSpace($reservationVo->spaceUuid()->value());
        if (!$spaceEntity) {
            throw new SpacesNotFoundException();
        }
        $this->logger->info('Creating reservation for space: ' . $reservationVo->spaceUuid()->value());

        $this->exitsReservationByDate($reservationVo->date()->value());
        
        return $this->saveAllReservation(
            $reservationVo->slots(),
            $spaceEntity,
            $reservationVo->date()->value()
        );
        

    }
    public function getSpace(string $uuid):mixed
    {
        $spaceEntityQuery = $this->queryBus->ask(new GetSpaceByUuidQueried($uuid ) );
        $spaceEntity = $spaceEntityQuery->get();
        if (!$spaceEntity  || $spaceEntity instanceof Exception) {
            return null;
        }
        return $spaceEntity;  
    }

    public function exitsReservationByDate( string $date ):void
    {
        $dateObj = \DateTimeImmutable::createFromFormat('d/m/Y', $date);
        if (!$dateObj) {
            throw new \InvalidArgumentException("Invalid date format: $date");
        }
        $reservation = $this->reservationRepository->findByDate($dateObj);
        if ($reservation) {
            throw new ReservationDuplicateException();
            // PODRIAMOS ELIMINAR LA RESERVA DUPLICADA Y CONTINUAR CON LA NUEVA RESERVA. PERO RESPETO REST AL SER POST
        }
    }

    public function saveAllReservation(SlotsVo $slotsVo, $spaceEntity, string $date ):array
    {
        return array_map(
            fn (SlotVo $slotVo) => $this->saveReservation($slotVo, $spaceEntity, $date),
            $slotsVo->gets()
        );    
    }

    public function saveReservation(SlotVo $slotVo, $spaceEntity, string $date):string
    {

        $reservationEntity = new ReservationEntity();
        $reservationEntity->setUuid(UuidValueObject::random()->value() );
        $reservationEntity->setSpace($spaceEntity);
        $dateObj = \DateTimeImmutable::createFromFormat('d/m/Y', $date);
        $reservationEntity->setDate($dateObj);
        $reservationEntity->setStatus($slotVo->status()->value());
        $reservationEntity->setHour($slotVo->hour()->value());
        $now = new \DateTimeImmutable();
        $reservationEntity->setCreatedAt($now);
        $reservationEntity->setUpdatedAt($now);
        $this->reservationRepository->save($reservationEntity);

        return $reservationEntity->getUuid();
        
    }
}
