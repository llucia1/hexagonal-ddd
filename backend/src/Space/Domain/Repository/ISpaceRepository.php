<?php
declare(strict_types=1);
namespace Fynkus\Space\Domain\Repository;



use Fynkus\Space\Infrastructure\DB\MySQL\Entity\SpaceEntity;
interface ISpaceRepository
{
    public function getByUuid(string $uuid): ?SpaceEntity;
    public function getAll(): array;
}