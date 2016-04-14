<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Propel\Module;

use Codeception\Configuration;
use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\PropelFacade;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

class PropelInstall extends Module
{

    /**
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param null $config
     */
    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);

        if (!empty($config['enabled'])) {
            $this->initPropel();
        }
    }

    /**
     * @throws \Exception
     *
     * @return int
     */
    protected function initPropel()
    {
        $this->copyFromTestBundle();

        $this->getFacade()->cleanPropelSchemaDirectory();
        $this->getFacade()->copySchemaFilesToTargetDirectory();
        $this->getFacade()->createDatabaseIfNotExists();

        $this->convertConfig();
        $this->runCommands();
    }

    /**
     * @return void
     */
    private function runCommands()
    {
        foreach ($this->getCommands() as $command) {
            $this->runCommand($command);
        }
    }

    /**
     * @return array
     */
    private function getCommands()
    {
        return [
            $this->createDiffCommand(),
            $this->createMigrateCommand(),
            $this->getModelBuildCommand(),
        ];
    }

    /**
     * @return string
     */
    private function getModelBuildCommand()
    {
        $config = Config::get(PropelConstants::PROPEL);
        return $this->getBaseCommand()
            . ' vendor/bin/propel model:build'
            . $this->getConfigDirectoryForCommand($config)
            . ' --schema-dir ' . $config['paths']['schemaDir'] . ' --disable-namespace-auto-package'
        ;
    }

    /**
     * @return string
     */
    private function getBaseCommand()
    {
        return 'APPLICATION_ENV=' . APPLICATION_ENV
            . ' APPLICATION_STORE=' . APPLICATION_STORE
            . ' APPLICATION_ROOT_DIR=' . APPLICATION_ROOT_DIR
            . ' APPLICATION=' . APPLICATION
        ;
    }

    /**
     * @param array $config
     *
     * @return string
     */
    private function getConfigDirectoryForCommand(array $config)
    {
        return ' --config-dir ' . $config['paths']['phpConfDir'];
    }

    /**
     * @return array
     */
    private function createDiffCommand()
    {
        $config = Config::get(PropelConstants::PROPEL);
        $command = $this->getBaseCommand()
            . ' vendor/bin/propel diff'
            . $this->getConfigDirectoryForCommand($config)
            . ' --schema-dir ' . $config['paths']['schemaDir']
        ;

        return $command;
    }

    /**
     * @return string
     */
    private function createMigrateCommand()
    {
        $config = Config::get(PropelConstants::PROPEL);
        $command = $this->getBaseCommand()
            . ' vendor/bin/propel migrate'
            . $this->getConfigDirectoryForCommand($config)
        ;

        return $command;
    }

    /**
     * @param $command
     *
     * @return void
     */
    protected function runCommand($command)
    {
        $process = new Process($command, Configuration::projectDir());
        $process->setTimeout(600);
        $process->mustRun(function ($type, $buffer) use ($command) {
            if (Process::ERR === $type) {
                echo $command . ' Failed:' . PHP_EOL;
                echo $buffer;
            }
        });
    }

    /**
     * @return \Spryker\Zed\Propel\Business\PropelFacade
     */
    private function getFacade()
    {
        return new PropelFacade();
    }

    /**
     * Copy schema files from bundle to test into "virtual project"
     *
     * @return void
     */
    private function copyFromTestBundle()
    {
        $testBundleSchemaDirectory = Configuration::projectDir() . 'src/Spryker/Zed/*/Persistence/Propel/Schema';
        if (count(glob($testBundleSchemaDirectory)) === 0) {
            return;
        }

        $finder = $this->getBundleSchemaFinder($testBundleSchemaDirectory);

        if ($finder->count() > 0) {
            $pathForSchemas = $this->getTargetSchemaDirectory();
            $filesystem = new Filesystem();
            foreach ($finder as $file) {
                $path = $pathForSchemas . DIRECTORY_SEPARATOR . $file->getFileName();
                $filesystem->dumpFile($path, $file->getContents());
            }
        }
    }

    /**
     * @param string $testBundleSchemaDirectory
     *
     * @return \Symfony\Component\Finder\Finder
     */
    private function getBundleSchemaFinder($testBundleSchemaDirectory)
    {
        $finder = new Finder();
        $finder->files()->in($testBundleSchemaDirectory)->name('*.schema.xml');

        return $finder;
    }

    /**
     * Path to where the files from the bundle to test should be copied ("virtual project")
     *
     * @return string
     */
    private function getTargetSchemaDirectory()
    {
        $pathForSchemas = APPLICATION_ROOT_DIR . '/src/Spryker/Zed/Testify/Persistence/Propel/Schema';
        $this->createTargetSchemaDirectoryIfNotExists($pathForSchemas);

        return $pathForSchemas;
    }

    /**
     * @param string $pathForSchemas
     *
     * @return void
     */
    private function createTargetSchemaDirectoryIfNotExists($pathForSchemas)
    {
        if (!is_dir($pathForSchemas)) {
            mkdir($pathForSchemas, 0775, true);
        }
    }

    /**
     * @return void
     */
    private function convertConfig()
    {
        $config = [
            'propel' => Config::get(PropelConstants::PROPEL),
        ];

        $dsn = Config::get(PropelConstants::ZED_DB_ENGINE) . ':host=' . Config::get(PropelConstants::ZED_DB_HOST)
            . ';dbname=' . Config::get(PropelConstants::ZED_DB_DATABASE);

        $config['propel']['database']['connections']['default']['dsn'] = $dsn;
        $config['propel']['database']['connections']['default']['user'] = Config::get(PropelConstants::ZED_DB_USERNAME);
        $config['propel']['database']['connections']['default']['password'] = Config::get(PropelConstants::ZED_DB_PASSWORD);

        $config['propel']['database']['connections']['zed'] = $config['propel']['database']['connections']['default'];

        $json = json_encode($config, JSON_PRETTY_PRINT);

        $fileName = $config['propel']['paths']['phpConfDir']
            . DIRECTORY_SEPARATOR
            . 'propel.json';

        if (!is_dir(dirname($fileName))) {
            mkdir(dirname($fileName), 0775, true);
        }

        file_put_contents($fileName, $json);
    }

}
