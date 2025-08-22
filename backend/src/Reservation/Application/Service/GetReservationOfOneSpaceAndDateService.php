<?php
declare(strict_types=1);

namespace Fynkus\Reservation\Application\Service;

use Exception;
use Fynkus\Common\Domain\Bus\Query\QueryBus;
use Fynkus\Reservation\Domain\Exception\SpacesNotFoundException;
use Fynkus\Reservation\Domain\Repository\IReservationRepository;
use Fynkus\Reservation\Domain\Service\IReservationOfOneSpaceAndDateService;
use Fynkus\Reservation\Domain\Vo\DateVo;
use Fynkus\Reservation\Domain\Vo\SpaceUuid;
use Fynkus\Space\Application\Cqrs\Queries\GetSpaceByUuidQueried;
use Psr\Log\LoggerInterface;


class GetReservationOfOneSpaceAndDateService implements IReservationOfOneSpaceAndDateService
{
    public function __construct(
        private readonly IReservationRepository $reservationRepository,
        private QueryBus             $queryBus,
        public LoggerInterface       $logger

    ) {}
    public function __invoke( SpaceUuid $spaceUuid, DateVo $date ): array
    {
        return $this->getReservation( $spaceUuid, $date  );
    }

    public function getReservation(SpaceUuid $spaceUuid, DateVo $date ): array
    {
        $spaceEntity = $this->getSpace($spaceUuid->value());
        if (!$spaceEntity) {
            throw new SpacesNotFoundException();
        }

        $this->logger->info('Get reservation for space. ');
        
        
        return $this->getReservationSpaceDate(
            $spaceUuid,  $date
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

    public function getReservationSpaceDate(SpaceUuid $spaceUuid, DateVo $date):array
    {
        return $this->reservationRepository->findBySpaceAndDate($spaceUuid->value(), $date->formatYmd());
        
    }
}
