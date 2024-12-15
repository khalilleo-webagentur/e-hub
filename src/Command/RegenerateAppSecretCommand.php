<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// $ php bin/console app:regenerate-app-secret

#[AsCommand(
    name: 'app:regenerate-app-secret',
    description: 'Generate a new APP_SECRET',
    hidden: false
)]
class RegenerateAppSecretCommand extends Command
{
    public const FAILURE = 0;
    public const SUCCESS = 1;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $a = '0123456789abcdef';
        $secret = '';
        for ($i = 0; $i < 32; $i++) {
            $secret .= $a[rand(0, 15)];
        }

        shell_exec('sed -i -E "s/^APP_SECRET=.{32}$/APP_SECRET=' . $secret . '/" .env');

        $output->writeln('New APP_SECRET was generated: ' . $secret);

        return 0;
    }

    protected function configure(): void
    {
        //
    }
}
