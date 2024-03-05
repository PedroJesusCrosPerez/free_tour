<?php
// src/Service/DispatcherEvents.php

namespace App\Service;

use App\Event\ReservationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DispatcherEvents
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatch($event): void
    {
        // Disparar el evento
        $this->dispatcher->dispatch($event);
    }

    public function dispatchReservation($data)
    {
        // Disparar el evento
        $event = new ReservationEvent($data);
        $this->dispatcher->dispatch($event, ReservationEvent::NAME);
    }
}

/*

    $event = new ReservationEvent(['reservation'=>$reservation]);
    $this->dispatcher->dispatch($event, ReservationEvent::NAME);

*/
