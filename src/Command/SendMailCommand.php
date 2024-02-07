<?php
namespace App\Command;

use App\Service\MessageGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
#[AsCommand(
    name: 'app:send-mail',
    description: 'Manda un correo.',
)]
class SendMailCommand extends Command
{
    public function __construct(
        private MessageGenerator $messageGenerator,
    ){
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('addressee', InputArgument::OPTIONAL, 'Addressee')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Shall I yell?')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            '',
            '==============================',
            'Comando para enviar un correo',
            '==============================',
        ]);

        // $io = new SymfonyStyle($input, $output);
        // $addressee = $input->getArgument('addressee') ?: 'defaultmail@pedrofreetour.es';

        // $this->messageGenerator->notifyOfSiteUpdate($addressee);
        // $message = "sprintf('Hey %s!', $addressee)";

        // Segunda parte
        $io = new SymfonyStyle($input, $output);
        $email = $io->ask('<fg=magenta>Por favor, introduce un correo electrónico</>');
    
        $this->messageGenerator->notifyOfSiteUpdate($email);
        $message = sprintf('Te hemos enviado un mail a %s!', $email);
    
        $io->success($message);
        return Command::SUCCESS;
    }
}