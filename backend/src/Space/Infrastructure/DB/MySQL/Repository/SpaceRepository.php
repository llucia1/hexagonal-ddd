<?php
declare(strict_types=1);

namespace Fynkus\Space\Infrastructure\DB\MySQL\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Fynkus\Space\Domain\Repository\ISpaceRepository;
use Fynkus\Space\Infrastructure\DB\MySQL\Entity\SpaceEntity;

/**
 * @extends ServiceEntityRepository<SpaceEntity>
 *
 * @implements ISpaceRepository<SpaceEntity>
 *
 * @method SpaceEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpaceEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpaceEntity[]    findAll()
 * @method SpaceEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaceRepository extends ServiceEntityRepository implements ISpaceRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceEntity::class);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getByUuid(string $uuid): ?SpaceEntity
    {
        return $this->findOneBy(['uuid' => $uuid]);
    }
}

