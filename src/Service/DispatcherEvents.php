<?php
// src/Service/DispatcherEvents.php

namespace App\Service;

use App\Event\MyCustomEvent;
use App\Event\ReservationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\UserCreatedEvent;

class DispatcherEvents
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
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
