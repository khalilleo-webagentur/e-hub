<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Service\TokenGeneratorService;
use App\Service\UserService;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// $ php bin/console app:new-admin

#[AsCommand(
    name: 'app:new-admin',
    description: 'Add demo admin for demonstration purposes.',
    hidden: false
)]
class NewAdminCommand extends Command
{
    public const FAILURE = 0;
    public const SUCCESS = 1;

    public function __construct(
        private readonly UserService $userService,
        private readonly TokenGeneratorService $tokenGeneratorService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('running ...');

        $faker = Factory::create();

        $email = $faker->safeEmail();

        if (!$this->userService->getByEmail($email)) {

            $user = new User();

            $code = $this->tokenGeneratorService->randomToken();

            $this->userService->save(
                $user
                    ->setName($faker->name())
                    ->setEmail($email)
                    ->setPassword($this->userService->encodePassword($email))
                    ->setRoles(['ROLE_SUPER_ADMIN'])
                    ->setIsVerified(true)
                    ->setToken($code)
            );

            $output->writeln(sprintf('Admin added. E:: %s and OTP:: %s', $email, $code));

            return self::SUCCESS;
        }

        $output->writeln('Admin cannot be created ...');

        return self::FAILURE;
    }

    protected function configure(): void
    {
        //
    }
}
