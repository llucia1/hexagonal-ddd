<?php
declare(strict_types=1);

namespace Fynkus\Reservation\Infrastructure\DB\MySQL\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Fynkus\Space\Infrastructure\DB\MySQL\Repository\SpaceRepository;
use Ramsey\Uuid\Uuid as Ramsey;


#[ORM\Entity]
#[ORM\Table(name: 'reservation')]
#[ORM\UniqueConstraint(name: 'uniq_reservation', columns: ['space_id', 'date', 'hour'])]
class ReservationEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 36, unique: true)]
    private string $uuid;

    #[ORM\ManyToOne(targetEntity: 'Fynkus\\Space\\Infrastructure\\DB\\MySQL\\Entity\\SpaceEntity')]
    #[ORM\JoinColumn(name: 'space_id', referencedColumnName: 'id', nullable: false)]
    private ?object $space = null;

    #[ORM\Column(type: 'date')]
    private DateTimeInterface $date;

    #[ORM\Column(type: 'integer')]
    private int $hour;

    #[ORM\Column(type: 'string', length: 36)]
    private string $status;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $updatedAt;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getSpace(): ?object
    {
        return $this->space;
    }

    public function setSpace(?object $space): self
    {
        $this->space = $space;
        return $this;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getHour(): int
    {
        return $this->hour;
    }

    public function setHour(int $hour): self
    {
        $this->hour = $hour;
        return $this;
    }
    public function getStatus(): string
    {
        return $this->status;
    }
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

}
