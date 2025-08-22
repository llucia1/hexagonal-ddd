<?php
declare(strict_types=1);
namespace Fynkus\Space\Application\Cqrs\Queries;

use Fynkus\Common\Domain\Bus\Query\Query;

final readonly class GetSpaceByUuidQueried implements Query
{
    public function __construct(private ?string $uuid){

    }

    public function uuid():?string{
        return $this->uuid;
    }
}