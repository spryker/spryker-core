<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Propel\Helper;

use Codeception\Module;
use Spryker\Zed\Propel\Business\PropelFacade;
use Spryker\Zed\Propel\Communication\Console\BuildModelConsole;
use Spryker\Zed\Propel\Communication\Console\DiffConsole;
use Spryker\Zed\Propel\Communication\Console\MigrateConsole;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class PropelHelper extends Module
{
    use ConfigHelperTrait;
    use BusinessHelperTrait;

    /**
     * @var string
     */
    protected const CONFIG_SCHEMA_SOURCE_DIRECTORY_LIST = 'schemaSourceDirectoryList';

    /**
     * @var string
     */
    protected const CONFIG_SCHEMA_TARGET_DIRECTORY = 'schemaTargetDirectory';

    /**
     * @var string
     */
    protected const CONFIG_IS_ISOLATED_MODULE_TEST = 'isolated';

    /**
     * @var string
     */
    protected const PROPEL_MODULE_NAME = 'Propel';

    /**
     * @var string
     */
    protected const SCHEMA_FILE_PATTERN = '*.schema.xml';

    /**
     * @var string
     */
    protected const SCHEMA_TARGET_DIRECTORY_DEFAULT = 'src' . DIRECTORY_SEPARATOR . 'Orm' . DIRECTORY_SEPARATOR . 'Propel' . DIRECTORY_SEPARATOR . 'Schema' . DIRECTORY_SEPARATOR;

    /**
     * @var string
     */
    protected const SCHEMA_SOURCE_DIRECTORY_DEFAULT = 'src' . DIRECTORY_SEPARATOR . 'Spryker' . DIRECTORY_SEPARATOR . 'Zed' . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . 'Persistence' . DIRECTORY_SEPARATOR . 'Propel' . DIRECTORY_SEPARATOR . 'Schema' . DIRECTORY_SEPARATOR;

    /**
     * @var array
     */
    protected $config = [
        self::CONFIG_SCHEMA_SOURCE_DIRECTORY_LIST => [
            self::SCHEMA_SOURCE_DIRECTORY_DEFAULT,
        ],
        self::CONFIG_IS_ISOLATED_MODULE_TEST => false,
    ];

    /**
     * @param array $settings
     *
     * @return void
     */
    public function _beforeSuite($settings = []): void
    {
        if ($this->config[static::CONFIG_IS_ISOLATED_MODULE_TEST]) {
            $this->mockPropelSchemaPathPatterns();
            $this->getFacade()->cleanPropelSchemaDirectory();
            $this->copyPropelSchemasFromDefinedSchemaDirectoryList();
            $this->getFacade()->copySchemaFilesToTargetDirectory();
            $this->runCommands();
        }
    }

    /**
     * @return void
     */
    protected function runCommands(): void
    {
        $application = $this->createApplication();
        $output = new ConsoleOutput();

        foreach ($this->getCommands() as $command) {
            $input = new ArrayInput([
                'command' => $command->getName(),
            ]);

            $application->doRun($input, $output);
        }
    }

    /**
     * @return \Symfony\Component\Console\Application
     */
    protected function createApplication(): Application
    {
        $application = new Application();
        $commands = $this->getCommands();

        $application->addCommands($commands);

        return $application;
    }

    /**
     * @return array<\Spryker\Zed\Kernel\Communication\Console\Console>
     */
    protected function getCommands(): array
    {
        return [
            new DiffConsole(),
            new MigrateConsole(),
            new BuildModelConsole(),
        ];
    }

    /**
     * @return void
     */
    protected function mockPropelSchemaPathPatterns(): void
    {
        $this->getConfigHelper()->mockConfigMethod(
            'getPropelSchemaPathPatterns',
            $this->getPropelSchemaPathPatterns(),
            static::PROPEL_MODULE_NAME,
        );
    }

    /**
     * @return array<array-key, string>
     */
    protected function getPropelSchemaPathPatterns(): array
    {
        return array_map(function ($schemaPathPattern) {
            return APPLICATION_ROOT_DIR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $schemaPathPattern;
        }, $this->config[static::CONFIG_SCHEMA_SOURCE_DIRECTORY_LIST]);
    }

    /**
     * @return \Spryker\Zed\Propel\Business\PropelFacade
     */
    protected function getFacade(): PropelFacade
    {
        return $this->getBusinessHelper()->getFacade(static::PROPEL_MODULE_NAME);
    }

    /**
     * This will copy all propel schema files from defined schema directories in the codeception.yml file
     * of the module under test.
     *
     * @example codeception.yml
     *
     * ```
     * env:
     *   isolated: # (environment name which will be used on CLI with `vendor/bin/codecept run --env isolated`)
     *     modules:
     *       config:
     *         \SprykerTest\Shared\Propel\Helper\PropelHelper:
     *           schemaDirectories:
     *             - # path to schema files, relative to the module under test.
     * ```
     *
     * @return void
     */
    protected function copyPropelSchemasFromDefinedSchemaDirectoryList(): void
    {
        $finder = $this->createPropelSchemaFinder($this->config[static::CONFIG_SCHEMA_SOURCE_DIRECTORY_LIST]);

        if ($finder->count() > 0) {
            $schemaTargetDirectory = $this->getSchemaTargetDirectory();
            $filesystem = new Filesystem();

            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            foreach ($finder as $file) {
                $path = $schemaTargetDirectory . DIRECTORY_SEPARATOR . $file->getFilename();
                $filesystem->dumpFile($path, $file->getContents());
            }
        }
    }

    /**
     * @param array $schemaSourceDirectoryList
     *
     * @return \Symfony\Component\Finder\Finder
     */
    protected function createPropelSchemaFinder(array $schemaSourceDirectoryList): Finder
    {
        $schemaSourceDirectoryList = array_map(function (string $schemaSourceDirectory) {
            if (defined('MODULE_UNDER_TEST_ROOT_DIR') && MODULE_UNDER_TEST_ROOT_DIR !== null) {
                return rtrim(MODULE_UNDER_TEST_ROOT_DIR . $schemaSourceDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }

            return rtrim(APPLICATION_ROOT_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $schemaSourceDirectory . DIRECTORY_SEPARATOR;
        }, $schemaSourceDirectoryList);

        $finder = new Finder();

        $finder->files()->in($schemaSourceDirectoryList)->name(static::SCHEMA_FILE_PATTERN);

        return $finder;
    }

    /**
     * Getting path to where the files from the bundle to test should be copied to ("virtual project").
     *
     * @return string
     */
    protected function getSchemaTargetDirectory(): string
    {
        $schemaTargetDirectory = $this->getSchemaTargetDirectoryDefault();

        if ($this->hasSchemaTargetDirectoryConfigured()) {
            $schemaTargetDirectory = $this->config[static::CONFIG_SCHEMA_TARGET_DIRECTORY];
        }

        $this->createSchemaTargetDirectoryIfNotExists($schemaTargetDirectory);

        return $schemaTargetDirectory;
    }

    /**
     * Getting default path to where the files from module to test should be copied to ("virtual project").
     *
     * @return string
     */
    protected function getSchemaTargetDirectoryDefault(): string
    {
        return APPLICATION_ROOT_DIR . static::SCHEMA_TARGET_DIRECTORY_DEFAULT;
    }

    /**
     * Checking whether default path is overwritten by configuration value.
     *
     * @return bool
     */
    protected function hasSchemaTargetDirectoryConfigured(): bool
    {
        return isset($this->config[static::CONFIG_SCHEMA_TARGET_DIRECTORY]);
    }

    /**
     * Checking whether target schema directory exits otherwise creating it.
     *
     * @param string $schemaTargetDirectory
     *
     * @return void
     */
    protected function createSchemaTargetDirectoryIfNotExists(string $schemaTargetDirectory): void
    {
        if (!is_dir($schemaTargetDirectory)) {
            mkdir($schemaTargetDirectory, 0775, true);
        }
    }
}
