<?php
declare(strict_types=1);

namespace Fynkus\Common\Domain\Bus\EventSource;

interface EventBus
{
    public function publish(DomainEvent ...$events): void;

}