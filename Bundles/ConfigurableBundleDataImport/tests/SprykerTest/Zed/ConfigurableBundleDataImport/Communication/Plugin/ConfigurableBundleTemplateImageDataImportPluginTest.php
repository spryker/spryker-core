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
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Zed\ConfigurableBundleDataImport\Communication\Plugin\ConfigurableBundleTemplateImageDataImportPlugin;
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
 * @group ConfigurableBundleTemplateImageDataImportPluginTest
 * Add your own group annotations below this line
 * @group ConfigurableBundle
 */
class ConfigurableBundleTemplateImageDataImportPluginTest extends Unit
{
    protected const TEST_CONFIGURABLE_BUNDLE_TEMPLATE_KEY = 'test-configurable-bundle-template-key';
    protected const TEST_PRODUCT_IMAGE_SET_KEY = 'test-product-image-set-key';

    protected const INCORRECT_CONFIGURABLE_BUNDLE_TEMPLATE_KEY = 'incorrect-configurable-bundle-template-key';
    protected const INCORRECT_PRODUCT_IMAGE_SET_KEY = 'incorrect-product-image-set-key';

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
        $this->tester->ensureProductImageTablesIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->createConfigurableBundleTemplate(static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_KEY);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::PRODUCT_IMAGE_SET_KEY => static::TEST_PRODUCT_IMAGE_SET_KEY,
        ]);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/configurable_bundle_template_image.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $configurableBundleImageDataImportPlugin = new ConfigurableBundleTemplateImageDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $configurableBundleImageDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertProductImageSetDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenConfigurableBundleTemplateNotFoundByKey(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/configurable_bundle_template_image_configurable_bundle_template_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $configurableBundleImageDataImportPlugin = new ConfigurableBundleTemplateImageDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf('Could not find configurable bundle template by key "%s"', static::INCORRECT_CONFIGURABLE_BUNDLE_TEMPLATE_KEY));

        // Act
        $configurableBundleImageDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductImageSetNotFoundByKey(): void
    {
        // Arrange
        $this->tester->createConfigurableBundleTemplate(static::TEST_CONFIGURABLE_BUNDLE_TEMPLATE_KEY);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/configurable_bundle_template_image_product_image_set_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $configurableBundleImageDataImportPlugin = new ConfigurableBundleTemplateImageDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf('Could not find product image set by key "%s"', static::INCORRECT_PRODUCT_IMAGE_SET_KEY));

        // Act
        $configurableBundleImageDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $configurableBundleImageDataImportPlugin = new ConfigurableBundleTemplateImageDataImportPlugin();

        // Assert
        $this->assertSame(ConfigurableBundleDataImportConfig::IMPORT_TYPE_CONFIGURABLE_BUNDLE_TEMPLATE_IMAGE, $configurableBundleImageDataImportPlugin->getImportType());
    }
}
