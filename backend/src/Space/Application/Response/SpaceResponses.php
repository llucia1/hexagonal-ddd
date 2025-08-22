<?php
declare(strict_types=1);

namespace Fynkus\Space\Application\Response;

use Fynkus\Common\Domain\Bus\Query\Response;
final class SpaceResponses implements Response
{
    private readonly  array $spaces;
    public function __construct(SpaceResponse ...$space)
    {
        $this->spaces = $space;
    }

    public function gets(): array
    {
        return $this->spaces;
    }
}