<?php
declare(strict_types=1);

namespace Fynkus\Common\Domain\ValueObjects;

abstract class ArrayValueObject
{
    public function __construct(protected array $value)
    {
    }

    public function value(): array
    {
        return $this->value;
    }

}