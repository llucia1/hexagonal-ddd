<?php
declare(strict_types=1);
namespace Fynkus\Space\Domain\Service;

use Fynkus\Space\Application\Response\SpaceResponses;

interface ISpaceService
{
    public function getAll(): SpaceResponses;
}