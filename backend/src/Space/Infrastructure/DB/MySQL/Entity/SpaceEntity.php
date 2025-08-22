<?php
declare(strict_types=1);

namespace Fynkus\Space\Infrastructure\DB\MySQL\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Fynkus\Space\Infrastructure\DB\MySQL\Repository\SpaceRepository;
use Ramsey\Uuid\Uuid as Ramsey;


#[ORM\Entity(repositoryClass: SpaceRepository::class)]
#[ORM\Table(name:'space')]
class SpaceEntity
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'string', length: 36, unique: true)]
    public ?string $uuid;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    public ?string $name="";
    
    
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime')]
    protected ?DateTimeInterface $createdAt = null;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: 'datetime')]
    protected ?DateTimeInterface $updatedAt = null;
    
    public function __construct()
    {
        $this->uuid = Ramsey::uuid4()->toString();
    }

    public function setId(int $id):void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid( ?string $uuid): ?static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }



}
