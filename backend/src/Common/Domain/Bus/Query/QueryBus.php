<?php
declare(strict_types=1);
namespace Fynkus\Common\Domain\Bus\Query;

interface QueryBus
{
    public  function ask(Query $query):?Response;

}