<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetUserAccountCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $userPasswordHasher,
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:user:reset')
            ->setDescription('Reset a user account')
            ->setHelp('This command allows you to reset a user account.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Prompt for the user's email address
        $email = $io->ask('Enter the email address of the user to reset:');

        // Find the user by email
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $io->error('User not found.');
            return Command::FAILURE;
        }

        // Reset the user account (e.g., set password to a default value)
        // Modify this part according to your specific requirements
            // Prompt for the user's new password
            $newPassword = $io->ask('Enter your new password:');
            // encode the plain password
            $user->setPassword(
                $this->userPasswordHasher->hashPassword($user, $newPassword)
            );
        

        // Persist changes
        $this->entityManager->flush();

        $io->success('User account reset successfully.');

        return Command::SUCCESS;
    }
}
