<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\DataImport\ProductClassDataImportPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group ProductClassDataImportPluginTest
 */
class ProductClassDataImportPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const EXPECTED_IMPORT_COUNT = 3;

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH = 'import/product_class.csv';

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH_INVALID = 'import/product_class_invalid.csv';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->ensureProductClassTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportImportsProductClasses(): void
    {
        // Arrange
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer);

        $productClassDataImportPlugin = new ProductClassDataImportPlugin();
        $productClassDataImportPlugin = $this->overwriteConfig($productClassDataImportPlugin);

        // Act
        $dataImporterReportTransfer = $productClassDataImportPlugin->import($dataImporterConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(static::EXPECTED_IMPORT_COUNT, $dataImporterReportTransfer->getImportedDataSetCount());

        $productClasses = $this->tester->getAllProductClasses();
        $this->assertCount(static::EXPECTED_IMPORT_COUNT, $productClasses, 'Expected number of product classes in database after import');

        $productClassNames = array_map(function ($productClass) {
            return $productClass->getName();
        }, $productClasses);

        $this->assertContains('Hardware', $productClassNames, 'Expected "Hardware" product class in database after import');
        $this->assertContains('Software', $productClassNames, 'Expected "Software" product class in database after import');
        $this->assertContains('Service', $productClassNames, 'Expected "Service" product class in database after import');
    }

    /**
     * @return void
     */
    public function testImportWithInvalidDataThrowsException(): void
    {
        // Arrange
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH_INVALID);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);

        // Act
        $productClassDataImportPlugin = new ProductClassDataImportPlugin();
        $productClassDataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\DataImport\ProductClassDataImportPlugin $productClassDataImportPlugin
     *
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\DataImport\ProductClassDataImportPlugin
     */
    protected function overwriteConfig(ProductClassDataImportPlugin $productClassDataImportPlugin): ProductClassDataImportPlugin
    {
        $moduleNameConstant = '\Pyz\Zed\SelfServicePortal\SelfServicePortalConfig::MODULE_NAME';

        if (!defined($moduleNameConstant)) {
            return $productClassDataImportPlugin;
        }

        $configMock = $this->createPartialMock(SelfServicePortalConfig::class, ['getProductClassDataImporterConfiguration']);
        $configMock->method('getProductClassDataImporterConfiguration')
            ->willReturn(
                (new SelfServicePortalConfig())
                    ->getProductClassDataImporterConfiguration()
                    ->setModuleName(
                        constant($moduleNameConstant),
                    ),
            );

        $productClassDataImportPlugin->setBusinessFactory(
            (new SelfServicePortalBusinessFactory())
                ->setConfig($configMock),
        );

        return $productClassDataImportPlugin;
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        parent::_after();

        $this->tester->truncateProductClassTable();
    }
}
