<?php
// src/Event/MyCustomEvent.php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ReservationEvent extends Event
{
    public const NAME = 'reservation.event';

    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}