<?php
declare(strict_types=1);
namespace Fynkus\Space\Application\Cqrs\Handlers;


use Fynkus\Common\Domain\Bus\Query\QueryHandler;
use Fynkus\Space\Application\Cqrs\Queries\GetSpaceByUuidQueried;
use Fynkus\Space\Application\Response\SpaceEntityResponse;
use Fynkus\Space\Domain\Exception\SpacesNotFoundException;
use Fynkus\Space\Domain\Repository\ISpaceRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetSpaceByUuidHandler implements QueryHandler
{


    public function __construct(private  readonly  ISpaceRepository $spaceService)
    {
    }


    public function __invoke(GetSpaceByUuidQueried $space): SpaceEntityResponse
    {
        try {
            $spaces = $this->spaceService->getByUuid( $space->uuid() );
            $result = new SpaceEntityResponse( $spaces ? $spaces : null );
            return $result;

        }catch(\Exception $ex){
            throw new SpacesNotFoundException();
        }
    }


}