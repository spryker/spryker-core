<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\ConfigurableBundleDataImport\Communication\Plugin\ConfigurableBundleTemplateSlotDataImportPlugin;
use Spryker\Zed\ConfigurableBundleDataImport\ConfigurableBundleDataImportConfig;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundleDataImport
 * @group Communication
 * @group Plugin
 * @group ConfigurableBundleTemplateSlotDataImportPluginTest
 * Add your own group annotations below this line
 * @group ConfigurableBundle
 */
class ConfigurableBundleTemplateSlotDataImportPluginTest extends Unit
{
    protected const TEST_CONFIGURABLE_BUNDLE_TEMPLATE_KEY = 'test-configurable-bundle-template-key-1';
    protected const TEST_PRODUCT_LIST_KEY = 'test-product-list-key';

    protected const INCORRECT_CONFIGURABLE_BUNDLE_TEMPLATE_KEY = 'incorrect-configurable-bundle-template-key';
    protected const INCORRECT_PRODUCT_LIST_KEY = 'incorrect-product-list-key';

    /**
     * @var \SprykerTest\Zed\ConfigurableBundleDataImport\ConfigurableBundleDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureConfigurableBundleTablesIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->createConfigurableBundleTemplate(static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_KEY);
        $this->tester->createProductList(static::TEST_PRODUCT_LIST_KEY);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/configurable_bundle_template_slot.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $configurableBundleSlotDataImportPlugin = new ConfigurableBundleTemplateSlotDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $configurableBundleSlotDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertConfigurableBundleTemplateSlotDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenConfigurableBundleTemplateNotFoundByKey(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/configurable_bundle_template_slot_configurable_bundle_template_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $configurableBundleSlotDataImportPlugin = new ConfigurableBundleTemplateSlotDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf('Could not find configurable bundle template by key "%s"', static::INCORRECT_CONFIGURABLE_BUNDLE_TEMPLATE_KEY));

        // Act
        $configurableBundleSlotDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductListNotFoundByKey(): void
    {
        // Arrange
        $this->tester->createConfigurableBundleTemplate(static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_KEY);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/configurable_bundle_template_slot_product_list_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $configurableBundleSlotDataImportPlugin = new ConfigurableBundleTemplateSlotDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf('Could not find product list by key "%s"', static::INCORRECT_PRODUCT_LIST_KEY));

        // Act
        $configurableBundleSlotDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $configurableBundleSlotDataImportPlugin = new ConfigurableBundleTemplateSlotDataImportPlugin();

        // Assert
        $this->assertSame(ConfigurableBundleDataImportConfig::IMPORT_TYPE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT, $configurableBundleSlotDataImportPlugin->getImportType());
    }
}
