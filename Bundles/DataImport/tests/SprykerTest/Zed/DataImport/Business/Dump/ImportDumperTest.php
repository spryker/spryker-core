<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Dump;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterCollection;
use Spryker\Zed\DataImport\Business\Model\Dump\ImporterDumper;
use Spryker\Zed\DataImport\Communication\Console\Mapper\DataImportConfigurationMapper;
use Spryker\Zed\DataImport\Communication\Console\Parser\DataImportConfigurationYamlParser;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilDataReaderServiceBridge;
use SprykerTest\Zed\DataImport\Communication\Plugin\DataImportStubPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Dump
 * @group ImportDumperTest
 * Add your own group annotations below this line
 */
class ImportDumperTest extends Unit
{
    protected const IMPORT_CONFIG_FILE_NAME = 'import_dummy.yml';

    /**
     * @var \SprykerTest\Zed\DataImport\DataImportBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDumpByImportConfigurationReturnsListOfAppliedImporters(): void
    {
        // Arrange
        $dataImportConfigurationParser = new DataImportConfigurationYamlParser(
            new DataImportToUtilDataReaderServiceBridge($this->tester->getLocator()->utilDataReader()->service()),
            new DataImportConfigurationMapper()
        );

        $configFilePath = Configuration::dataDir() . static::IMPORT_CONFIG_FILE_NAME;
        $dataImportConfigurationTransfer = $dataImportConfigurationParser->parseConfigurationFile($configFilePath);
        $factory = $this->createMock(DataImportBusinessFactory::class);

        $importDumper = new ImporterDumper(
            new DataImporterCollection(),
            $factory,
            [new DataImportStubPlugin()]
        );

        // Act
        $dumpedImportersList = $importDumper->getImportersDumpByConfiguration($dataImportConfigurationTransfer);

        // Assert
        $this->assertIsArray($dumpedImportersList);
        $this->assertNotEmpty($dumpedImportersList);
        $this->assertArrayHasKey('dummy', $dumpedImportersList);
        $this->assertSame(DataImportStubPlugin::class, $dumpedImportersList['dummy']);
    }

    /**
     * @return void
     */
    public function testDumpByImportConfigurationReturnsEmptyListIfNoImporterFound(): void
    {
        // Arrange
        $dataImportConfigurationParser = new DataImportConfigurationYamlParser(
            new DataImportToUtilDataReaderServiceBridge($this->tester->getLocator()->utilDataReader()->service()),
            new DataImportConfigurationMapper()
        );

        $configFilePath = Configuration::dataDir() . static::IMPORT_CONFIG_FILE_NAME;
        $dataImportConfigurationTransfer = $dataImportConfigurationParser->parseConfigurationFile($configFilePath);
        $factory = $this->createMock(DataImportBusinessFactory::class);

        $importDumper = new ImporterDumper(
            new DataImporterCollection(),
            $factory,
            []
        );

        // Act
        $dumpedImportersList = $importDumper->getImportersDumpByConfiguration($dataImportConfigurationTransfer);

        // Assert
        $this->assertIsArray($dumpedImportersList);
        $this->assertEmpty($dumpedImportersList);
    }
}
