<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/14 14:43
 */

namespace CodeSinging\PinMigration\Console;

use CodeSinging\PinMigration\Foundation\MigrationCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use think\console\input\Option;

class Reset extends MigrationCommand
{
    const COMMAND_NAME = 'migrate:reset';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Rollback all migrations')
            ->addOption('--environment', '-e', Option::VALUE_REQUIRED, 'The target environment')
            ->addOption('--force', '-f', Option::VALUE_NONE, 'Force rollback to ignore breakpoints')
            ->addOption('--dry-run', '-x', Option::VALUE_NONE, 'Dump query to standard output instead of executing it')
            ->addOption('--fake', null, Option::VALUE_NONE, "Mark any rollbacks selected as run, but don't actually execute them")
            ->setHelp(
                <<<EOT
The <info>reset</info> command reverts all versions

<info>phinx rollback -e development</info>
<info>phinx rollback -e development -v</info>
<info>phinx rollback -e development -t 20111018185412 -f</info>

If you have a breakpoint set, then the rollbacks will stop at the breakpoint.
<info>phinx reset -e development </info>

The <info>version_order</info> configuration option is used to determine the order of the migrations when rolling back.
This can be used to allow the rolling back of the last executed migration instead of the last created one, or combined
with the <info>-d|--date</info> option to rollback to a certain date using the migration start times to order them.

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
        $version = 0;
        $date = null;
        $force = (bool)$input->getOption('force');
        $fake = (bool)$input->getOption('fake');

        $config = $this->getConfig();

        if ($environment === null) {
            $environment = $config->getDefaultEnvironment();
            $output->writeln('<comment>warning</comment> no environment specified, defaulting to: ' . $environment);
        } else {
            $output->writeln('<info>using environment</info> ' . $environment);
        }

        $envOptions = $config->getEnvironment($environment);
        if (isset($envOptions['adapter'])) {
            $output->writeln('<info>using adapter</info> ' . $envOptions['adapter']);
        }

        if (isset($envOptions['wrapper'])) {
            $output->writeln('<info>using wrapper</info> ' . $envOptions['wrapper']);
        }

        if (isset($envOptions['name'])) {
            $output->writeln('<info>using database</info> ' . $envOptions['name']);
        }

        $versionOrder = $this->getConfig()->getVersionOrder();
        $output->writeln('<info>ordering by </info>' . $versionOrder . " time");

        if ($fake) {
            $output->writeln('<comment>warning</comment> performing fake rollbacks');
        }

        // rollback the specified environment
        if ($date === null) {
            $targetMustMatchVersion = true;
            $target = $version;
        } else {
            $targetMustMatchVersion = false;
            $target = $this->getTargetFromDate($date);
        }

        $start = microtime(true);
        $this->getManager()->rollback($environment, $target, $force, $targetMustMatchVersion, $fake);
        $end = microtime(true);

        $output->writeln('');
        $output->writeln('<comment>All Done. Took ' . sprintf('%.4fs', $end - $start) . '</comment>');

    }

    /**
     * Get Target from Date
     *
     * @param string $date The date to convert to a target.
     * @return string The target
     */
    public function getTargetFromDate($date)
    {
        if (!preg_match('/^\d{4,14}$/', $date)) {
            throw new \InvalidArgumentException('Invalid date. Format is YYYY[MM[DD[HH[II[SS]]]]].');
        }

        // what we need to append to the date according to the possible date string lengths
        $dateStrlenToAppend = [
            14 => '',
            12 => '00',
            10 => '0000',
            8 => '000000',
            6 => '01000000',
            4 => '0101000000',
        ];

        if (!isset($dateStrlenToAppend[strlen($date)])) {
            throw new \InvalidArgumentException('Invalid date. Format is YYYY[MM[DD[HH[II[SS]]]]].');
        }

        $target = $date . $dateStrlenToAppend[strlen($date)];

        $dateTime = \DateTime::createFromFormat('YmdHis', $target);

        if ($dateTime === false) {
            throw new \InvalidArgumentException('Invalid date. Format is YYYY[MM[DD[HH[II[SS]]]]].');
        }

        return $dateTime->format('YmdHis');
    }
}