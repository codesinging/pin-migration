<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/14 14:56
 */

namespace CodeSinging\PinMigration\Console;

use CodeSinging\PinMigration\Foundation\MigrationCommand;
use Exception;
use InvalidArgumentException;
use Phinx\Config\NamespaceAwareInterface;
use Phinx\Util\Util;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use think\console\input\Argument;
use think\console\input\Option;

class SeedCreate extends MigrationCommand
{
    const COMMAND_NAME = 'seed:create';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Create a new database seeder')
            ->addArgument('name', Argument::REQUIRED, 'What is the name of the seeder?')
            ->addOption('path', null, Option::VALUE_REQUIRED, 'Specify the path in which to create this seeder')
            ->setHelp(sprintf(
                '%sCreates a new database seeder%s',
                PHP_EOL,
                PHP_EOL
            ));
    }

    /**
     * Handle the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     * @throws Exception
     */
    protected function handle(InputInterface $input, OutputInterface $output)
    {
        $this->bootstrap($input, $output);

        // get the seed path from the config
        $path = $this->getSeedPath($input, $output);

        if (!file_exists($path)) {
            if ($this->output->confirm($this->input, 'Create seeds directory? [y]/n ', true)) {
                mkdir($path, 0755, true);
            }
        }

        $this->verifySeedDirectory($path);

        $path = realpath($path);
        $className = $input->getArgument('name');

        if (!Util::isValidPhinxClassName($className)) {
            throw new InvalidArgumentException(sprintf(
                'The seed class name "%s" is invalid. Please use CamelCase format',
                $className
            ));
        }

        // Compute the file path
        $filePath = $path . DIRECTORY_SEPARATOR . $className . '.php';

        if (is_file($filePath)) {
            throw new InvalidArgumentException(sprintf(
                'The file "%s" already exists',
                basename($filePath)
            ));
        }

        // inject the class names appropriate to this seeder
        $contents = file_get_contents($this->getSeedTemplateFilename());

        $config = $this->getConfig();
        $namespace = $config instanceof NamespaceAwareInterface ? $config->getSeedNamespaceByPath($path) : null;
        $classes = [
            '$namespaceDefinition' => $namespace !== null ? ('namespace ' . $namespace . ';') : '',
            '$namespace' => $namespace,
            '$useClassName' => 'Phinx\Seed\AbstractSeed',
            '$className' => $className,
            '$baseClassName' => 'AbstractSeed',
        ];
        $contents = strtr($contents, $classes);

        if (file_put_contents($filePath, $contents) === false) {
            throw new RuntimeException(sprintf(
                'The file "%s" could not be written to',
                $path
            ));
        }

        $output->writeln('<info>using seed base class</info> ' . $classes['$useClassName']);
        $output->writeln('<info>created</info> .' . str_replace(getcwd(), '', $filePath));

        return 0;
    }

    /**
     * Returns the seed path to create the seeder in.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     * @throws Exception
     */
    protected function getSeedPath(InputInterface $input, OutputInterface $output)
    {
        // First, try the non-interactive option:
        $path = $input->getOption('path');

        if (!empty($path)) {
            return $path;
        }

        $paths = $this->getConfig()->getSeedPaths();

        // No paths? That's a problem.
        if (empty($paths)) {
            throw new Exception('No seed paths set in your Phinx configuration file.');
        }

        foreach ($paths as &$path) {
            $path = app()->getRootPath() . $path;
        }

        // Only one path set, so select that:
        if (1 === count($paths)) {
            return array_shift($paths);
        }

        // Ask the user which of their defined paths they'd like to use:
        return $this->output->choice($this->input, 'Which seeds path would you like to use?', $paths, 0);
    }
}