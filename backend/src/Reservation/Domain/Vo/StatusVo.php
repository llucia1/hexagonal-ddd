<?php
namespace Fynkus\Reservation\Domain\Vo;

use Fynkus\Common\Domain\Const\Status;

class StatusVo
{

    public function __construct(protected ?string $value)
    {
        $this->ensureValid($value);
        $this->value = $value;
    }

    public function value(): ?string
    {
        return $this->value;
    }




    private function ensureValid(string $value): void
    {
        $valid = [
            Status::RESERVED,
            Status::FREE,
        ];

        if (!in_array($value, $valid, true)) {
            throw new \InvalidArgumentException(sprintf('Invalid status value: "%s"', $value));
        }
    }
}