<?php
// src/Service/DispatcherEvents.php

namespace App\Service;

use App\Event\MyCustomEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\UserCreatedEvent;

class DispatcherEvents
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatchEventGetData($data)
    {
        // Disparar el evento
        $event = new MyCustomEvent($data);
        $this->dispatcher->dispatch($event, MyCustomEvent::class);
    }
}
