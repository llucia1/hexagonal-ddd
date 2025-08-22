<?php
declare(strict_types=1);
namespace Fynkus\Common\Infrastructure\Bus\Command;


use Fynkus\Common\Domain\Bus\Command\Command;

final class CommandNotRegisteredError extends  \RuntimeException
{
    public function __construct(Command $command)
    {
        $commandClass = $command::class;
        parent::__construct("The Command <c$commandClass> hasn't command handler associated :(");
    }

}