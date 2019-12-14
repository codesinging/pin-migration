<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/14 15:08
 */

namespace CodeSinging\PinMigration\Console;

use CodeSinging\PinMigration\Foundation\MigrationCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use think\console\input\Option;

class Status extends MigrationCommand
{
    const COMMAND_NAME = 'migrate:status';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Show migration status');

        $this->addOption('--environment', '-e', Option::VALUE_REQUIRED, 'The target environment.')
            ->addOption('--format', '-f', Option::VALUE_REQUIRED, 'The output format: text or json. Defaults to text.')
            ->setHelp(
                <<<EOT
The <info>status</info> command prints a list of all migrations, along with their current status

<info>phinx status -e development</info>
<info>phinx status -e development -f json</info>

The <info>version_order</info> configuration option is used to determine the order of the status migrations.
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

        $environment = $input->getOption('environment');
        $format = $input->getOption('format');

        if ($environment === null) {
            $environment = $this->getConfig()->getDefaultEnvironment();
            $output->writeln('<comment>warning</comment> no environment specified, defaulting to: ' . $environment);
        } else {
            $output->writeln('<info>using environment</info> ' . $environment);
        }
        if ($format !== null) {
            $output->writeln('<info>using format</info> ' . $format);
        }

        $output->writeln('<info>ordering by </info>' . $this->getConfig()->getVersionOrder() . " time");

        // print the status
        return $this->getManager()->printStatus($environment, $format);
    }
}