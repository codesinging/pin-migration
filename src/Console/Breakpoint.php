<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/14 14:36
 */

namespace CodeSinging\PinMigration\Console;

use CodeSinging\PinMigration\Foundation\MigrationCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use think\console\input\Option;

class Breakpoint extends MigrationCommand
{
    const COMMAND_NAME = 'migrate:breakpoint';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Manage breakpoints')
            ->addOption('--environment', '-e', Option::VALUE_REQUIRED, 'The target environment.')
            ->addOption('--target', '-t', Option::VALUE_REQUIRED, 'The version number to target for the breakpoint')
            ->addOption('--set', '-s', Option::VALUE_NONE, 'Set the breakpoint')
            ->addOption('--unset', '-u', Option::VALUE_NONE, 'Unset the breakpoint')
            ->addOption('--remove-all', '-r', Option::VALUE_NONE, 'Remove all breakpoints')
            ->setHelp(
                <<<EOT
The <info>breakpoint</info> command allows you to toggle, set, or unset a breakpoint against a specific target to inhibit rollbacks beyond a certain target.
If no target is supplied then the most recent migration will be used.
You cannot specify un-migrated targets

<info>phinx breakpoint -e development</info>
<info>phinx breakpoint -e development -t 20110103081132</info>
<info>phinx breakpoint -e development -r</info>
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
     * @throws \Exception
     */
    protected function handle(InputInterface $input, OutputInterface $output)
    {
        $this->bootstrap( $input, $output);

        $environment = $input->getOption('environment');
        $version = $input->getOption('target');
        $removeAll = $input->getOption('remove-all');
        $set = $input->getOption('set');
        $unset = $input->getOption('unset');

        if ($environment === null) {
            $environment = $this->getConfig()->getDefaultEnvironment();
            $output->writeln('<comment>warning</comment> no environment specified, defaulting to: ' . $environment);
        } else {
            $output->writeln('<info>using environment</info> ' . $environment);
        }

        if ($version && $removeAll) {
            throw new \InvalidArgumentException('Cannot toggle a breakpoint and remove all breakpoints at the same time.');
        }

        if (($set && $unset) || ($set && $removeAll) || ($unset && $removeAll)) {
            throw new \InvalidArgumentException('Cannot use more than one of --set, --unset, or --remove-all at the same time.');
        }

        if ($removeAll) {
            // Remove all breakpoints.
            $this->getManager()->removeBreakpoints($environment);
        } elseif ($set) {
            // Set the breakpoint.
            $this->getManager()->setBreakpoint($environment, $version);
        } elseif ($unset) {
            // Unset the breakpoint.
            $this->getManager()->unsetBreakpoint($environment, $version);
        } else {
            // Toggle the breakpoint.
            $this->getManager()->toggleBreakpoint($environment, $version);
        }
    }
}