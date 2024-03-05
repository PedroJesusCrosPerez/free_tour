<?php
// src/Event/ReservationEvent.php

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

    public function validate(): bool
    {
        dd("HOLA SOY UN EVENTO");
        return true;
    }
}