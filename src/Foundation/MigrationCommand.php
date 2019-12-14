<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/13 22:30
 */

namespace CodeSinging\PinMigration\Foundation;

use CodeSinging\PinMigration\Support\GetDbConfig;
use Phinx\Config\Config;
use Phinx\Config\ConfigInterface;
use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\Manager;
use Phinx\Util\Util;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Config as AppConfig;

abstract class MigrationCommand extends Command
{
    use GetDbConfig;

    /**
     * The location of the default migration template.
     */
    const DEFAULT_MIGRATION_TEMPLATE = '/../../templates/Migration.template.php.dist';

    /**
     * The location of the default seed template.
     */
    const DEFAULT_SEED_TEMPLATE = '/../../templates/Seed.template.php.dist';

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var MigrationInput
     */
    protected $migrateInput;

    /**
     * @var MigrationOutput
     */
    protected $migrateOutput;

    /**
     * @var string
     */
    protected $defaultDatabase = 'default';

    /**
     * Bootstrap Phinx.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    protected function bootstrap(InputInterface $input, OutputInterface $output)
    {
        if (!$this->getConfig()) {
            $this->loadConfig($input, $output);
        }

        $this->loadManager($input, $output);

        if ($bootstrap = $this->getConfig()->getBootstrapFile()) {
            $output->writeln('<info>using bootstrap</info> .' . str_replace(getcwd(), '', realpath($bootstrap)) . ' ');
            Util::loadPhpFile($bootstrap);
        }

        // report the paths
        $paths = $this->getConfig()->getMigrationPaths();

        $output->writeln('<info>using migration paths</info> ');

        foreach (Util::globAll($paths) as $path) {
            $output->writeln('<info> - ' . realpath($path) . '</info>');
        }

        try {
            $paths = $this->getConfig()->getSeedPaths();

            $output->writeln('<info>using seed paths</info> ');

            foreach (Util::globAll($paths) as $path) {
                $output->writeln('<info> - ' . realpath($path) . '</info>');
            }
        } catch (\UnexpectedValueException $e) {
            // do nothing as seeds are optional
        }

    }

    /**
     * Set the config.
     *
     * @param ConfigInterface $config
     *
     * @return $this
     */
    protected function setConfig(ConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Parse the config file and load it into the config object.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function loadConfig(InputInterface $input, OutputInterface $output)
    {
        $this->config = new Config(AppConfig::pull('migration'));

        $environments = $this->config['environments'];
        $environments['default_database'] = $this->defaultDatabase;
        $environments[$this->defaultDatabase] = $this->dbConfig();

        $this->config['environments'] = $environments;
    }

    /**
     * @return Config
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * Set the database adapter.
     *
     * @param AdapterInterface $adapter
     *
     * @return $this
     */
    protected function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * Get the database adapter.
     * @return AdapterInterface
     */
    protected function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Execute the commands.
     *
     * @param Input  $input
     * @param Output $output
     *
     * @return int|mixed|null
     */
    protected function execute(Input $input, Output $output)
    {
        $this->migrateInput = new MigrationInput($input);
        $this->migrateOutput = new MigrationOutput($output);

        return $this->handle($this->migrateInput, $this->migrateOutput);
    }

    /**
     * Handle the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    abstract protected function handle(InputInterface $input, OutputInterface $output);

    /**
     * Load the migrations manager and inject the config.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function loadManager(InputInterface $input, OutputInterface $output)
    {
        if ($this->getManager() === null) {
            $this->setManager(new Manager($this->getConfig(), $input, $output));
        } else {
            $this->manager->setInput($input);
            $this->manager->setOutput($output);
        }
    }

    /**
     * Set the migration manager.
     *
     * @param Manager $manager
     *
     * @return $this
     */
    protected function setManager(Manager $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Manager
     */
    protected function getManager()
    {
        return $this->manager;
    }

    /**
     * Verify that the migration directory exists and is writable.
     *
     * @param string $path
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function verifyMigrationDirectory($path)
    {
        if (!is_dir($path)) {
            throw new \InvalidArgumentException(sprintf(
                'Migration directory "%s" does not exist',
                $path
            ));
        }

        if (!is_writable($path)) {
            throw new \InvalidArgumentException(sprintf(
                'Migration directory "%s" is not writable',
                $path
            ));
        }
    }

    /**
     * Verify that the seed directory exists and is writable.
     *
     * @param string $path
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function verifySeedDirectory($path)
    {
        if (!is_dir($path)) {
            throw new \InvalidArgumentException(sprintf(
                'Seed directory "%s" does not exist',
                $path
            ));
        }

        if (!is_writable($path)) {
            throw new \InvalidArgumentException(sprintf(
                'Seed directory "%s" is not writable',
                $path
            ));
        }
    }

    /**
     * Returns the migration template filename.
     *
     * @return string
     */
    protected function getMigrationTemplateFilename()
    {
        return __DIR__ . self::DEFAULT_MIGRATION_TEMPLATE;
    }

    /**
     * Returns the seed template filename.
     *
     * @return string
     */
    protected function getSeedTemplateFilename()
    {
        return __DIR__ . self::DEFAULT_SEED_TEMPLATE;
    }
}