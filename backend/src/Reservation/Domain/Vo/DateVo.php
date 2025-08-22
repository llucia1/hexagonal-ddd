<?php
declare(strict_types=1);
namespace Fynkus\Reservation\Domain\Vo;


use InvalidArgumentException;

class DateVo
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if (!self::isValid($value)) {
            throw new InvalidArgumentException(
                "Invalid date format: {$value}. Expected format: dd/mm/yyyy."
            );
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function isValid(string $value): bool
    {

        return preg_match(
            '/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/\d{4}$/',
            trim($value)
        ) === 1;
    }

    public function toDateTime(): \DateTimeImmutable
    {
        $dateTime = \DateTimeImmutable::createFromFormat('d/m/Y', $this->value);

        $errors = \DateTimeImmutable::getLastErrors();
        if (!$dateTime || $errors['error_count'] > 0) {
            throw new InvalidArgumentException(
                "Failed to create DateTime from value: {$this->value}"
            );
        }

        return $dateTime;
    }
    public function formatYmd(): string
    {
        $parsed = \DateTimeImmutable::createFromFormat('d/m/Y', $this->value);
        return $parsed->format('Y-m-d');
    }

    public function __toString(): string
    {
        return $this->value;
    }
}