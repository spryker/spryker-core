<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Propel\Helper;

use Codeception\Configuration;
use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\PropelFacade;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

class PropelInstallHelper extends Module
{
    /**
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param array|null $config
     */
    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);

        if (!empty($config['enabled'])) {
            $this->initPropel();
        }
    }

    /**
     * @return void
     */
    protected function initPropel()
    {
        $this->copyFromTestBundle();

        $this->getFacade()->cleanPropelSchemaDirectory();
        $this->getFacade()->copySchemaFilesToTargetDirectory();
        $this->getFacade()->createDatabaseIfNotExists();
        $this->getFacade()->convertConfig();

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
        . ' --schema-dir ' . $config['paths']['schemaDir'] . ' --disable-namespace-auto-package';
    }

    /**
     * @return string
     */
    private function getBaseCommand()
    {
        return 'APPLICATION_ENV=' . APPLICATION_ENV
        . ' APPLICATION_STORE=' . APPLICATION_STORE
        . ' APPLICATION_ROOT_DIR=' . APPLICATION_ROOT_DIR
        . ' APPLICATION=' . APPLICATION;
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
            . ' --schema-dir ' . $config['paths']['schemaDir'];

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
            . $this->getConfigDirectoryForCommand($config);

        return $command;
    }

    /**
     * @param string $command
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

        if ($finder->count() === 0) {
            return;
        }

        $pathForSchemas = $this->getTargetSchemaDirectory();
        $filesystem = new Filesystem();

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $path = $pathForSchemas . DIRECTORY_SEPARATOR . $file->getFilename();
            $filesystem->dumpFile($path, $file->getContents());
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
}
