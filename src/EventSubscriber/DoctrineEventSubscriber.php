<?php
// src/EventSubscriber/DoctrineEventSubscriber.php
namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Doctrine\ORM\Events;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\Event\LifecycleEventArgs;

class DoctrineEventSubscriber implements EventSubscriberInterface
{
    private $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    private function sendEmail($message)
    {
        $email = (new Email())
            ->from('noreply@example.com')
            ->to('admin@example.com')
            ->subject('USUARIO PERSISTIDO')
            ->text($message);
        $this->mailer->send($email);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::prePersist => 'processPreInsert',
            Events::postPersist => 'processPostInsert',
            Events::postUpdate => 'processPostUpdate',
            Events::postRemove => 'processPostDelete',
        ];
    }

    public function processPreInsert(ExceptionEvent $event, User $user, PostPersistEventArgs $args): void
    {
        // $this->sendEmail("Se ha hecho un insert del usuario" . $user . " _ " . $event->getThrowable()->getMessage() . " [PostPersist] => " . $args->getObject());
        $this->sendEmail("Se va a hacer un INSERT de User");
    }

    public function processPostInsert(ExceptionEvent $event, User $user, PostPersistEventArgs $args): void
    {
        // $this->sendEmail("Se ha hecho un insert del usuario" . $user . " _ " . $event->getThrowable()->getMessage() . " [PostPersist] => " . $args->getObject());
        $this->sendEmail("Se ha hecho un INSERT del usuario");
    }

    public function processPostUpdate(ExceptionEvent $event, User $user, /*PreUpdateEventArgs*/): void
    {
        // $this->sendEmail("Se ha hecho un update" . $user . " _ " . $event->getThrowable()->getMessage());
        $this->sendEmail("Se ha hecho un UPDATE del usuario");
    }

    public function processPostDelete(LifecycleEventArgs $args): void
    {
        // $this->sendEmail("Se ha eliminado un " . $user . " _ " . $event->getThrowable()->getMessage());
        $this->sendEmail("Se ha hecho un DELETE del usuario");
    }
}