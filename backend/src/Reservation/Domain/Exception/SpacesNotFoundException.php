<?php
declare(strict_types=1);

namespace Fynkus\Reservation\Domain\Exception;

use Exception;

class SpacesNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Not Found Space.' );
    }
}