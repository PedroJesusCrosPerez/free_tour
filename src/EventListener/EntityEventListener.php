<?php
// src/EventListener/EntityEventListener.php
namespace App\EventListener;

use App\Event\ReservationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityEventListener implements EventSubscriberInterface
{
    public function __construct(
        private ValidatorInterface $validator,
    ){}

    public static function getSubscribedEvents(): array
    {
        return [
            ReservationEvent::NAME => 'insertReservation',
        ];
    }

    public function insertReservation(ReservationEvent $event): void
    {
        $data = $event->getData();
        $reservation = $data['reservation'];

            // Toggle comment to test the validation 
        // $reservation->setNumberTickets("hola");

        // Validar los datos del formulario
            $errors = $this->validator->validate($reservation);
            if (count($errors) > 0) {
                // Si hay errores de validación, devolver una respuesta con los errores
                dump($errors);
                dd(new JsonResponse(['errors' => (string) $errors], JsonResponse::HTTP_BAD_REQUEST));
            } else {
                dump('¡¡Todo correcto!!');
            }

            // Debugging
        // dd($reservation);
    }
}