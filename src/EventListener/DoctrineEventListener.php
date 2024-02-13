<?php
// src/EventListener/DoctrineEventListener.php
namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class DoctrineEventListener implements EventSubscriberInterface
{
    private $mailer;
    public function __construct(MailerInterface $mailer) { $this->mailer = $mailer; }
    private function sendEmail($message)
    {
        $email = (new Email())
            ->from('noreply@example.com')
            ->to('admin@example.com')
            ->subject('ENVIAR CORREO')
            ->text($message);
        $this->mailer->send($email);
    }
    
    public static function getSubscribedEvents(): array
    {
        return [
            // Events::prePersist => 'processPreInsert',
            // Events::postPersist => 'processPostInsert',
            // Events::postUpdate => 'processPostUpdate',
            // Events::postRemove => 'processPostDelete',
            Events::prePersist,
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ];
    }

    public function prePersist(): void
    {
        // $this->sendEmail("Se ha hecho un insert del usuario" . $user . " _ " . $event->getThrowable()->getMessage() . " [PostPersist] => " . $args->getObject());
        $this->sendEmail("Se va a hacer un INSERT de User");
    }

    public function postPersist(): void
    {
        // $this->sendEmail("Se ha hecho un insert del usuario" . $user . " _ " . $event->getThrowable()->getMessage() . " [PostPersist] => " . $args->getObject());
        $this->sendEmail("Se ha hecho un INSERT del usuario");
    }

    public function postUpdate(ExceptionEvent $event, User $user, /*PreUpdateEventArgs*/): void
    {
        // $this->sendEmail("Se ha hecho un update" . $user . " _ " . $event->getThrowable()->getMessage());
        $this->sendEmail("Se ha hecho un UPDATE del usuario");
    }

    public function postRemove(ExceptionEvent $event, User $user, PostPersistEventArgs $args): void
    {
        // $this->sendEmail("Se ha eliminado un " . $user . " _ " . $event->getThrowable()->getMessage());
        $this->sendEmail("Se ha hecho un DELETE del usuario");
    }

    // public function prePersist(ExceptionEvent $event, User $user, PrePersistEventArgs,PostPersistEventArgs,PreUpdateEventArgs $args): void
    // {
    //     // $this->sendEmail("Se ha hecho un insert del usuario" . $user . " _ " . $event->getThrowable()->getMessage() . " [PostPersist] => " . $args->getObject());
    //     $this->sendEmail("Se va a hacer un INSERT de User");
    // }
}