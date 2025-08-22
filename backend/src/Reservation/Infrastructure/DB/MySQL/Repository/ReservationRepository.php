<?php
declare(strict_types=1);

namespace Fynkus\Reservation\Infrastructure\DB\MySQL\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Fynkus\Reservation\Domain\Repository\IReservationRepository;
use Fynkus\Reservation\Infrastructure\DB\MySQL\Entity\ReservationEntity;

/**
 * @extends ServiceEntityRepository<ReservationEntity>
 *
 * @implements IReservationRepository<ReservationEntity>
 *
 * @method ReservationEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationEntity[]    findAll()
 * @method ReservationEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository implements IReservationRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationEntity::class);
    }
    public function findBySpaceAndDate(string $spaceUuid, string $date): array
    {
        $qb = $this->_em->createQueryBuilder();
    
        return $qb->select('r')
            ->from(ReservationEntity::class, 'r')
            ->join('r.space', 's')
            ->where('s.uuid = :uuid')
            ->andWhere('r.date = :date')
            ->setParameter('uuid', $spaceUuid)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }
    public function findByDate(\DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.date = :date')
            ->setParameter('date', $date->format('d-m-Y'))
            ->getQuery()
            ->getResult();
    }
    public function save(ReservationEntity $reservation): void
    {
        $this->_em->persist($reservation);
        $this->_em->flush();
    }
}

