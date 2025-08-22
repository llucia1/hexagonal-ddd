<?php
namespace Fynkus\Reservation\Domain\Vo;

class SlotsVo
{
    private  array $slots;
    public function __construct(SlotVo ...$slots)
    {
        $this->slots = $slots;
    }

    public function gets(): array
    {
        return $this->slots;
    }
}