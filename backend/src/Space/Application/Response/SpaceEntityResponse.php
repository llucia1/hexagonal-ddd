<?php
declare(strict_types=1);

namespace Fynkus\Space\Application\Response;

use Fynkus\Common\Domain\Bus\Query\Response;
use Fynkus\Space\Infrastructure\DB\MySQL\Entity\SpaceEntity;

final readonly class SpaceEntityResponse implements Response
{
    public function __construct(
        private ?SpaceEntity $spaceEntity = null
    ) {
    }

    public function get(): ?SpaceEntity
    {
        return $this->spaceEntity;
    }
}
