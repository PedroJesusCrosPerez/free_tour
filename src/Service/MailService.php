<?php
// src/Service/MailService.php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailService
{
    public function __construct(
        private LoggerInterface $logger,
        private string $adminEmail,
        private MailerInterface $mailer,
        private Environment $twig,
    ) {}


    public function sendMail(string $destinatary, string $subject, string $body): bool
    {
        $email = (new Email())
            ->from('pedro@freetour.es')
            ->to($destinatary)
            ->subject($subject)
            ->text('{$body}')
            ->html($this->twig->render('mailer/emailtemplate.html.twig', [
                'mytext' => 'texto personalizado escrito fuera de la plantilla',
            ]))
        ;

        return $this->mailer->send($email) != null ? true : false;
    }


    public function confirmReservation(string $destinatary, string $subject): bool
    {
        $email = (new Email())
            ->from($this->adminEmail)
            ->to($destinatary)
            ->subject($subject)
            ->html($this->twig->render('mailer/reservation-done.html.twig', [
                'mytext' => '__texto personalizado escrito fuera de la plantilla__',
            ]))
        ;
        return $this->mailer->send($email) != null ? true : false;
    }

    // public function getHappyMessage(): string
    // {
    //     $this->logger->info('About to find a happy message!');
    //     $messages = [
    //         'You did it! You updated the system! Amazing!',
    //         'That was one of the coolest updates I\'ve seen all day!',
    //         'Great work! Keep going!',
    //     ];

    //     $index = array_rand($messages);

    //     return $messages[$index];
    // }

    public function notifyOfSiteUpdate(string $correo = "@null.es"): bool
    {
        $email = (new Email())
            ->from('pedro@freetour.es')
            // ->to($this->adminEmail)
            ->to($correo)
            ->subject('SERVICIO subject')
            ->text('SERVICIO text')
            ->html('<p>SERVICIO html</p>')
        ;

        return $this->mailer->send($email) != null ? true : false;
    }
}