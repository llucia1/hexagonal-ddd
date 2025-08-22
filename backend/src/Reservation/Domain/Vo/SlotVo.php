<?php
namespace Fynkus\Reservation\Domain\Vo;

class SlotVo
{

    public function __construct(protected ?StatusVo $status, protected ?HourVo $hour)
    {
        
    }
    public function status(): ?StatusVo
    {
        return $this->status;
    }
    public function hour(): ?HourVo
    {
        return $this->hour;
    }
}