<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Transfer\Helper;

use Codeception\Module;
use Exception;
use Psr\Log\NullLogger;
use Spryker\Zed\Transfer\Business\TransferFacade;
use SprykerTest\Shared\Testify\Helper\ModuleHelperConfigTrait;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class TransferGenerateHelper extends Module
{
    use ModuleHelperConfigTrait;

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
    protected const CONFIG_ENTITY_SCHEMA_DIRECTORIES = 'entitySchemaDirectories';

    /**
     * @var string
     */
    protected const CONFIG_DATA_BUILDER_SCHEMA_DIRECTORIES = 'dataBuilderSchemaDirectories';

    /**
     * @var string
     */
    protected const TRANSFER_SCHEMA_FILENAME_PATTERN = '*.transfer.xml';

    /**
     * @var string
     */
    protected const ENTITY_TRANSFER_SCHEMA_FILENAME_PATTERN = '*.schema.xml';

    /**
     * @var string
     */
    protected const DATA_BUILDER_SCHEMA_FILENAME_PATTERN = '*.databuilder.xml';

    /**
     * @var string
     */
    protected const CONFIG_IS_ISOLATED_MODULE_TEST = 'isolated';

    /**
     * @var string
     */
    protected const DEFAULT_TRANSFER_SCHEMA_TARGET_DIRECTORY = 'src/Spryker/Shared/Testify/Transfer/';

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
    protected function setDefaultConfig(): void
    {
        $this->config = [
            static::CONFIG_SCHEMA_DIRECTORIES => [
                'src/*/Shared/*/Transfer/',
            ],
            static::CONFIG_ENTITY_SCHEMA_DIRECTORIES => [
                'src/Orm/Propel/Schema/',
            ],
            static::CONFIG_DATA_BUILDER_SCHEMA_DIRECTORIES => [
                'tests/_data/',
            ],
            static::CONFIG_IS_ISOLATED_MODULE_TEST => false,
        ];
    }

    /**
     * @return void
     */
    protected function generateTransferObjects(): void
    {
        $transferFacade = $this->getFacade();

        $transferFacade->deleteGeneratedDataTransferObjects();
        $transferFacade->deleteGeneratedDataBuilderObjects();
        $transferFacade->deleteGeneratedEntityTransferObjects();
        $this->copySchemasFromDefinedSchemaDirectories(
            $this->config[static::CONFIG_SCHEMA_DIRECTORIES],
            static::TRANSFER_SCHEMA_FILENAME_PATTERN,
        );
        $this->copySchemasFromDefinedSchemaDirectories(
            $this->config[static::CONFIG_DATA_BUILDER_SCHEMA_DIRECTORIES],
            static::DATA_BUILDER_SCHEMA_FILENAME_PATTERN,
        );
        $transferFacade->generateTransferObjects(new NullLogger());
        $transferFacade->generateDataBuilders(new NullLogger());

        if ($this->hasEntityTransferSchemaDefinitionFiles()) {
            $transferFacade->generateEntityTransferObjects(new NullLogger());
        }
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
     * @param array<string> $schemaDirectoryList
     * @param string $fileNamePattern
     *
     * @return void
     */
    protected function copySchemasFromDefinedSchemaDirectories(array $schemaDirectoryList, string $fileNamePattern): void
    {
        $finder = $this->createSchemaDefinitionFinder($schemaDirectoryList, $fileNamePattern);

        if (!$finder->hasResults()) {
            return;
        }

        $filesystem = new Filesystem();

        foreach ($finder as $file) {
            $filesystem->dumpFile(
                $this->getTargetSchemaDirectory() . $file->getFileName(),
                $file->getContents(),
            );
        }
    }

    /**
     * @param array<string> $schemaDirectories
     * @param string $filenamePattern
     *
     * @return \Symfony\Component\Finder\Finder<\Symfony\Component\Finder\SplFileInfo>
     */
    protected function createSchemaDefinitionFinder(array $schemaDirectories, string $filenamePattern): Finder
    {
        $schemaDirectories = array_map(function (string $schemaDirectory) {
            if (defined('MODULE_UNDER_TEST_ROOT_DIR') && MODULE_UNDER_TEST_ROOT_DIR !== null) {
                return rtrim(MODULE_UNDER_TEST_ROOT_DIR . $schemaDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }

            return rtrim(APPLICATION_ROOT_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $schemaDirectory . DIRECTORY_SEPARATOR;
        }, $schemaDirectories);

        return (new Finder())->files()
            ->in($schemaDirectories)
            ->name($filenamePattern);
    }

    /**
     * @return bool
     */
    protected function hasEntityTransferSchemaDefinitionFiles(): bool
    {
        try {
            $finder = $this->createSchemaDefinitionFinder(
                $this->config[static::CONFIG_ENTITY_SCHEMA_DIRECTORIES],
                static::ENTITY_TRANSFER_SCHEMA_FILENAME_PATTERN,
            );

            return $finder->count() > 0;
        } catch (Exception $e) {
            $this->debug('Note: Entity transfer objects only generated while required schema files are available through usage of PropelHelper before TransferGenerateHelper.');

            return false;
        }
    }

    /**
     * @return string
     */
    protected function getTargetSchemaDirectory(): string
    {
        $targetSchemaDirectory = rtrim(APPLICATION_ROOT_DIR, '/') . DIRECTORY_SEPARATOR . static::DEFAULT_TRANSFER_SCHEMA_TARGET_DIRECTORY;

        if (isset($this->config[static::TARGET_DIRECTORY])) {
            $targetSchemaDirectory = $this->config[static::TARGET_DIRECTORY];
        }

        if (!is_dir($targetSchemaDirectory)) {
            mkdir($targetSchemaDirectory, 0775, true);
        }

        return $targetSchemaDirectory;
    }

    /**
     * @return void
     */
    protected function addAutoloader(): void
    {
        spl_autoload_register(function ($className) {
            if (strrpos($className, 'Transfer') === false && strrpos($className, 'Builder') === false) {
                return false;
            }

            $classNameParts = explode('\\', $className);
            $fileName = implode(DIRECTORY_SEPARATOR, $classNameParts) . '.php';
            $filePath = APPLICATION_SOURCE_DIR . $fileName;

            if (!file_exists($filePath)) {
                return false;
            }

            require_once $filePath;

            return true;
        });
    }
}
