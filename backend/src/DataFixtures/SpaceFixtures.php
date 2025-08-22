<?php

namespace Fynkus\DataFixtures;

use App\Space\Domain\Entity\SpaceEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Fynkus\Common\Domain\ValueObjects\UuidValueObject;
use Fynkus\Space\Infrastructure\DB\MySQL\Entity\SpaceEntity as EntitySpaceEntity;
use Symfony\Component\Uid\Uuid;

class SpaceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $spaces = [
            'Pista de Padel',
            'Piscina',
            'Gimnasio'
        ];

        foreach ($spaces as $name) {
            $space = new EntitySpaceEntity();
            $space->setUuid(UuidValueObject::random()->value());
            $space->setName($name);

            
            $now = new \DateTimeImmutable();
            $space->setCreatedAt($now);
            $space->setUpdatedAt($now);

            $manager->persist($space);
        }

        $manager->flush();
    }
}