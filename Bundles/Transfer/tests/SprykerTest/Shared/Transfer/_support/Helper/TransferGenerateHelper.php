<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Transfer\Helper;

use Codeception\Module;
use Psr\Log\NullLogger;
use Spryker\Zed\Transfer\Business\TransferFacade;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class TransferGenerateHelper extends Module
{
    /**
     * @var string
     */
    protected const TARGET_DIRECTORY = 'target_directory';

    /**
     * @var string
     */
    protected const CONFIG_SCHEMA_DIRECTORIES = 'schemaDirectories';

    /**
     * @var string
     */
    protected const CONFIG_IS_ISOLATED_MODULE_TEST = 'isolated';

    /**
     * @var array
     */
    protected $config = [
        self::CONFIG_SCHEMA_DIRECTORIES => [
            'src/*/Shared/*/Transfer/',
        ],
        self::CONFIG_IS_ISOLATED_MODULE_TEST => false,
    ];

    /**
     * @param array $settings
     *
     * @return void
     */
    public function _beforeSuite($settings = [])
    {
        if ($this->config[static::CONFIG_IS_ISOLATED_MODULE_TEST]) {
            $this->generateTransferObjects();
            $this->addAutoloader();
        }
    }

    /**
     * @return void
     */
    protected function generateTransferObjects(): void
    {
        $transferFacade = $this->getFacade();

        $this->copySchemasFromDefinedSchemaDirectories();

        $transferFacade->deleteGeneratedTransferObjects();

        $this->debug('Generating Transfer Objects...');
        $transferFacade->generateTransferObjects(new NullLogger());

        $this->debug('Generating DataBuilders...');
        $transferFacade->generateDataBuilders(new NullLogger());
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\TransferFacade
     */
    protected function getFacade(): TransferFacade
    {
        return new TransferFacade();
    }

    /**
     * This will copy all schema files from the schema directories which are defined in the codeception.yml file
     * of the module under test.
     *
     * @example codeception.yml
     *
     * ```
     * env:
     *   isolated: # (environment name which will be used on CLI with `vendor/bin/codecept run --env isolated`)
     *     modules:
     *       config:
     *         \SprykerTest\Shared\Transfer\Helper\TransferGenerateHelper:
     *           schemaDirectories:
     *             - # path to schema files, relative to the module under test.
     *
     * ```
     *
     * @return void
     */
    protected function copySchemasFromDefinedSchemaDirectories(): void
    {
        $finder = $this->createTransferSchemaFinder($this->config[static::CONFIG_SCHEMA_DIRECTORIES]);

        if ($finder->count() > 0) {
            $pathForTransferSchemas = $this->getTargetSchemaDirectory();
            $filesystem = new Filesystem();
            foreach ($finder as $file) {
                $path = $pathForTransferSchemas . 'Transfer' . DIRECTORY_SEPARATOR . $file->getFileName();
                $filesystem->dumpFile($path, $file->getContents());
            }
        }
    }

    /**
     * @param array $schemaDirectories
     *
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function createTransferSchemaFinder(array $schemaDirectories)
    {
        $schemaDirectories = array_map(function (string $schemaDirectory) {
            if (defined('MODULE_UNDER_TEST_ROOT_DIR') && MODULE_UNDER_TEST_ROOT_DIR !== null) {
                return rtrim(MODULE_UNDER_TEST_ROOT_DIR . $schemaDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }

            return rtrim(APPLICATION_ROOT_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $schemaDirectory . DIRECTORY_SEPARATOR;
        }, $schemaDirectories);

        $finder = new Finder();
        $finder->files()->in($schemaDirectories)->name('*.transfer.xml');

        return $finder;
    }

    /**
     * @return string
     */
    protected function getTargetSchemaDirectory(): string
    {
        $pathForTransferSchemas = rtrim(APPLICATION_ROOT_DIR, '/') . '/src/Spryker/Shared/Testify/';

        if (isset($this->config[self::TARGET_DIRECTORY])) {
            $pathForTransferSchemas = $this->config[self::TARGET_DIRECTORY];
        }

        if (!is_dir($pathForTransferSchemas)) {
            mkdir($pathForTransferSchemas, 0775, true);
        }

        return $pathForTransferSchemas;
    }

    /**
     * This will add autoloading for generated Transfer objects in isolated module tests.
     *
     * @return void
     */
    protected function addAutoloader(): void
    {
        spl_autoload_register(function ($className) {
            if (strrpos($className, 'Transfer') === false) {
                return false;
            }

            $classNameParts = explode('\\', $className);

            $transferFileName = implode(DIRECTORY_SEPARATOR, $classNameParts) . '.php';
            $transferFilePath = APPLICATION_ROOT_DIR . 'src' . DIRECTORY_SEPARATOR . $transferFileName;

            if (!file_exists($transferFilePath)) {
                return false;
            }

            require_once $transferFilePath;

            return true;
        });
    }
}
