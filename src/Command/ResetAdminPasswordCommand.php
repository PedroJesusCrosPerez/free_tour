<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetAdminPasswordCommand extends Command
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
            ->setName('reset:admin:password')
            ->setDescription("Reset a admin's password")
            ->setHelp('This command allows you to reset admin password account.')
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'If set, the command will reset the password without asking for confirmation.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Check if the --force option is set
            $force = $input->getOption('force');

        if (!$force) {
            $io->warning('This operation will reset the admin password.');
            if (!$io->confirm('Do you want to continue?')) {
                $io->success('Operation canceled.');
                return Command::SUCCESS;
            }

            // Find the admin
                $admin = $this->entityManager->getRepository(User::class)->find(2);
                if (!$admin && !$admin->getRoles() == ['ROLE_ADMIN']) {
                    $io->error('Admin not found.');
                    return Command::FAILURE;
                }
        }

        // Prompt for the admin's password address
            $password = $io->ask("Enter the previus admin's password:");

        // Compare both passwords, gived than admin's password
            if (!$this->userPasswordHasher->isPasswordValid($admin, $password)) {
                $io->error('Invalid password.');
                return Command::FAILURE;
            }

        // Prompt, Encode and Save new password
            // Prompt for the user's new password
                $newPassword = $io->ask("Enter admin's new password:");
            // Encode and Save the plain password
                $admin->setPassword(
                    $this->userPasswordHasher->hashPassword($admin, $newPassword)
                );

        // Persist changes
            $this->entityManager->flush();

        $io->success("Admin's password reset successfully.");

        return Command::SUCCESS;
    }
}

/*

$admin = $this->entityManager->getRepository(User::class)->findByRoles(['ROLE_ADMIN']);
foreach ($admin as $key => $admin) {
    if (!$admin && !$admin->getRoles() == ['ROLE_ADMIN']) {
        unset($admins[$key]);
    }
}

foreach ($admin as $key => $admin) {
    if (!$admin && !$admin->getRoles() == ['ROLE_ADMIN']) {
        $io->error('Admin not found.');
        return Command::FAILURE;
    }
}

*/
