<?php
declare(strict_types=1);
namespace Fynkus\Common\Infrastructure\Bus;

use Fynkus\Common\Domain\Bus\EventSource\DomainEvent;

final class DomainEventJsonSerializer
{
    public static function serialize(DomainEvent $domainEvent):string
    {
        return json_encode(
            [
                'data'=>[
                    'id'=> $domainEvent->eventId(),
                    'type' => $domainEvent::eventName(),
                    'event_time' =>$domainEvent->eventTime(),
                    'attributes' => array_merge($domainEvent->toPrimitives(),['id'=>$domainEvent->aggregateId()]),
                ],
                'meta'=>[],
            ]
        );
    }

}