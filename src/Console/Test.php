<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/14 15:12
 */

namespace CodeSinging\PinMigration\Console;

use CodeSinging\PinMigration\Foundation\MigrationCommand;
use Phinx\Migration\Manager\Environment;
use Phinx\Util\Util;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use think\console\input\Option;

class Test extends MigrationCommand
{

    const COMMAND_NAME = 'migrate:test';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Verify the configuration file');

        $this->addOption('--environment', '-e', Option::VALUE_REQUIRED, 'The target environment')
            ->setHelp(
                <<<EOT
The <info>test</info> command verifies the YAML configuration file and optionally an environment

<info>phinx test</info>
<info>phinx test -e development</info>

EOT
            );

    }

    /**
     * Handle the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function handle(InputInterface $input, OutputInterface $output)
    {
        $this->loadConfig($input, $output);
        $this->loadManager($input, $output);

        // Verify the migrations path(s)
        array_map(
            [$this, 'verifyMigrationDirectory'],
            Util::globAll($this->getConfig()->getMigrationPaths())
        );

        // Verify the seed path(s)
        array_map(
            [$this, 'verifySeedDirectory'],
            Util::globAll($this->getConfig()->getSeedPaths())
        );

        $envName = $input->getOption('environment');
        if ($envName) {
            if (!$this->getConfig()->hasEnvironment($envName)) {
                throw new \InvalidArgumentException(sprintf(
                    'The environment "%s" does not exist',
                    $envName
                ));
            }

            $output->writeln(sprintf('<info>validating environment</info> %s', $envName));
            $environment = new Environment(
                $envName,
                $this->getConfig()->getEnvironment($envName)
            );
            // validate environment connection
            $environment->getAdapter()->connect();
        }

        $output->writeln('<info>success!</info>');
    }
}