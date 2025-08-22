<?php
declare(strict_types=1);

namespace Fynkus\Common\Domain\ValueObjects;

abstract class BooleanValueObject
{

    public function __construct(protected bool $value)
    {
    }

    public function value(): bool
    {
        return $this->value;
    }
}