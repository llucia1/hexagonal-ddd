<?php
declare(strict_types=1);

namespace Fynkus\Space\Application\Response;

use Fynkus\Common\Domain\Bus\Query\Response;

final readonly class SpaceResponse implements Response
{
    public function __construct(
        private ?string $uuid = null,
        private ?string $name = null
    ) {
    }
    
    public function uuid(): ?string
    {
        return $this->uuid;
    }

    public function name(): ?string
    {
        return $this->name;
    }
}
