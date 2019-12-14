<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/14 15:04
 */

namespace CodeSinging\PinMigration\Console;

use CodeSinging\PinMigration\Foundation\MigrationCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use think\console\input\Option;

class SeedRun extends MigrationCommand
{
    const COMMAND_NAME = 'seed:run';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Run database seeders')
            ->addOption('--environment', '-e', Option::VALUE_REQUIRED, 'The target environment')
            ->addOption('--seed', '-s', Option::VALUE_REQUIRED | Option::VALUE_IS_ARRAY, 'What is the name of the seeder?')
            ->setHelp(
                <<<EOT
The <info>seed:run</info> command runs all available or individual seeders

<info>phinx seed:run -e development</info>
<info>phinx seed:run -e development -s UserSeeder</info>
<info>phinx seed:run -e development -s UserSeeder -s PermissionSeeder -s LogSeeder</info>
<info>phinx seed:run -e development -v</info>

EOT
            );
    }

    /**
     * Handle the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     * @throws \Exception
     */
    protected function handle(InputInterface $input, OutputInterface $output)
    {
        $this->bootstrap($input, $output);

        $seedSet = $input->getOption('seed');
        $environment = $input->getOption('environment');

        if ($environment === null) {
            $environment = $this->getConfig()->getDefaultEnvironment();
            $output->writeln('<comment>warning</comment> no environment specified, defaulting to: ' . $environment);
        } else {
            $output->writeln('<info>using environment</info> ' . $environment);
        }

        $envOptions = $this->getConfig()->getEnvironment($environment);
        if (isset($envOptions['adapter'])) {
            $output->writeln('<info>using adapter</info> ' . $envOptions['adapter']);
        }

        if (isset($envOptions['wrapper'])) {
            $output->writeln('<info>using wrapper</info> ' . $envOptions['wrapper']);
        }

        if (isset($envOptions['name'])) {
            $output->writeln('<info>using database</info> ' . $envOptions['name']);
        } else {
            $output->writeln('<error>Could not determine database name! Please specify a database name in your config file.</error>');

            return;
        }

        if (isset($envOptions['table_prefix'])) {
            $output->writeln('<info>using table prefix</info> ' . $envOptions['table_prefix']);
        }
        if (isset($envOptions['table_suffix'])) {
            $output->writeln('<info>using table suffix</info> ' . $envOptions['table_suffix']);
        }

        $start = microtime(true);

        if (empty($seedSet)) {
            // run all the seed(ers)
            $this->getManager()->seed($environment);
        } else {
            // run seed(ers) specified in a comma-separated list of classes
            foreach ($seedSet as $seed) {
                $this->getManager()->seed($environment, trim($seed));
            }
        }

        $end = microtime(true);

        $output->writeln('');
        $output->writeln('<comment>All Done. Took ' . sprintf('%.4fs', $end - $start) . '</comment>');
    }
}