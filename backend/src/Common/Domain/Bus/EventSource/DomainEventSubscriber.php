<?php
declare(strict_types=1);

namespace Fynkus\Common\Domain\Bus\EventSource;

interface DomainEventSubscriber
{
    public static function subscribedTo(): array;
}