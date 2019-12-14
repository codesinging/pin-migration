<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/13 22:37
 */

namespace CodeSinging\PinMigration\Console;

use CodeSinging\PinMigration\Foundation\MigrationCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\facade\Config;

class Init extends MigrationCommand
{
    const COMMAND_NAME = 'migrate:init';

    const CONFIG_NAME = 'migration.php';

    const CONFIG_TEMPLATE = '/../../config/migration.php';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Initialize migration configuration');
    }

    /**
     * Handle the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws \Exception
     */
    protected function handle(InputInterface $input, OutputInterface $output)
    {
        $configPath = app()->getConfigPath().self::CONFIG_NAME;
        $configTemplate = __DIR__.self::CONFIG_TEMPLATE;

        if (copy($configTemplate, $configPath)){
            $output->writeln("<info>Migration config created</info>: {$configPath}");
        } else {
            $output->writeln("<error>Failed to create migration config:</error>: {$configPath}");
        }
    }
}