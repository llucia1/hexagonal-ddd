<?php
declare(strict_types=1);
namespace Fynkus\Common\Infrastructure\Bus\Command;

use Fynkus\Common\Domain\Bus\Command\Command;
use Fynkus\Common\Domain\Bus\Command\CommandBus;
use Fynkus\Common\Infrastructure\Bus\CallableFirstParameterExtractor;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class InMemorySymfonyCommandBus implements CommandBus
{

    private readonly MessageBus $bus;

    public function __construct(iterable $commandHandlers){
        $this->bus = new MessageBus(
            [
                new HandleMessageMiddleware(
                    new HandlersLocator(CallableFirstParameterExtractor::forCallables($commandHandlers))
                ),
            ]
        );
    }
    public function dispatch(Command $command): void
    {
        try{
            $this->bus->dispatch($command);
        }catch (NoHandlerForMessageException){

        }catch (HandlerFailedException $ex){
            throw $ex->getPrevious() ?? $ex;
        }
    }
}