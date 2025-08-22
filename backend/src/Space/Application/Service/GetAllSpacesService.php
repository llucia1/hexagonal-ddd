<?php
declare(strict_types=1);

namespace Fynkus\Space\Application\Service;

use Psr\Log\LoggerInterface;
use Fynkus\Space\Domain\Repository\ISpaceRepository;
use Fynkus\Space\Application\Response\SpaceResponse;
use Fynkus\Space\Application\Response\SpaceResponses;
use Fynkus\Space\Domain\Exception\ListSpacesEmptyException;
use Fynkus\Space\Domain\Service\ISpaceService;
use Fynkus\Space\Infrastructure\DB\MySQL\Entity\SpaceEntity;

use function Lambdish\Phunctional\map;

class GetAllSpacesService implements ISpaceService
{
    public function __construct(
        private readonly ISpaceRepository $spaceRepository,
        public LoggerInterface       $logger

    ) {}

    public function __invoke(): SpaceResponses
    {
        return $this->getAll();
    }

    public function getAll(): SpaceResponses
    {
        $this->logger->info("Start Service Get All Spaces.");
        $oss =  $this->spaceRepository->getAll();

        return  empty($oss)
            ?throw new ListSpacesEmptyException()
            :new SpaceResponses( ...map($this->toResponse() , $oss) );
    }

    public function toResponse():callable
    {
        return static fn (SpaceEntity $so): SpaceResponse => new SpaceResponse(
            $so->getUuid(), 
            $so->getName()
        );
    }
}
